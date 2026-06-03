<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

class FirebasePushService
{
    private ?Messaging $messaging = null;

    public function sendToToken(string $token, string $title, string $body, ?string $url = null): array
    {
        try {
            $this->messaging()->send(
                CloudMessage::withTarget('token', $token)
                    ->withNotification(Notification::create($title, $body))
                    ->withData($this->payloadData($title, $body, $url))
            );

            return ['success' => true];
        } catch (Throwable $e) {
            Log::warning('Firebase push failed', [
                'error' => $e->getMessage(),
                'credentials' => config('firebase.credentials'),
                'project_id' => config('firebase.web.project_id'),
                'messaging_sender_id' => config('firebase.web.messaging_sender_id'),
                'token_prefix' => substr($token, 0, 16),
                'token_length' => strlen($token),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send one notification payload to many FCM tokens.
     *
     * FCM accepts up to 500 tokens per multicast call. The caller can pass a larger
     * list; this method chunks it and returns aggregate counts plus bad tokens.
     *
     * @param array<int, string> $tokens
     * @return array{success:bool,sent:int,failed:int,invalid_tokens:array<int,string>,unknown_tokens:array<int,string>,error?:string}
     */
    public function sendMulticast(array $tokens, string $title, string $body, ?string $url = null): array
    {
        $tokens = array_values(array_unique(array_filter(array_map(
            static fn ($token) => trim((string) $token),
            $tokens
        ))));

        if ($tokens === []) {
            return [
                'success' => true,
                'sent' => 0,
                'failed' => 0,
                'invalid_tokens' => [],
                'unknown_tokens' => [],
            ];
        }

        try {
            $sent = 0;
            $failed = 0;
            $invalidTokens = [];
            $unknownTokens = [];

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($this->payloadData($title, $body, $url));

            foreach (array_chunk($tokens, 500) as $tokenChunk) {
                $report = $this->messaging()->sendMulticast($message, $tokenChunk);

                $sent += $report->successes()->count();
                $failed += $report->failures()->count();
                $invalidTokens = array_merge($invalidTokens, $report->invalidTokens());
                $unknownTokens = array_merge($unknownTokens, $report->unknownTokens());
            }

            return [
                'success' => $failed === 0,
                'sent' => $sent,
                'failed' => $failed,
                'invalid_tokens' => array_values(array_unique($invalidTokens)),
                'unknown_tokens' => array_values(array_unique($unknownTokens)),
            ];
        } catch (Throwable $e) {
            Log::warning('Firebase multicast push failed', [
                'error' => $e->getMessage(),
                'credentials' => config('firebase.credentials'),
                'project_id' => config('firebase.web.project_id'),
                'messaging_sender_id' => config('firebase.web.messaging_sender_id'),
                'token_count' => count($tokens),
            ]);

            return [
                'success' => false,
                'sent' => 0,
                'failed' => count($tokens),
                'invalid_tokens' => [],
                'unknown_tokens' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send one notification payload to a token chunk and return per-token status.
     * Keep chunks <= 500 because that is the maximum FCM multicast target count.
     *
     * @param array<int, string> $tokens
     * @return array{success:bool,sent:int,failed:int,reports:array<string,array{success:bool,error:?string,invalid:bool,unknown:bool}>,invalid_tokens:array<int,string>,unknown_tokens:array<int,string>,error?:string}
     */
    public function sendMulticastDetailed(array $tokens, string $title, string $body, ?string $url = null): array
    {
        $tokens = array_values(array_unique(array_filter(array_map(
            static fn ($token) => trim((string) $token),
            $tokens
        ))));

        if ($tokens === []) {
            return [
                'success' => true,
                'sent' => 0,
                'failed' => 0,
                'reports' => [],
                'invalid_tokens' => [],
                'unknown_tokens' => [],
            ];
        }

        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($this->payloadData($title, $body, $url));

            $report = $this->messaging()->sendMulticast($message, array_slice($tokens, 0, 500));
            $reports = [];

            foreach ($report->getItems() as $item) {
                $token = $item->target()->value();
                $reports[$token] = [
                    'success' => $item->isSuccess(),
                    'error' => $item->error()?->getMessage(),
                    'invalid' => $item->messageTargetWasInvalid(),
                    'unknown' => $item->messageWasSentToUnknownToken(),
                ];
            }

            return [
                'success' => !$report->hasFailures(),
                'sent' => $report->successes()->count(),
                'failed' => $report->failures()->count(),
                'reports' => $reports,
                'invalid_tokens' => $report->invalidTokens(),
                'unknown_tokens' => $report->unknownTokens(),
            ];
        } catch (Throwable $e) {
            Log::warning('Firebase detailed multicast push failed', [
                'error' => $e->getMessage(),
                'credentials' => config('firebase.credentials'),
                'project_id' => config('firebase.web.project_id'),
                'messaging_sender_id' => config('firebase.web.messaging_sender_id'),
                'token_count' => count($tokens),
            ]);

            return [
                'success' => false,
                'sent' => 0,
                'failed' => count($tokens),
                'reports' => array_fill_keys($tokens, [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'invalid' => false,
                    'unknown' => false,
                ]),
                'invalid_tokens' => [],
                'unknown_tokens' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    private function messaging(): Messaging
    {
        if ($this->messaging) {
            return $this->messaging;
        }

        $credentials = config('firebase.credentials');

        if (is_string($credentials) && $credentials !== '' && !str_starts_with($credentials, DIRECTORY_SEPARATOR)) {
            $credentials = base_path($credentials);
        }

        if (!is_string($credentials) || $credentials === '' || !file_exists($credentials)) {
            throw new \RuntimeException('Firebase credentials file not found.');
        }

        return $this->messaging = (new Factory())
            ->withServiceAccount($credentials)
            ->createMessaging();
    }

    private function payloadData(string $title, string $body, ?string $url = null): array
    {
        $data = [
            'title' => $title,
            'body' => $body,
        ];

        if ($url) {
            $data['url'] = $url;
        }

        return $data;
    }
}
