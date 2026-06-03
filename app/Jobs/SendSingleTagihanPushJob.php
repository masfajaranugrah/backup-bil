<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Services\CustomerPushService;
use App\Support\WilayahContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSingleTagihanPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 12;
    public int $timeout = 20;

    public function __construct(public string $tagihanId)
    {
    }

    public function handle(): void
    {
        $tagihan = Tagihan::with(['pelanggan', 'paket'])
            ->where('id', $this->tagihanId)
            ->whereRaw("LOWER(TRIM(COALESCE(status_pembayaran, ''))) = ?", ['belum bayar'])
            ->whereHas('pelanggan', function ($query): void {
                WilayahContext::scopePelanggan($query)
                    ->where('status', 'approve');
            })
            ->first();

        if (!$tagihan) {
            Log::info('SendSingleTagihanPushJob skipped: tagihan not found/already paid', [
                'tagihan_id' => $this->tagihanId,
            ]);
            return;
        }

        $pelanggan = $tagihan->pelanggan;
        if (!$pelanggan || (empty($pelanggan->fcm_token) && empty($pelanggan->webpushr_sid))) {
            Log::info('SendSingleTagihanPushJob skipped: push token empty', [
                'tagihan_id' => $tagihan->id,
                'pelanggan_id' => $pelanggan?->id,
            ]);
            return;
        }

        $amount = (int) ($tagihan->harga ?? $tagihan->paket?->harga ?? 0);
        $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');

        $title = 'Tagihan Baru';
        $message = "Halo {$pelanggan->nama_lengkap}, tagihan Anda sebesar {$formattedAmount} telah diterbitkan. Silakan cek detailnya.";
        $targetUrl = WilayahContext::customerAppUrl('/dashboard/customer/tagihan');

        if (trim((string) ($pelanggan->fcm_token ?? '')) !== '') {
            SendFcmPushJob::dispatch((string) $pelanggan->id, $title, $message, $targetUrl);

            Log::info('SendSingleTagihanPushJob queued FCM job', [
                'tagihan_id' => $tagihan->id,
                'pelanggan_id' => $pelanggan->id,
                'queue' => config('queue.fcm_queue', 'fcm'),
            ]);
            return;
        }

        $result = $this->sendWebpushrNotification($pelanggan, $title, $message, $targetUrl);

        if ($result['success']) {
            Log::info('SendSingleTagihanPushJob success', [
                'tagihan_id' => $tagihan->id,
                'pelanggan_id' => $pelanggan->id,
            ]);
            return;
        }

        if (!empty($result['rate_limited'])) {
            $delay = $this->nextDelaySeconds();
            Log::warning('SendSingleTagihanPushJob rate_limited, will retry', [
                'tagihan_id' => $tagihan->id,
                'pelanggan_id' => $pelanggan->id,
                'attempt' => $this->attempts(),
                'retry_in_seconds' => $delay,
                'http_code' => $result['http_code'] ?? null,
                'response' => $result['response'] ?? null,
            ]);
            $this->release($delay);
            return;
        }

        Log::error('SendSingleTagihanPushJob failed', [
            'tagihan_id' => $tagihan->id,
            'pelanggan_id' => $pelanggan->id,
            'http_code' => $result['http_code'] ?? null,
            'error' => $result['error'] ?? null,
            'response' => $result['response'] ?? null,
        ]);
    }

    private function nextDelaySeconds(): int
    {
        $schedule = [60, 120, 300, 600, 900, 1200, 1800, 3600];
        $index = min(max($this->attempts() - 1, 0), count($schedule) - 1);
        return $schedule[$index];
    }

    private function sendWebpushrNotification($pelanggan, string $title, string $message, string $targetUrl): array
    {
        return app(CustomerPushService::class)->sendWebpushrToPelanggan($pelanggan, $title, $message, $targetUrl);
    }
}
