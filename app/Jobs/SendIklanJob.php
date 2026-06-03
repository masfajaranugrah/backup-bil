<?php

namespace App\Jobs;

use App\Models\Iklan;
use App\Models\Pelanggan;
use App\Services\CustomerPushService;
use App\Services\FirebasePushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendIklanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 0; // allow long running batches

    public function __construct(public string $iklanId)
    {
    }

    public function handle(): void
    {
        $iklan = Iklan::find($this->iklanId);
        if (!$iklan) {
            Log::warning('SendIklanJob: iklan not found', ['iklan_id' => $this->iklanId]);
            return;
        }

        $totalFcmTargets = Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) != ''")
            ->count();

        $totalWebpushFallbackTargets = Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) = ''")
            ->whereRaw("TRIM(COALESCE(webpushr_sid, '')) != ''")
            ->count();

        $targetTotal = $totalFcmTargets + $totalWebpushFallbackTargets;

        $iklan->update([
            'status' => 'active',
            'sent_at' => null,
            'total_sent' => 0,
        ]);

        $this->updateProgress([
            'status' => 'processing',
            'total' => $targetTotal,
            'processed' => 0,
            'sent' => 0,
            'failed' => 0,
        ]);

        $sent = 0;
        $failed = 0;
        $totalTargets = 0;
        $pushService = app(CustomerPushService::class);
        $firebasePushService = app(FirebasePushService::class);

        Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) != ''")
            ->select(['id', 'fcm_token'])
            ->chunkById(500, function ($customers) use (&$sent, &$failed, &$totalTargets, $firebasePushService, $iklan, $targetTotal): void {
                $tokens = [];
                $tokenToCustomerIds = [];

                foreach ($customers as $customer) {
                    $token = trim((string) $customer->fcm_token);
                    if ($token === '') {
                        continue;
                    }

                    $tokens[] = $token;
                    $tokenToCustomerIds[$token][] = $customer->id;
                }

                if ($tokens === []) {
                    return;
                }

                $totalTargets += count($customers);
                $result = $firebasePushService->sendMulticastDetailed(
                    $tokens,
                    $iklan->title,
                    $iklan->message,
                    url('/dashboard/customer/tagihan/home')
                );

                $reports = $result['reports'] ?? [];

                foreach ($tokenToCustomerIds as $token => $customerIds) {
                    $report = $reports[$token] ?? null;
                    $ok = is_array($report) ? ((bool) ($report['success'] ?? false)) : false;

                    if ($ok) {
                        $sent += count($customerIds);
                        continue;
                    }

                    $failed += count($customerIds);
                }

                $invalidOrUnknownTokens = array_values(array_unique(array_merge(
                    $result['invalid_tokens'] ?? [],
                    $result['unknown_tokens'] ?? []
                )));

                if ($invalidOrUnknownTokens !== []) {
                    Pelanggan::query()
                        ->whereIn('fcm_token', $invalidOrUnknownTokens)
                        ->update(['fcm_token' => null]);
                }

                $this->updateProgress([
                    'status' => 'processing',
                    'total' => $targetTotal,
                    'processed' => $sent + $failed,
                    'sent' => $sent,
                    'failed' => $failed,
                ]);
            });

        Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) = ''")
            ->whereRaw("TRIM(COALESCE(webpushr_sid, '')) != ''")
            ->select(['id', 'webpushr_sid'])
            ->chunkById(500, function ($customers) use (&$sent, &$failed, &$totalTargets, $pushService, $iklan, $targetTotal): void {
                $totalTargets += count($customers);

                foreach ($customers as $customer) {
                    try {
                        $result = $pushService->sendWebpushrToPelanggan(
                            $customer,
                            $iklan->title,
                            $iklan->message,
                            url('/dashboard/customer/tagihan/home')
                        );

                        if (($result['success'] ?? false) === true) {
                            $sent++;
                        } else {
                            $failed++;
                        }
                    } catch (\Throwable $e) {
                        $failed++;
                        Log::error('SendIklanJob: webpushr exception', [
                            'pelanggan_id' => $customer->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                $this->updateProgress([
                    'status' => 'processing',
                    'total' => $targetTotal,
                    'processed' => $sent + $failed,
                    'sent' => $sent,
                    'failed' => $failed,
                ]);
            });

        $iklan->update([
            'status' => 'active',
            'sent_at' => now(),
            'total_sent' => $sent,
        ]);

        Log::info('SendIklanJob finished', [
            'iklan_id' => $iklan->id,
            'sent' => $sent,
            'failed' => $failed,
            'total_targets' => $totalTargets,
        ]);

        $this->updateProgress([
            'status' => 'completed',
            'total' => max($targetTotal, $totalTargets),
            'processed' => $sent + $failed,
            'sent' => $sent,
            'failed' => $failed,
            'finished_at' => now()->toDateTimeString(),
        ]);
    }

    private function updateProgress(array $progress): void
    {
        Cache::put('iklan_push_progress:' . $this->iklanId, $progress, now()->addHours(6));
    }
}
