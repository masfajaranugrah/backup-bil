<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Models\TagihanNotificationLog;
use App\Services\CustomerPushService;
use App\Support\WilayahContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendTagihanPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0; // allow long running
    public $tries = 0; // unlimited; WithoutOverlapping releases count as attempts
    public $maxExceptions = 3; // hanya gagal jika ada 3 exception asli

    /**
     * @param array<int|string> $tagihanIds
     */
    public function __construct(public array $tagihanIds, public ?string $batchId = null)
    {
        // Pastikan masuk ke queue default tanpa mendefinisikan properti duplikat
        $this->onQueue('default');
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('tagihan-broadcast-push'))
                ->releaseAfter(30)
                ->expireAfter(3600),
        ];
    }

    public function handle(): void
    {
        $sent = 0;
        $ignored = 0;
        $failed = 0;
        $batchCounter = 0;

        // --- Konfigurasi throttle ---
        // Job ini dipakai untuk broadcast tagihan. Tagihan satu pelanggan tetap memakai SendSingleTagihanPushJob.
        $batchSize = max(1, (int) env('WEBPUSHR_BATCH_SIZE', 400));
        $perRequestDelaySec = max(0, (int) env('WEBPUSHR_DELAY_SECONDS', 1));
        $batchPauseMinutes = max(0, (int) env('WEBPUSHR_BATCH_PAUSE_MINUTES', 0));

        Log::info('SendTagihanPushJob started', [
            'total_ids' => count($this->tagihanIds),
            'batch_size' => $batchSize,
            'delay_per_request_s' => $perRequestDelaySec,
            'batch_pause_min' => $batchPauseMinutes,
            'batch_id' => $this->batchId,
        ]);

        // Job ini hanya untuk fallback WebPushr. Pelanggan dengan FCM diproses SendFcmTagihanPushJob di queue fcm.
        $tagihans = Tagihan::query()
            ->whereIn('id', $this->tagihanIds)
            ->whereRaw("LOWER(TRIM(COALESCE(status_pembayaran, ''))) = ?", ['belum bayar'])
            ->whereHas('pelanggan', function ($query): void {
                WilayahContext::scopePelanggan($query)
                    ->where('status', 'approve')
                    ->where(function ($fcmQuery): void {
                    $fcmQuery->whereNull('fcm_token')
                        ->orWhere('fcm_token', '');
                })->whereNotNull('webpushr_sid')
                    ->where('webpushr_sid', '!=', '');
            })
            ->with(['pelanggan:id,nama_lengkap,webpushr_sid,fcm_token'])
            ->get();

        $totalToProcess = $tagihans->count();
        Log::info("SendTagihanPushJob: found {$totalToProcess} tagihan with WebPushr fallback token");

        foreach ($tagihans as $tagihan) {
            try {
                $pelanggan = $tagihan->pelanggan;
                $sid = trim((string) ($pelanggan?->webpushr_sid ?? ''));

                if (!$pelanggan || $sid === '') {
                    $ignored++;
                    $this->markLog($tagihan->id, 'skipped', 'webpushr', 'WebPushr SID kosong.');
                    Log::info('SendTagihanPushJob ignored: missing WebPushr SID', [
                        'tagihan_id' => $tagihan->id,
                        'pelanggan_id' => $tagihan->pelanggan_id,
                        'batch_id' => $this->batchId,
                    ]);
                    continue;
                }

                // ---- Kirim notifikasi ----
                $title = 'Informasi Tagihan';
                $message = 'Tagihan bulan ini telah tersedia. Silakan melakukan pembayaran sebelum batas waktu yang ditentukan agar layanan tetap berjalan dengan baik.';
                $targetUrl = WilayahContext::customerAppUrl('/dashboard/customer/tagihan');

                $result = $this->sendWebpushrNotification($pelanggan, $title, $message, $targetUrl);

                if ($result['success']) {
                    $sent++;
                    $this->markLog($tagihan->id, 'sent', 'webpushr');
                    Log::info("SendTagihanPushJob: sent WebPushr #{$sent} to {$pelanggan->nama_lengkap} (tagihan #{$tagihan->id})");
                } elseif (!empty($result['rate_limited'])) {
                    $failed++;
                    $this->markLog($tagihan->id, 'failed', 'webpushr', 'Rate limited oleh provider WebPushr.');
                    Log::warning('SendTagihanPushJob rate limited by provider, waiting extra 60s', [
                        'tagihan_id' => $tagihan->id,
                        'http_code' => $result['http_code'] ?? null,
                        'batch_id' => $this->batchId,
                    ]);
                    // Jika kena rate limit dari provider, tunggu 60 detik lalu lanjut
                    sleep(60);
                } else {
                    $failed++;
                    $this->markLog(
                        $tagihan->id,
                        'failed',
                        'webpushr',
                        (string) ($result['error'] ?? $result['response']['description'] ?? 'Gagal mengirim WebPushr.')
                    );
                    Log::error('SendTagihanPushJob failed send', [
                        'tagihan_id' => $tagihan->id,
                        'http_code' => $result['http_code'] ?? null,
                        'error' => $result['error'] ?? null,
                        'batch_id' => $this->batchId,
                    ]);
                }

                // Progress log setiap 50 pelanggan
                if (($batchCounter + 1) % 50 === 0) {
                    Log::info("SendTagihanPushJob progress: " . ($batchCounter + 1) . "/{$totalToProcess} processed (sent_webpushr={$sent}, failed={$failed}, ignored={$ignored})");
                }

                $batchCounter++;

                if ($perRequestDelaySec > 0) {
                    sleep($perRequestDelaySec);
                }

                // ---- Jeda antar batch hanya jika masih ada item setelah batch ini ----
                if ($batchPauseMinutes > 0 && $batchCounter < $totalToProcess && $batchCounter % $batchSize === 0) {
                    $batchNumber = $batchCounter / $batchSize;
                    $pauseSeconds = $batchPauseMinutes * 60;

                    Log::info("SendTagihanPushJob: batch #{$batchNumber} selesai ({$batchCounter}/{$totalToProcess}). Jeda {$batchPauseMinutes} menit...");

                    sleep($pauseSeconds);

                    $nextBatch = $batchNumber + 1;
                    Log::info("SendTagihanPushJob: jeda selesai, melanjutkan batch #{$nextBatch}...");
                }

            } catch (\Throwable $e) {
                $failed++;
                $this->markLog($tagihan->id, 'failed', 'webpushr', $e->getMessage());
                Log::error('SendTagihanPushJob error per item', [
                    'tagihan_id' => $tagihan->id,
                    'error' => $e->getMessage(),
                    'batch_id' => $this->batchId,
                ]);
                // Tetap jeda meskipun error, agar tidak burst
                if ($perRequestDelaySec > 0) {
                    sleep($perRequestDelaySec);
                }
                $batchCounter++;
            }
        }

        Log::info('SendTagihanPushJob finished', [
            'sent_webpushr' => $sent,
            'ignored' => $ignored,
            'failed' => $failed,
            'total_processed' => $batchCounter,
            'batch_id' => $this->batchId,
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('SendTagihanPushJob failed permanently', [
            'error' => $e->getMessage(),
            'tagihan_ids_count' => count($this->tagihanIds),
            'batch_id' => $this->batchId,
        ]);

        if (!$this->batchId) {
            return;
        }

        TagihanNotificationLog::query()
            ->where('batch_id', $this->batchId)
            ->whereIn('tagihan_id', $this->tagihanIds)
            ->where('provider', 'webpushr')
            ->where('status', 'pending')
            ->update([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'updated_at' => now(),
            ]);
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

    private function sendWebpushrNotification($pelanggan, string $title, string $message, string $targetUrl): array
    {
        return app(CustomerPushService::class)->sendWebpushrToPelanggan($pelanggan, $title, $message, $targetUrl);
    }
}
