<?php

namespace App\Jobs;

use App\Models\Iklan;
use App\Models\Pelanggan;
use App\Services\CustomerPushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendIklanPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;     // jangan dimatiin walau lama
    public $tries = 3;       // retry kalau gagal

    public function __construct(public string $iklanId) {}

    public function handle()
    {
        $iklan = Iklan::find($this->iklanId);

        if (!$iklan) {
            Log::warning('Iklan tidak ditemukan', ['iklan_id' => $this->iklanId]);
            return;
        }

        Log::info('Mulai kirim push iklan', ['iklan_id' => $iklan->id]);

        $sentCount = 0;
        $failedCount = 0;

        // PENTING: pakai cursor supaya aman 10.000+
        $pelanggans = Pelanggan::where(function ($query) {
                $query->where(function ($tokenQuery) {
                    $tokenQuery->whereNotNull('fcm_token')
                        ->where('fcm_token', '!=', '');
                })->orWhere(function ($sidQuery) {
                    $sidQuery->whereNotNull('webpushr_sid')
                        ->where('webpushr_sid', '!=', '');
                });
            })
            ->cursor();

        $pushService = app(CustomerPushService::class);

        foreach ($pelanggans as $pelanggan) {
            try {
                $result = $pushService->sendToPelanggan(
                    $pelanggan,
                    $iklan->title,
                    $iklan->message,
                    url('/dashboard/customer/tagihan/home')
                );

                if ($result['success']) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }

                // throttle ringan biar API & server adem
                usleep(200000); // 0.2 detik

            } catch (\Throwable $e) {
                $failedCount++;
                Log::error('Gagal kirim push', [
                    'pelanggan_id' => $pelanggan->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $iklan->update([
            'total_sent' => $sentCount,
            'status' => 'sent',
            'sent_at' => now()
        ]);

        Log::info('Selesai kirim push iklan', [
            'iklan_id' => $iklan->id,
            'sent' => $sentCount,
            'failed' => $failedCount
        ]);
    }

}
