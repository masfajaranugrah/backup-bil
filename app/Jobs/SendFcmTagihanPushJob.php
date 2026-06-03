<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Models\TagihanNotificationLog;
use App\Models\Pelanggan;
use App\Services\FirebasePushService;
use App\Support\WilayahContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendFcmTagihanPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;
    public int $backoff = 30;

    /**
     * @param array<int|string> $tagihanIds
     */
    public function __construct(
        public array $tagihanIds,
        public ?string $batchId = null,
        public string $notificationType = 'reminder'
    )
    {
        $this->onQueue(config('queue.fcm_queue', 'fcm'));
    }

    public function handle(FirebasePushService $firebasePushService): void
    {
        $tagihans = Tagihan::query()
            ->whereIn('id', $this->tagihanIds)
            ->whereRaw("LOWER(TRIM(COALESCE(status_pembayaran, ''))) = ?", ['belum bayar'])
            ->whereHas('pelanggan', function ($query): void {
                WilayahContext::scopePelanggan($query)
                    ->where('status', 'approve')
                    ->whereNotNull('fcm_token')
                    ->where('fcm_token', '!=', '');
            })
            ->with(['pelanggan:id,nama_lengkap,fcm_token', 'paket:id,harga'])
            ->get();

        if ($tagihans->isEmpty()) {
            Log::info('SendFcmTagihanPushJob skipped: no FCM tokens', [
                'total_ids' => count($this->tagihanIds),
                'batch_id' => $this->batchId,
            ]);
            return;
        }

        $chunkSize = min(500, max(1, (int) env('FCM_MULTICAST_CHUNK_SIZE', 500)));
        $delayMs = max(0, (int) env('FCM_MULTICAST_DELAY_MS', 200));
        Log::info('SendFcmTagihanPushJob started', [
            'queue' => config('queue.fcm_queue', 'fcm'),
            'tagihan_count' => count($this->tagihanIds),
            'token_count' => $tagihans->count(),
            'chunk_size' => $chunkSize,
            'delay_ms' => $delayMs,
            'batch_id' => $this->batchId,
            'notification_type' => $this->notificationType,
        ]);

        $sent = 0;
        $failed = 0;
        $payloadGroups = [];
        $tokenToPelangganIds = [];

        foreach ($tagihans as $tagihan) {
            $pelanggan = $tagihan->pelanggan;
            $token = trim((string) ($pelanggan?->fcm_token ?? ''));

            if (!$pelanggan || $token === '') {
                $failed++;
                $this->markLog($tagihan->id, 'failed', 'firebase', 'Token FCM pelanggan kosong.');
                continue;
            }

            $payload = $this->notificationPayload($tagihan);
            $payloadKey = md5($payload['title'] . '|' . $payload['message'] . '|' . $payload['target_url']);
            $payloadGroups[$payloadKey]['payload'] = $payload;
            $payloadGroups[$payloadKey]['tokens'][$token][] = $tagihan->id;
            $tokenToPelangganIds[$token][] = $pelanggan->id;
        }

        $chunkIndex = 0;
        foreach ($payloadGroups as $payloadGroup) {
            $payload = $payloadGroup['payload'];
            $tokenToTagihanIds = $payloadGroup['tokens'];

            foreach (array_chunk(array_keys($tokenToTagihanIds), $chunkSize) as $tokenChunk) {
                $chunkIndex++;

                try {
                    $result = $firebasePushService->sendMulticastDetailed(
                        $tokenChunk,
                        $payload['title'],
                        $payload['message'],
                        $payload['target_url']
                    );
                    $reports = $result['reports'] ?? [];

                    foreach ($tokenChunk as $token) {
                        $report = $reports[$token] ?? [
                            'success' => false,
                            'error' => $result['error'] ?? 'Gagal mengirim FCM.',
                            'invalid' => false,
                            'unknown' => false,
                        ];
                        $tagihanIds = $tokenToTagihanIds[$token] ?? [];

                        if (($report['success'] ?? false) === true) {
                            $sent += count($tagihanIds);
                            foreach ($tagihanIds as $tagihanId) {
                                $this->markLog($tagihanId, 'sent', 'firebase');
                            }
                            continue;
                        }

                        $failed += count($tagihanIds);
                        $error = (string) ($report['error'] ?? 'Gagal mengirim FCM.');

                        foreach ($tagihanIds as $tagihanId) {
                            $this->markLog($tagihanId, 'failed', 'firebase', $error);
                        }

                        if (($report['invalid'] ?? false) || ($report['unknown'] ?? false)) {
                            Pelanggan::query()
                                ->whereIn('id', array_unique($tokenToPelangganIds[$token] ?? []))
                                ->where('fcm_token', $token)
                                ->update(['fcm_token' => null]);
                        }
                    }

                    Log::info('SendFcmTagihanPushJob multicast chunk finished', [
                        'chunk' => $chunkIndex,
                        'token_count' => count($tokenChunk),
                        'sent_tokens' => $result['sent'] ?? 0,
                        'failed_tokens' => $result['failed'] ?? 0,
                        'batch_id' => $this->batchId,
                        'notification_type' => $this->notificationType,
                    ]);

                    if ($delayMs > 0 && $chunkIndex * $chunkSize < $tagihans->count()) {
                        usleep($delayMs * 1000);
                    }
                } catch (\Throwable $e) {
                    foreach ($tokenChunk as $token) {
                        $tagihanIds = $tokenToTagihanIds[$token] ?? [];
                        $failed += count($tagihanIds);

                        foreach ($tagihanIds as $tagihanId) {
                            $this->markLog($tagihanId, 'failed', 'firebase', $e->getMessage());
                        }
                    }

                    Log::warning('SendFcmTagihanPushJob failed per multicast chunk', [
                        'chunk' => $chunkIndex,
                        'token_count' => count($tokenChunk),
                        'batch_id' => $this->batchId,
                        'notification_type' => $this->notificationType,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        Log::info('SendFcmTagihanPushJob finished', [
            'queue' => config('queue.fcm_queue', 'fcm'),
            'token_count' => $tagihans->count(),
            'sent' => $sent,
            'failed' => $failed,
            'batch_id' => $this->batchId,
            'notification_type' => $this->notificationType,
        ]);
    }

    private function notificationPayload(Tagihan $tagihan): array
    {
        if ($this->notificationType === 'created') {
            $amount = (int) ($tagihan->harga ?? $tagihan->paket?->harga ?? 0);
            $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');
            $name = trim((string) ($tagihan->pelanggan?->nama_lengkap ?? 'Pelanggan'));

            return [
                'title' => 'Tagihan Baru',
                'message' => "Halo {$name}, tagihan baru Anda sebesar {$formattedAmount} telah diterbitkan. Silakan cek detail tagihan.",
                'target_url' => WilayahContext::customerAppUrl('/dashboard/customer/tagihan'),
            ];
        }

        return [
            'title' => 'Reminder Tagihan',
            'message' => 'Tagihan internet Anda belum dibayar. Silakan segera lakukan pembayaran agar layanan tetap aktif.',
            'target_url' => WilayahContext::customerAppUrl('/dashboard/customer/tagihan'),
        ];
    }

    private function markLog(string $tagihanId, string $status, string $provider, ?string $message = null): void
    {
        if (!$this->batchId) {
            return;
        }

        TagihanNotificationLog::query()
            ->where('batch_id', $this->batchId)
            ->where('tagihan_id', $tagihanId)
            ->update([
                'status' => $status,
                'provider' => $provider,
                'message' => $message,
                'sent_at' => $status === 'sent' ? now() : null,
                'updated_at' => now(),
            ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('SendFcmTagihanPushJob failed permanently', [
            'error' => $e->getMessage(),
            'tagihan_ids_count' => count($this->tagihanIds),
            'batch_id' => $this->batchId,
            'notification_type' => $this->notificationType,
        ]);

        if (!$this->batchId) {
            return;
        }

        TagihanNotificationLog::query()
            ->where('batch_id', $this->batchId)
            ->whereIn('tagihan_id', $this->tagihanIds)
            ->where('provider', 'firebase')
            ->where('status', 'pending')
            ->update([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'updated_at' => now(),
            ]);
    }
}
