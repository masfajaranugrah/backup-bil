<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\TagihanNotificationLog;
use App\Jobs\SendBroadcastInfoPushJob;
use App\Jobs\SendFcmTagihanPushJob;
use App\Jobs\SendTagihanPushJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Support\WilayahContext;

class PushNotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

            $query = Tagihan::with(['pelanggan', 'paket'])
                ->select('tagihans.*')
                ->join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->where('tagihans.status_pembayaran', 'belum bayar')
                ->orderBy('tagihans.created_at', 'desc');

            // Search filter
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('pelanggans.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('pelanggans.nomer_id', 'like', "%{$search}%")
                        ->orWhere('pelanggans.no_whatsapp', 'like', "%{$search}%");
                });
            }

            // Use Laravel pagination (40 per page)
            $tagihans = $query->paginate(40)->withQueryString()->through(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->pelanggan->nama_lengkap ?? '-',
                    'nomer_id' => $item->pelanggan->nomer_id ?? '-',
                    'no_whatsapp' => $item->pelanggan->no_whatsapp ?? '08xxxxxxxxxx',
                    'paket' => [
                        'id' => $item->paket->id ?? null,
                        'nama_paket' => $item->paket->nama_paket ?? '-',
                        'harga' => $item->paket->harga ?? 0,
                    ],
                    'tanggal_mulai' => $item->tanggal_mulai ?? null,
                    'tanggal_berakhir' => $item->tanggal_berakhir ?? null,
                    'status_pembayaran' => $item->status_pembayaran ?? 'belum bayar',
                    'status_label' => ucwords(str_replace('_', ' ', $item->status_pembayaran ?? 'belum bayar')),
                    'catatan' => $item->catatan ?? '-',
                ];
            });

            // Get total count for "send to all" feature
            $totalTagihan = Tagihan::join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->where('tagihans.status_pembayaran', 'belum bayar')
                ->count();

            return view('content.apps.PushNotification.push', compact('tagihans', 'totalTagihan'));
        } catch (\Exception $e) {
            Log::error('Error loading push notification page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman');
        }
    }

    /**
     * Get all tagihan IDs for broadcast (AJAX endpoint)
     */
    public function getAllTagihanIds()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

            $ids = Tagihan::join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->where('tagihans.status_pembayaran', 'belum bayar')
                ->pluck('tagihans.id')
                ->toArray();

            return response()->json([
                'success' => true,
                'ids' => $ids,
                'total' => count($ids)
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting all tagihan IDs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'ids' => [],
                'total' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get outstanding tagihan IDs for broadcast (AJAX endpoint)
     * Outstanding = belum bayar dan sudah lewat bulan berjalan.
     */
    public function getOutstandingTagihanIds()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

            $ids = Tagihan::join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->where('tagihans.status_pembayaran', 'belum bayar')
                // Outstanding = bulan sebelum bulan ini (exclude bulan berjalan)
                ->whereDate('tagihans.tanggal_mulai', '<', $startOfMonth)
                ->pluck('tagihans.id')
                ->toArray();

            return response()->json([
                'success' => true,
                'ids' => $ids,
                'total' => count($ids)
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting outstanding tagihan IDs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'ids' => [],
                'total' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim push notification tagihan ke pelanggan
     */
    public function broadcast(Request $request)
    {
        try {
            $validated = $request->validate([
                'tagihan_ids' => ['required', 'array', 'min:1'],
                'tagihan_ids.*' => ['string', 'distinct'],
            ]);
            $tagihanIds = $validated['tagihan_ids'];

            Log::info('[push_notification] broadcast requested', [
                'requested_total' => count($tagihanIds),
                'queue_connection' => config('queue.default'),
            ]);

            if (empty($tagihanIds)) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Tidak ada tagihan yang dipilih'
                ]);
            }

            // Guard: hanya tagihan yang masih berstatus belum bayar.
            $allowedTagihanIds = Tagihan::query()
                ->join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->whereIn('tagihans.id', $tagihanIds)
                ->where('tagihans.status_pembayaran', 'belum bayar')
                ->pluck('tagihans.id')
                ->toArray();

            if (empty($allowedTagihanIds)) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Tidak ada tagihan belum bayar yang valid untuk dikirim',
                ]);
            }

            $batchId = (string) Str::uuid();
            $this->createTagihanPushLogs($batchId, $allowedTagihanIds);
            $dispatchResult = $this->dispatchTagihanPush($allowedTagihanIds, 'broadcast', $batchId);

            if (!($dispatchResult['accepted'] ?? true)) {
                $this->markPendingTagihanPushLogsFailed($batchId, $dispatchResult['message']);

                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'mode' => $dispatchResult['mode'],
                    'batch_id' => $batchId,
                    'message' => $dispatchResult['message'],
                    'total' => count($allowedTagihanIds),
                ], 422);
            }

            return response()->json([
                'success' => true,
                'queued' => $dispatchResult['queued'],
                'mode' => $dispatchResult['mode'],
                'batch_id' => $batchId,
                'progress_url' => route('push.notification.progress', $batchId),
                'message' => $dispatchResult['queued']
                    ? 'Notifikasi sedang dikirim di background melalui queue'
                    : 'Notifikasi sedang diproses langsung (sync fallback karena queue tidak siap)',
                'total' => count($allowedTagihanIds),
            ]);

        } catch (\Throwable $e) {
            return $this->pushErrorResponse('Broadcast error', $e);
        }
    }

    /**
     * Kirim reminder tagihan ke semua pelanggan approve yang masih belum bayar.
     * Endpoint ini tidak mengirim puluhan ribu ID ke browser.
     */
    public function broadcastAll()
    {
        try {
            Log::info('[push_notification] broadcast all requested', [
                'queue_connection' => config('queue.default'),
            ]);

            $allowedTagihanIds = Tagihan::query()
                ->select('tagihans.id')
                ->join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->where('tagihans.status_pembayaran', 'belum bayar')
                ->pluck('tagihans.id')
                ->toArray();

            if (empty($allowedTagihanIds)) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Tidak ada tagihan belum bayar yang valid untuk dikirim',
                ]);
            }

            $batchId = (string) Str::uuid();
            $this->createTagihanPushLogs($batchId, $allowedTagihanIds);
            $dispatchResult = $this->dispatchTagihanPush($allowedTagihanIds, 'broadcast_all', $batchId);

            if (!($dispatchResult['accepted'] ?? true)) {
                $this->markPendingTagihanPushLogsFailed($batchId, $dispatchResult['message']);

                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'mode' => $dispatchResult['mode'],
                    'batch_id' => $batchId,
                    'message' => $dispatchResult['message'],
                    'total' => count($allowedTagihanIds),
                ], 422);
            }

            return response()->json([
                'success' => true,
                'queued' => $dispatchResult['queued'],
                'mode' => $dispatchResult['mode'],
                'batch_id' => $batchId,
                'progress_url' => route('push.notification.progress', $batchId),
                'message' => 'Reminder tagihan sedang dikirim aman melalui queue',
                'total' => count($allowedTagihanIds),
            ]);
        } catch (\Throwable $e) {
            return $this->pushErrorResponse('Broadcast all error', $e);
        }
    }

    /**
     * Kirim push notification khusus outstanding (bulan sebelum bulan ini)
     */
    public function broadcastOutstanding(Request $request)
    {
        try {
            $validated = $request->validate([
                'tagihan_ids' => ['required', 'array', 'min:1'],
                'tagihan_ids.*' => ['string', 'distinct'],
            ]);
            $tagihanIds = $validated['tagihan_ids'];
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

            Log::info('[push_notification] broadcast outstanding requested', [
                'requested_total' => count($tagihanIds),
                'queue_connection' => config('queue.default'),
            ]);

            if (empty($tagihanIds)) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Tidak ada tagihan outstanding yang dipilih'
                ]);
            }

            $allowedTagihanIds = Tagihan::query()
                ->join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
                ->whereIn('tagihans.id', $tagihanIds)
                ->where('tagihans.status_pembayaran', 'belum bayar')
                ->whereDate('tagihans.tanggal_mulai', '<', $startOfMonth)
                ->pluck('tagihans.id')
                ->toArray();

            if (empty($allowedTagihanIds)) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Tidak ada tagihan outstanding yang valid untuk dikirim',
                ]);
            }

            $batchId = (string) Str::uuid();
            $this->createTagihanPushLogs($batchId, $allowedTagihanIds);
            $dispatchResult = $this->dispatchTagihanPush($allowedTagihanIds, 'broadcast_outstanding', $batchId);

            if (!($dispatchResult['accepted'] ?? true)) {
                $this->markPendingTagihanPushLogsFailed($batchId, $dispatchResult['message']);

                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'mode' => $dispatchResult['mode'],
                    'batch_id' => $batchId,
                    'message' => $dispatchResult['message'],
                    'total' => count($allowedTagihanIds),
                ], 422);
            }

            return response()->json([
                'success' => true,
                'queued' => $dispatchResult['queued'],
                'mode' => $dispatchResult['mode'],
                'batch_id' => $batchId,
                'progress_url' => route('push.notification.progress', $batchId),
                'message' => $dispatchResult['queued']
                    ? 'Notifikasi outstanding sedang dikirim di background melalui queue'
                    : 'Notifikasi outstanding sedang diproses langsung (sync fallback karena queue tidak siap)',
                'total' => count($allowedTagihanIds),
            ]);
        } catch (\Throwable $e) {
            return $this->pushErrorResponse('Broadcast outstanding error', $e, 'Terjadi kesalahan saat mengirim notifikasi outstanding');
        }
    }

    /**
     * Kirim broadcast info/iklan ke semua pelanggan
     */
    public function broadcastInfo(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => ['required', 'string', 'min:10', 'max:500'],
            ]);
            $message = trim($validated['message']);

            Log::info('[push_notification] broadcast info requested', [
                'message_length' => mb_strlen((string) $message),
            ]);

            if (empty($message)) {
                return response()->json([
                    'success' => false,
                    'sent' => 0,
                    'ignored' => 0,
                    'message' => 'Pesan tidak boleh kosong'
                ]);
            }

            $targetTotal = Pelanggan::where(function ($query) {
                    $query->whereRaw("TRIM(COALESCE(fcm_token, '')) != ''")
                        ->orWhereRaw("TRIM(COALESCE(webpushr_sid, '')) != ''");
                })
                ->tap(fn ($query) => WilayahContext::scopePelanggan($query))
                ->where('status', 'approve')
                ->count();

            if ($targetTotal === 0) {
                return response()->json([
                    'success' => true,
                    'queued' => false,
                    'sent' => 0,
                    'ignored' => 0,
                    'message' => 'Tidak ada pelanggan dengan token notifikasi yang valid'
                ]);
            }

            $queueConnection = (string) config('queue.default', 'database');
            $jobsTable = (string) config('queue.connections.database.table', 'jobs');
            if ($queueConnection === 'sync' || ($queueConnection === 'database' && !Schema::hasTable($jobsTable))) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Queue belum aman untuk broadcast info. Gunakan database/redis queue dan jalankan queue worker.',
                    'total' => $targetTotal,
                ], 422);
            }

            SendBroadcastInfoPushJob::dispatch($message);

            return response()->json([
                'success' => true,
                'queued' => true,
                'sent' => 0,
                'ignored' => 0,
                'total' => $targetTotal,
                'message' => "Broadcast info untuk {$targetTotal} pelanggan diproses di background"
            ]);

        } catch (\Throwable $e) {
            return $this->pushErrorResponse('Broadcast info error', $e, 'Terjadi kesalahan saat mengirim notifikasi');
        }
    }

    /**
     * Dispatch job push dengan fallback sync jika queue database belum siap.
     *
     * @param array<int|string> $allowedTagihanIds
     * @return array{queued:bool,mode:string,accepted?:bool,message?:string}
     */
    private function dispatchTagihanPush(array $allowedTagihanIds, string $source, string $batchId): array
    {
        $queueConnection = (string) config('queue.default', 'database');
        $jobsTable = (string) config('queue.connections.database.table', 'jobs');
        $syncFallbackLimit = max(1, (int) env('PUSH_SYNC_FALLBACK_LIMIT', 500));
        $fcmJobChunkSize = max(1, (int) env('FCM_TAGIHAN_JOB_CHUNK_SIZE', 2500));
        $webpushrJobChunkSize = max(1, (int) env('WEBPUSHR_TAGIHAN_JOB_CHUNK_SIZE', 2500));

        try {
            if ($queueConnection === 'sync') {
                if (count($allowedTagihanIds) > $syncFallbackLimit) {
                    Log::warning('[push_notification] sync queue connection, large broadcast rejected', [
                        'source' => $source,
                        'total' => count($allowedTagihanIds),
                        'sync_fallback_limit' => $syncFallbackLimit,
                    ]);

                    return [
                        'queued' => false,
                        'mode' => 'sync_queue_rejected',
                        'accepted' => false,
                        'message' => 'QUEUE_CONNECTION=sync tidak aman untuk broadcast besar. Gunakan database/redis queue dan jalankan queue worker terlebih dahulu.',
                    ];
                }

                Log::warning('[push_notification] sync queue connection, processing small broadcast inline', [
                    'source' => $source,
                    'total' => count($allowedTagihanIds),
                ]);

                SendFcmTagihanPushJob::dispatchSync($allowedTagihanIds, $batchId);
                SendTagihanPushJob::dispatchSync($allowedTagihanIds, $batchId);

                return ['queued' => false, 'mode' => 'sync_queue_inline'];
            }

            if ($queueConnection === 'database' && !Schema::hasTable($jobsTable)) {
                if (count($allowedTagihanIds) > $syncFallbackLimit) {
                    Log::warning('[push_notification] queue jobs table missing, large broadcast rejected', [
                        'source' => $source,
                        'jobs_table' => $jobsTable,
                        'total' => count($allowedTagihanIds),
                        'sync_fallback_limit' => $syncFallbackLimit,
                    ]);

                    return [
                        'queued' => false,
                        'mode' => 'queue_missing',
                        'accepted' => false,
                        'message' => 'Queue belum siap untuk broadcast besar. Jalankan migration jobs dan queue worker terlebih dahulu.',
                    ];
                }

                Log::warning('[push_notification] queue jobs table missing, fallback to sync', [
                    'source' => $source,
                    'jobs_table' => $jobsTable,
                    'total' => count($allowedTagihanIds),
                ]);

                SendFcmTagihanPushJob::dispatchSync($allowedTagihanIds, $batchId);
                SendTagihanPushJob::dispatchSync($allowedTagihanIds, $batchId);

                Log::info('[push_notification] sync fallback finished', [
                    'source' => $source,
                    'total' => count($allowedTagihanIds),
                ]);

                return ['queued' => false, 'mode' => 'sync_fallback'];
            }

            foreach (array_chunk($allowedTagihanIds, $fcmJobChunkSize) as $tagihanIdChunk) {
                SendFcmTagihanPushJob::dispatch($tagihanIdChunk, $batchId);
            }

            foreach (array_chunk($allowedTagihanIds, $webpushrJobChunkSize) as $tagihanIdChunk) {
                SendTagihanPushJob::dispatch($tagihanIdChunk, $batchId);
            }

            Log::info('[push_notification] queued successfully', [
                'source' => $source,
                'total' => count($allowedTagihanIds),
                'fcm_job_chunk_size' => $fcmJobChunkSize,
                'fcm_job_count' => (int) ceil(count($allowedTagihanIds) / $fcmJobChunkSize),
                'webpushr_job_chunk_size' => $webpushrJobChunkSize,
                'webpushr_job_count' => (int) ceil(count($allowedTagihanIds) / $webpushrJobChunkSize),
                'queue_connection' => $queueConnection,
            ]);

            return ['queued' => true, 'mode' => 'queue'];
        } catch (\Throwable $e) {
            Log::error('[push_notification] dispatch failed', [
                'source' => $source,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @param array<int|string> $tagihanIds
     */
    private function createTagihanPushLogs(string $batchId, array $tagihanIds): void
    {
        $now = now();

        Tagihan::query()
            ->select('tagihans.*')
            ->join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
            ->whereIn('tagihans.id', $tagihanIds)
            ->where('tagihans.status_pembayaran', 'belum bayar')
            ->with(['pelanggan:id,nama_lengkap,nomer_id,fcm_token,webpushr_sid'])
            ->chunkById(500, function ($tagihans) use ($batchId, $now): void {
                $rows = [];

                foreach ($tagihans as $tagihan) {
                    $pelanggan = $tagihan->pelanggan;
                    $fcmToken = trim((string) ($pelanggan?->fcm_token ?? ''));
                    $webpushrSid = trim((string) ($pelanggan?->webpushr_sid ?? ''));
                    $provider = $fcmToken !== '' ? 'firebase' : ($webpushrSid !== '' ? 'webpushr' : null);

                    $rows[] = [
                        'id' => (string) Str::uuid(),
                        'batch_id' => $batchId,
                        'tagihan_id' => $tagihan->id,
                        'pelanggan_id' => $pelanggan?->id,
                        'pelanggan_nomer_id' => $pelanggan?->nomer_id,
                        'pelanggan_nama' => $pelanggan?->nama_lengkap,
                        'provider' => $provider,
                        'status' => $provider ? 'pending' : 'skipped',
                        'message' => $provider ? null : 'Token notifikasi pelanggan kosong.',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($rows !== []) {
                    TagihanNotificationLog::insert($rows);
                }
            }, 'tagihans.id', 'id');
    }

    private function markPendingTagihanPushLogsFailed(string $batchId, string $message): void
    {
        TagihanNotificationLog::query()
            ->where('batch_id', $batchId)
            ->where('status', 'pending')
            ->update([
                'status' => 'failed',
                'message' => $message,
                'updated_at' => now(),
            ]);
    }

    public function broadcastProgress(string $batchId)
    {
        $baseQuery = TagihanNotificationLog::query()->where('batch_id', $batchId);

        $total = (clone $baseQuery)->count();
        if ($total === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Log broadcast tidak ditemukan.',
            ], 404);
        }

        $counts = [
            'total' => $total,
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'sent' => (clone $baseQuery)->where('status', 'sent')->count(),
            'failed' => (clone $baseQuery)->where('status', 'failed')->count(),
            'skipped' => (clone $baseQuery)->where('status', 'skipped')->count(),
        ];
        $processed = $counts['sent'] + $counts['failed'] + $counts['skipped'];
        $counts['processed'] = $processed;
        $counts['percent'] = $counts['total'] > 0 ? (int) floor(($processed / $counts['total']) * 100) : 0;
        $counts['finished'] = $counts['pending'] === 0;

        $logs = (clone $baseQuery)
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'failed' THEN 2 WHEN 'skipped' THEN 3 WHEN 'sent' THEN 4 ELSE 5 END")
            ->orderBy('updated_at', 'desc')
            ->limit(200)
            ->get();

        return response()->json([
            'success' => true,
            'batch_id' => $batchId,
            'counts' => $counts,
            'items' => $logs->map(fn(TagihanNotificationLog $log): array => [
                'tagihan_id' => $log->tagihan_id,
                'nama' => $log->pelanggan_nama ?: '-',
                'nomer_id' => $log->pelanggan_nomer_id ?: '-',
                'provider' => $log->provider ?: '-',
                'status' => $log->status,
                'message' => $log->message,
                'sent_at' => optional($log->sent_at)->format('Y-m-d H:i:s'),
            ])->values(),
        ]);
    }

    /**
     * Check apakah ada tagihan pending untuk pelanggan
     * Returns: has_notification, total_tagihan, tunggakan_count, bulan_tunggakan
     */
    public function check($nomer_id)
    {
        try {
            $tagihans = Tagihan::whereHas('pelanggan', function ($q) use ($nomer_id) {
                $q->where('nomer_id', $nomer_id);
            })
                ->where('status_pembayaran', 'belum bayar')
                ->get();

            if ($tagihans->isEmpty()) {
                return response()->json(['has_notification' => false]);
            }

            $now = \Carbon\Carbon::now();

            // Hitung tunggakan (yang sudah lewat tanggal_berakhir)
            $tunggakan = $tagihans->filter(function ($t) use ($now) {
                return $t->tanggal_berakhir && \Carbon\Carbon::parse($t->tanggal_berakhir)->lt($now);
            });

            $tunggakanCount = $tunggakan->count();

            // Hitung bulan tunggakan terlama dari tanggal_berakhir paling awal
            $bulanTunggakan = 0;
            if ($tunggakanCount > 0) {
                $oldest = $tunggakan->sortBy('tanggal_berakhir')->first();
                $bulanTunggakan = (int) \Carbon\Carbon::parse($oldest->tanggal_berakhir)->diffInMonths($now);
                // Minimum 1 bulan jika ada tunggakan
                $bulanTunggakan = max(1, $bulanTunggakan);
            }

            return response()->json([
                'has_notification' => true,
                'total_tagihan' => $tagihans->count(),
                'tunggakan_count' => $tunggakanCount,
                'bulan_tunggakan' => $bulanTunggakan,
            ]);

        } catch (\Exception $e) {
            return response()->json(['has_notification' => false]);
        }
    }

    /**
     * Check apakah ada broadcast info untuk ditampilkan
     */
    public function checkBroadcastInfo($nomer_id)
    {
        try {
            $pelanggan = Pelanggan::where('nomer_id', $nomer_id)->first();

            if (!$pelanggan) {
                return response()->json(['has_info' => false, 'info' => null]);
            }

            // Array info/iklan yang tersedia
            $availableInfos = [
                [
                    'id' => 1,
                    'title' => '?? Promo Spesial Bulan Ini!',
                    'message' => 'Dapatkan diskon 30% untuk upgrade paket internet! Buruan sebelum kehabisan.',
                    'action_url' => '/dashboard/customer/tagihan',
                    'features' => [
                        ['icon' => 'bi-percent', 'text' => 'Diskon 30%'],
                        ['icon' => 'bi-clock-history', 'text' => 'Terbatas']
                    ]
                ],
                [
                    'id' => 2,
                    'title' => '?? Maintenance Terjadwal',
                    'message' => 'Sistem akan maintenance pada Minggu, 15 Desember 2025 pukul 01:00 - 05:00 WIB.',
                    'features' => [
                        ['icon' => 'bi-tools', 'text' => 'Maintenance'],
                        ['icon' => 'bi-calendar-check', 'text' => '15 Des 2025']
                    ]
                ],
                [
                    'id' => 3,
                    'title' => '?? Cashback Spesial!',
                    'message' => 'Bayar tagihan tepat waktu dan dapatkan cashback hingga Rp 50.000!',
                    'features' => [
                        ['icon' => 'bi-cash-coin', 'text' => 'Cashback'],
                        ['icon' => 'bi-gift', 'text' => 'Hingga 50rb']
                    ]
                ],
                [
                    'id' => 4,
                    'title' => '? Upgrade Kecepatan',
                    'message' => 'Nikmati kecepatan internet 2x lebih cepat dengan harga spesial!',
                    'features' => [
                        ['icon' => 'bi-lightning-charge-fill', 'text' => '2x Lebih Cepat'],
                        ['icon' => 'bi-tag-fill', 'text' => 'Harga Spesial']
                    ]
                ]
            ];

            // Pilih info random
            $selectedInfo = $availableInfos[array_rand($availableInfos)];

            return response()->json([
                'has_info' => true,
                'info' => $selectedInfo
            ]);

        } catch (\Exception $e) {
            Log::error('Check broadcast info error: ' . $e->getMessage());
            return response()->json(['has_info' => false, 'info' => null]);
        }
    }

    private function pushErrorResponse(string $context, \Throwable $e, string $message = 'Terjadi kesalahan saat mengirim notifikasi')
    {
        Log::error($context . ': ' . $e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'queued' => false,
            'message' => $message,
        ], 500);
    }
}
