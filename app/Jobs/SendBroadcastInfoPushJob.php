<?php

namespace App\Jobs;

use App\Models\Pelanggan;
use App\Services\CustomerPushService;
use App\Services\FirebasePushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBroadcastInfoPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1800;
    public int $backoff = 60;

    public function __construct(public string $message)
    {
        $this->onQueue('default');
    }

    public function handle(FirebasePushService $firebasePushService, CustomerPushService $pushService): void
    {
        $targetUrl = url('/dashboard/customer/tagihan/home');
        $sent = 0;
        $failed = 0;
        $processed = 0;
        $chunkSize = min(500, max(1, (int) env('FCM_MULTICAST_CHUNK_SIZE', 500)));
        $delayMs = max(0, (int) env('FCM_MULTICAST_DELAY_MS', 200));

        Log::info('SendBroadcastInfoPushJob started', [
            'queue' => 'default',
            'chunk_size' => $chunkSize,
            'delay_ms' => $delayMs,
        ]);

        Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) != ''")
            ->where('nomer_id', 'LIKE', '%JMK-GK%')
            ->where('status', 'approve')
            ->select(['id', 'fcm_token'])
            ->chunkById($chunkSize, function ($pelanggans) use (&$sent, &$failed, &$processed, $firebasePushService, $targetUrl, $delayMs): void {
                $tokens = [];

                foreach ($pelanggans as $pelanggan) {
                    $token = trim((string) $pelanggan->fcm_token);
                    if ($token !== '') {
                        $tokens[] = $token;
                    }
                }

                if ($tokens === []) {
                    return;
                }

                $result = $firebasePushService->sendMulticastDetailed(
                    $tokens,
                    'Info Penting',
                    $this->message,
                    $targetUrl
                );

                $sent += (int) ($result['sent'] ?? 0);
                $failed += (int) ($result['failed'] ?? 0);
                $processed += count($tokens);

                $invalidTokens = array_values(array_unique(array_merge(
                    $result['invalid_tokens'] ?? [],
                    $result['unknown_tokens'] ?? []
                )));

                if ($invalidTokens !== []) {
                    Pelanggan::query()
                        ->whereIn('fcm_token', $invalidTokens)
                        ->update(['fcm_token' => null]);
                }

                Log::info('SendBroadcastInfoPushJob FCM chunk finished', [
                    'processed' => $processed,
                    'sent' => $sent,
                    'failed' => $failed,
                ]);

                if ($delayMs > 0) {
                    usleep($delayMs * 1000);
                }
            });

        Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) = ''")
            ->whereRaw("TRIM(COALESCE(webpushr_sid, '')) != ''")
            ->where('nomer_id', 'LIKE', '%JMK-GK%')
            ->where('status', 'approve')
            ->select(['id', 'webpushr_sid'])
            ->chunkById(500, function ($pelanggans) use (&$sent, &$failed, &$processed, $pushService, $targetUrl): void {
                $delaySeconds = max(0, (int) env('WEBPUSHR_DELAY_SECONDS', 1));

                foreach ($pelanggans as $pelanggan) {
                    $result = $pushService->sendWebpushrToPelanggan(
                        $pelanggan,
                        'Info Penting',
                        $this->message,
                        $targetUrl
                    );

                    if (($result['success'] ?? false) === true) {
                        $sent++;
                    } else {
                        $failed++;
                    }

                    $processed++;

                    if (!empty($result['rate_limited'])) {
                        sleep(60);
                    } elseif ($delaySeconds > 0) {
                        sleep($delaySeconds);
                    }
                }
            });

        Log::info('SendBroadcastInfoPushJob finished', [
            'processed' => $processed,
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }
}
