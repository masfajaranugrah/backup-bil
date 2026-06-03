<?php

namespace App\Services;

use App\Models\Pelanggan;

class CustomerPushService
{
    public function __construct(private readonly FirebasePushService $firebasePushService)
    {
    }

    /**
     * Send one customer notification.
     *
     * Provider selection is strict:
     * - if fcm_token exists, send via FCM only
     * - if fcm_token is empty, send via Webpushr SID
     *
     * @return array{success:bool,provider?:string,fcm_error?:string,http_code?:int,error?:string,response?:mixed,rate_limited?:bool}
     */
    public function sendToPelanggan(Pelanggan $pelanggan, string $title, string $message, string $targetUrl): array
    {
        $fcmToken = trim((string) ($pelanggan->fcm_token ?? ''));

        if ($fcmToken !== '') {
            return $this->sendFcmToPelanggan($pelanggan, $title, $message, $targetUrl);
        }

        return $this->sendWebpushrToPelanggan($pelanggan, $title, $message, $targetUrl);
    }

    /**
     * @return array{success:bool,provider:string,error?:string}
     */
    public function sendFcmToPelanggan(Pelanggan $pelanggan, string $title, string $message, string $targetUrl): array
    {
        $fcmToken = trim((string) ($pelanggan->fcm_token ?? ''));

        if ($fcmToken === '') {
            return [
                'success' => false,
                'provider' => 'firebase',
                'error' => 'Customer has no FCM token.',
            ];
        }

        $fcmResult = $this->firebasePushService->sendToToken($fcmToken, $title, $message, $targetUrl);

        if (($fcmResult['success'] ?? false) === true) {
            return $fcmResult + ['provider' => 'firebase'];
        }

        $fcmError = (string) ($fcmResult['error'] ?? '');
        if ($this->shouldForgetFcmToken($fcmError)) {
            $pelanggan->forceFill(['fcm_token' => null])->save();
        }

        return $fcmResult + ['provider' => 'firebase'];
    }

    /**
     * @return array{success:bool,provider:string,rate_limited?:bool,http_code?:int,error?:string,response?:mixed}
     */
    public function sendWebpushrToPelanggan(Pelanggan $pelanggan, string $title, string $message, string $targetUrl): array
    {
        $webpushrSid = trim((string) ($pelanggan->webpushr_sid ?? ''));

        if ($webpushrSid === '') {
            return [
                'success' => false,
                'provider' => 'webpushr',
                'error' => 'Customer has no Webpushr SID.',
            ];
        }

        $webpushrResult = $this->sendWebpushrNotification([
            'title' => $title,
            'message' => $message,
            'target_url' => $targetUrl,
            'sid' => $webpushrSid,
        ]);

        return $webpushrResult + [
            'provider' => 'webpushr',
        ];
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

    /**
     * @param array{title:string,message:string,target_url:string,sid:string} $data
     * @return array{success:bool,rate_limited?:bool,http_code?:int,error?:string,response?:mixed}
     */
    private function sendWebpushrNotification(array $data): array
    {
        try {
            $ch = curl_init('https://api.webpushr.com/v1/notification/send/sid');

            $payload = [
                'title' => $data['title'],
                'message' => $data['message'],
                'target_url' => $data['target_url'],
                'sid' => $data['sid'],
            ];

            $headers = [
                'Content-Type: application/json',
                'webpushrKey: ' . env('WEBPUSHR_KEY', ''),
                'webpushrAuthToken: ' . env('WEBPUSHR_TOKEN', ''),
            ];

            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CONNECTTIMEOUT => 5,
            ]);

            $responseRaw = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            $response = is_string($responseRaw) ? json_decode($responseRaw, true) : null;
            $type = strtolower((string) ($response['type'] ?? ''));
            $description = strtolower((string) ($response['description'] ?? ''));

            $rateLimited = in_array($httpCode, [420, 429], true)
                || $type === 'rate_limit'
                || str_contains($description, 'too many requests')
                || str_contains($description, 'rate limit');

            if ($httpCode === 200 && !empty($responseRaw) && !$rateLimited) {
                return [
                    'success' => true,
                    'http_code' => $httpCode,
                    'response' => $response ?? $responseRaw,
                ];
            }

            return [
                'success' => false,
                'rate_limited' => $rateLimited,
                'http_code' => $httpCode,
                'error' => $curlError ?: null,
                'response' => $response ?? $responseRaw,
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
