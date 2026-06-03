<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemLogController extends Controller
{
    private const LOG_RELATIVE_PATH = 'storage/logs/laravel.log';
    private const INITIAL_BYTES = 65536;
    private const MAX_CHUNK_BYTES = 262144;

    public function index(): View
    {
        return view('content.apps.System.log-console', [
            'command' => 'root@jernih:/var/www/billingjmk# tail -f storage/logs/laravel.log',
            'logPath' => self::LOG_RELATIVE_PATH,
        ]);
    }

    public function tail(Request $request): JsonResponse
    {
        $path = storage_path('logs/laravel.log');

        if (! is_file($path)) {
            return $this->noCacheJson([
                'exists' => false,
                'content' => '',
                'cursor' => 0,
                'size' => 0,
                'message' => 'File log belum ditemukan.',
            ]);
        }

        clearstatcache(true, $path);

        $size = filesize($path) ?: 0;
        $cursor = max(0, (int) $request->query('cursor', 0));
        $from = $cursor > 0 ? min($cursor, $size) : max(0, $size - self::INITIAL_BYTES);

        if ($cursor > $size) {
            $from = max(0, $size - self::INITIAL_BYTES);
        }

        $truncated = false;
        if (($size - $from) > self::MAX_CHUNK_BYTES) {
            $from = max(0, $size - self::MAX_CHUNK_BYTES);
            $truncated = true;
        }

        $content = '';
        if ($size > $from) {
            $handle = fopen($path, 'rb');

            if ($handle !== false) {
                fseek($handle, $from);
                $content = stream_get_contents($handle) ?: '';
                fclose($handle);
            }
        }

        if ($content !== '' && ! mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        }

        return $this->noCacheJson([
            'exists' => true,
            'content' => $content,
            'cursor' => $size,
            'from' => $from,
            'size' => $size,
            'truncated' => $truncated,
            'updated_at' => date('Y-m-d H:i:s', filemtime($path) ?: time()),
        ]);
    }

    private function noCacheJson(array $payload): JsonResponse
    {
        return response()
            ->json($payload)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }
}
