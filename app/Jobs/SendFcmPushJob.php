<?php

namespace App\Jobs;

use App\Models\Pelanggan;
use App\Services\FirebasePushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendFcmPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 20;

    public function __construct(
        public string $pelangganId,
        public string $title,
        public string $message,
        public string $targetUrl
    ) {
        $this->onQueue(config('queue.fcm_queue', 'fcm'));
    }

    public function handle(FirebasePushService $firebasePushService): void
    {
        $pelanggan = Pelanggan::query()
            ->select(['id', 'nama_lengkap', 'fcm_token'])
            ->find($this->pelangganId);

        if (!$pelanggan || trim((string) ($pelanggan->fcm_token ?? '')) === '') {
            Log::info('SendFcmPushJob skipped: FCM token empty', [
                'pelanggan_id' => $this->pelangganId,
            ]);
            return;
        }

        $result = $firebasePushService->sendToToken(
            trim((string) $pelanggan->fcm_token),
            $this->title,
            $this->message,
            $this->targetUrl
        );

        if (($result['success'] ?? false) === true) {
            Log::info('SendFcmPushJob success', [
                'pelanggan_id' => $pelanggan->id,
                'provider' => $result['provider'] ?? null,
            ]);
            return;
        }

        $error = (string) ($result['error'] ?? '');
        if ($this->shouldForgetFcmToken($error)) {
            $pelanggan->forceFill(['fcm_token' => null])->save();
        }

        Log::error('SendFcmPushJob failed', [
            'pelanggan_id' => $pelanggan->id,
            'provider' => 'firebase',
            'error' => $error,
            'response' => $result['response'] ?? null,
        ]);
    }

    private function shouldForgetFcmToken(string $error): bool
    {
        $error = strtolower($error);

        return str_contains($error, 'requested entity was not found')
            || str_contains($error, 'registration-token-not-registered')
            || str_contains($error, 'notregistered')
            || str_contains($error, 'unregistered')
            || str_contains($error, 'senderid mismatch')
            || str_contains($error, 'sender id mismatch')
            || str_contains($error, 'mismatched-credential')
            || str_contains($error, 'mismatched sender')
            || str_contains($error, 'invalid registration token')
            || str_contains($error, 'invalid-argument');
    }
}
