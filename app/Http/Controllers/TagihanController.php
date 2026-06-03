<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Rekening;
use App\Jobs\SendFcmPushJob;
use App\Jobs\SendFcmTagihanPushJob;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BayarExport;
use App\Exports\TagihanExport;
use App\Exports\TagihanBelumLunasMonthlyExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TagihanController extends Controller
{
    private function getBuktiPembayaranBasePathByNomorId(?string $nomorId): string
    {
        $isJmkGk = Str::startsWith(strtoupper(trim((string) $nomorId)), 'JMK-GK');

        if ($isJmkGk) {
            return rtrim(env('JMKGK_PUBLIC_STORAGE_PATH', '/var/www/billingJMKGK/storage/app/public'), '/');
        }

        return rtrim(env('JMK_PUBLIC_STORAGE_PATH', '/var/www/billingjmk/storage/app/public'), '/');
    }

    private function storeBuktiPembayaranByNomorId($file, ?string $nomorId): string
    {
        $basePath = $this->getBuktiPembayaranBasePathByNomorId($nomorId);
        $targetDir = $basePath . '/bukti_pembayaran';

        try {
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            if (is_dir($targetDir) && is_writable($targetDir)) {
                $filename = now()->format('YmdHis') . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $filename);
                return 'bukti_pembayaran/' . $filename;
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal simpan bukti ke path khusus, fallback ke public disk.', [
                'nomor_id' => $nomorId,
                'target_dir' => $targetDir,
                'error' => $e->getMessage(),
            ]);
        }

        return $file->store('bukti_pembayaran', 'public');
    }

    private function deleteBuktiPembayaranByNomorId(?string $buktiPath, ?string $nomorId): void
    {
        $buktiPath = trim((string) $buktiPath);
        if ($buktiPath === '') {
            return;
        }

        if (str_starts_with($buktiPath, 'storage/')) {
            $buktiPath = substr($buktiPath, 8);
        }

        $basePath = $this->getBuktiPembayaranBasePathByNomorId($nomorId);
        $absolutePath = $basePath . '/' . ltrim($buktiPath, '/');

        if (is_file($absolutePath)) {
            @unlink($absolutePath);
            return;
        }

        if (Storage::disk('public')->exists($buktiPath)) {
            Storage::disk('public')->delete($buktiPath);
        }
    }

    private function deleteKwitansi(?string $kwitansiPath): void
    {
        $kwitansiPath = trim((string) $kwitansiPath);
        if ($kwitansiPath === '') {
            return;
        }

        if (str_starts_with($kwitansiPath, 'storage/')) {
            $kwitansiPath = substr($kwitansiPath, 8);
        }

        if (Storage::disk('public')->exists($kwitansiPath)) {
            Storage::disk('public')->delete($kwitansiPath);
        }
    }

    private function deleteIncomeForTagihan(Tagihan $tagihan): int
    {
        $amount = $tagihan->jumlah_tagihan ?? $tagihan->paket?->harga;
        $description = 'Pembayaran paket ' . ($tagihan->paket?->nama_paket ?? '') . ' dari ' . ($tagihan->pelanggan?->nama_lengkap ?? '');

        $incomeByRelation = Income::query()->where('tagihan_id', $tagihan->id)->latest('created_at')->first();
        if ($incomeByRelation) {
            $incomeByRelation->delete();

            return 1;
        }

        if ($amount === null || trim($description) === 'Pembayaran paket  dari') {
            return 0;
        }

        $query = Income::query()
            ->whereIn('kategori', ['penjualan', 'Tagihan'])
            ->where('keterangan', $description)
            ->where('jumlah', $amount);

        if ($tagihan->tanggal_pembayaran) {
            $query->whereDate('tanggal_masuk', Carbon::parse($tagihan->tanggal_pembayaran)->toDateString());
        }

        $income = $query->latest('created_at')->first();

        if (! $income && $tagihan->tanggal_pembayaran) {
            $income = Income::query()
                ->whereIn('kategori', ['penjualan', 'Tagihan'])
                ->where('keterangan', $description)
                ->where('jumlah', $amount)
                ->latest('created_at')
                ->first();
        }

        if (! $income) {
            return 0;
        }

        $income->delete();

        return 1;
    }

    private function incomeDescriptionForTagihan(Tagihan $tagihan, ?Paket $paket = null): string
    {
        $paket ??= $tagihan->paket;
        $paketNama = $paket?->nama_paket ?: ($tagihan->nama_paket ?: '-');
        $pelangganNama = $tagihan->pelanggan?->nama_lengkap ?: '-';

        return 'Pembayaran paket ' . $paketNama . ' dari ' . $pelangganNama;
    }

    private function incomePaymentTypeForTagihan(Tagihan $tagihan): string
    {
        $typePembayaran = trim((string) ($tagihan->type_pembayaran ?? ''));
        if ($typePembayaran === '' || strtolower($typePembayaran) === 'cash') {
            return 'cash';
        }

        return $tagihan->rekening?->nama_bank ?: $typePembayaran;
    }

    private function findIncomeForTagihan(Tagihan $tagihan, $oldAmount = null, ?string $oldDescription = null): ?Income
    {
        $income = Income::query()->where('tagihan_id', $tagihan->id)->latest('created_at')->first();
        if ($income) {
            return $income;
        }

        if ($oldAmount === null || trim((string) $oldDescription) === '') {
            return null;
        }

        $query = Income::query()
            ->whereIn('kategori', ['penjualan', 'Tagihan'])
            ->where('keterangan', $oldDescription)
            ->where('jumlah', $oldAmount);

        if ($tagihan->tanggal_pembayaran) {
            $query->whereDate('tanggal_masuk', Carbon::parse($tagihan->tanggal_pembayaran)->toDateString());
        }

        return $query->latest('created_at')->first()
            ?: Income::query()
                ->whereIn('kategori', ['penjualan', 'Tagihan'])
                ->where('keterangan', $oldDescription)
                ->where('jumlah', $oldAmount)
                ->latest('created_at')
                ->first();
    }

    private function syncIncomeForPaidTagihan(Tagihan $tagihan, $oldAmount = null, ?string $oldDescription = null): Income
    {
        $tagihan->loadMissing(['pelanggan', 'paket', 'rekening']);

        $amount = $tagihan->harga ?? $tagihan->paket?->harga ?? 0;
        $description = $this->incomeDescriptionForTagihan($tagihan);
        $income = $this->findIncomeForTagihan($tagihan, $oldAmount, $oldDescription);

        $data = [
            'tagihan_id' => $tagihan->id,
            'kategori' => $income?->kategori ?: 'penjualan',
            'jumlah' => $amount,
            'keterangan' => $description,
            'tipe_pembayaran' => $this->incomePaymentTypeForTagihan($tagihan),
            'tanggal_masuk' => $tagihan->tanggal_pembayaran ?: now(),
        ];

        if ($income) {
            $income->update($data);

            return $income;
        }

        return Income::create($data + [
            'kode' => $this->getKode('penjualan'),
        ]);
    }

    private function resolveBuktiPembayaranUrlByNomorId(?string $buktiPath, ?string $nomorId): string
    {
        $buktiPath = trim((string) $buktiPath);
        if ($buktiPath === '' || $buktiPath === '-') {
            return '';
        }

        if (Str::startsWith($buktiPath, ['http://', 'https://'])) {
            return $buktiPath;
        }

        if (str_starts_with($buktiPath, 'storage/')) {
            $buktiPath = substr($buktiPath, 8);
        }

        $jmkgkPath = rtrim(env('JMKGK_PUBLIC_STORAGE_PATH', '/var/www/billingJMKGK/storage/app/public'), '/');
        $jmkPath = rtrim(env('JMK_PUBLIC_STORAGE_PATH', '/var/www/billingjmk/storage/app/public'), '/');
        $jmkgkUrl = rtrim(env('JMKGK_APP_URL', config('app.url')), '/');
        $jmkUrl = rtrim(env('JMK_APP_URL', config('app.url')), '/');

        $preferJmkgk = Str::startsWith(strtoupper(trim((string) $nomorId)), 'JMK-GK');

        $candidates = $preferJmkgk
            ? [[$jmkgkPath, $jmkgkUrl], [$jmkPath, $jmkUrl]]
            : [[$jmkPath, $jmkUrl], [$jmkgkPath, $jmkgkUrl]];

        foreach ($candidates as [$basePath, $baseUrl]) {
            $absolute = $basePath . '/' . ltrim($buktiPath, '/');
            if (is_file($absolute)) {
                return $baseUrl . '/storage/' . ltrim($buktiPath, '/');
            }
        }

        return asset('storage/' . ltrim($buktiPath, '/'));
    }

    private function eligibleBroadcastCustomersQuery(int $month, int $year)
    {
        return Pelanggan::query()
            // Hard filter: hanya pelanggan dengan status approve (toleran spasi/case)
            ->whereRaw('LOWER(TRIM(status)) = ?', ['approve'])
            ->whereNotNull('paket_id')
            ->whereDoesntHave('tagihans', function ($q) use ($month, $year) {
                $q->whereMonth('tanggal_mulai', $month)
                    ->whereYear('tanggal_mulai', $year);
            });
    }

    private function shouldSendFcmSynchronously(): bool
    {
        return filter_var(env('TAGIHAN_BROADCAST_FCM_SYNC', true), FILTER_VALIDATE_BOOL);
    }

    private function dispatchFcmTagihanPush(array $tagihanIds, ?string $batchId = null, string $notificationType = 'reminder'): string
    {
        if (empty($tagihanIds)) {
            return 'none';
        }

        if ($this->shouldSendFcmSynchronously()) {
            SendFcmTagihanPushJob::dispatchSync($tagihanIds, $batchId, $notificationType);

            return 'sync';
        }

        $queueConnection = (string) config('queue.default', 'database');
        $jobsTable = (string) config('queue.connections.database.table', 'jobs');

        if ($queueConnection === 'database' && !Schema::hasTable($jobsTable)) {
            Log::warning('Tagihan broadcast FCM queue table missing, fallback to sync', [
                'jobs_table' => $jobsTable,
                'tagihan_count' => count($tagihanIds),
                'batch_id' => $batchId,
            ]);

            SendFcmTagihanPushJob::dispatchSync($tagihanIds, $batchId, $notificationType);

            return 'sync_fallback';
        }

        SendFcmTagihanPushJob::dispatch($tagihanIds, $batchId, $notificationType);

        return 'queue';
    }

    private function dispatchSinglePush(array $notification): string
    {
        if (($notification['provider'] ?? null) === 'fcm' && $this->shouldSendFcmSynchronously()) {
            SendFcmPushJob::dispatchSync(
                $notification['pelanggan_id'],
                $notification['title'],
                $notification['message'],
                $notification['target_url']
            );

            return 'sync';
        }

        SendFcmPushJob::dispatch(
            $notification['pelanggan_id'],
            $notification['title'],
            $notification['message'],
            $notification['target_url']
        );

        return 'queue';
    }

    private function dispatchCustomerFcmPush(Pelanggan $pelanggan, string $title, string $message, string $targetUrl): string
    {
        if (trim((string) ($pelanggan->fcm_token ?? '')) === '') {
            return 'none';
        }

        if ($this->shouldSendFcmSynchronously()) {
            SendFcmPushJob::dispatchSync((string) $pelanggan->id, $title, $message, $targetUrl);

            return 'sync';
        }

        $queueConnection = (string) config('queue.default', 'database');
        $jobsTable = (string) config('queue.connections.database.table', 'jobs');

        if ($queueConnection === 'database' && !Schema::hasTable($jobsTable)) {
            Log::warning('Customer FCM queue table missing, fallback to sync', [
                'jobs_table' => $jobsTable,
                'pelanggan_id' => $pelanggan->id,
            ]);

            SendFcmPushJob::dispatchSync((string) $pelanggan->id, $title, $message, $targetUrl);

            return 'sync_fallback';
        }

        SendFcmPushJob::dispatch((string) $pelanggan->id, $title, $message, $targetUrl);

        return 'queue';
    }

    /**
     * Update paket tagihan (untuk mengubah nominal jika tidak sesuai)
     * dan tanggal mulai/berakhir
     */
    public function updatePaket(Request $request, $id)
    {
        $request->validate([
            'paket_id' => 'required|exists:pakets,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        DB::beginTransaction();
        try {
            $tagihan = Tagihan::with('pelanggan', 'paket')->findOrFail($id);
            $paket = Paket::findOrFail($request->paket_id);

            // Store old values for logging
            $oldPaketId = $tagihan->paket_id;
            $oldTanggalMulai = $tagihan->tanggal_mulai;
            $oldTanggalBerakhir = $tagihan->tanggal_berakhir;

            // Update paket_id, harga, dan tanggal
            $tagihan->paket_id = $paket->id;
            $tagihan->harga = $paket->harga;
            $tagihan->tanggal_mulai = $request->tanggal_mulai;
            $tagihan->tanggal_berakhir = $request->tanggal_berakhir;
            $tagihan->save();

            // Log perubahan
            Log::info('Tagihan updated', [
                'tagihan_id' => $id,
                'old_paket_id' => $oldPaketId,
                'new_paket_id' => $paket->id,
                'new_harga' => $paket->harga,
                'old_tanggal_mulai' => $oldTanggalMulai,
                'new_tanggal_mulai' => $request->tanggal_mulai,
                'old_tanggal_berakhir' => $oldTanggalBerakhir,
                'new_tanggal_berakhir' => $request->tanggal_berakhir,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil diperbarui.',
                'data' => [
                    'paket_nama' => $paket->nama_paket,
                    'harga' => $paket->harga,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_berakhir' => $request->tanggal_berakhir,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating tagihan', [
                'tagihan_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // get data json
    public function indexGetJson()
    {
        // Ambil semua pelanggan & paket untuk dropdown modal
        $pelanggan = Pelanggan::all();
        $paket = Paket::all();

        // Ambil semua tagihan dengan status "belum bayar" beserta relasinya
        $tagihans = Tagihan::with(['pelanggan', 'paket'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $pelanggan = $item->pelanggan;
                $paket = $item->paket;

                return [
                    'id' => $item->id,
                    'nomer_id' => $pelanggan->nomer_id ?? '-',
                    'nama_lengkap' => $pelanggan->nama_lengkap ?? '-',
                    'alamat_jalan' => $pelanggan->alamat_jalan ?? '-',
                    'rt' => $pelanggan->rt ?? '-',
                    'rw' => $pelanggan->rw ?? '-',
                    'desa' => $pelanggan->desa ?? '-',
                    'kecamatan' => $pelanggan->kecamatan ?? '-',
                    'kabupaten' => $pelanggan->kabupaten ?? '-',
                    'provinsi' => $pelanggan->provinsi ?? '-',
                    'kode_pos' => $pelanggan->kode_pos ?? '-',

                    'paket' => [
                        'id' => $paket->id ?? null,
                        'nama_paket' => $paket->nama_paket ?? '-',
                        'harga' => $paket->harga ?? 0,
                        'kecepatan' => $paket->kecepatan ?? 0,
                        'masa_pembayaran' => $paket->masa_pembayaran ?? 0,
                        'durasi' => $paket->durasi ?? 0,
                    ],

                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_berakhir' => $item->tanggal_berakhir,
                    'status_pembayaran' => $item->status_pembayaran,
                    'tanggal_pembayaran' => $item->tanggal_pembayaran ?? '-',
                    'bukti_pembayaran' => $item->bukti_pembayaran ?? '-',
                    'no_whatsapp' => $pelanggan->no_whatsapp ?? '08xxxxxxxxxx',
                    'catatan' => $item->catatan ?? '-',
                ];
            });

        // Ambil list unik untuk dropdown
        $kabupatenList = $pelanggan->pluck('kabupaten')->unique()->values();
        $kecamatanList = $pelanggan->pluck('kecamatan')->unique()->values();

        // Statistik
        $totalCustomer = $pelanggan->count();
        $lunas = 0;
        $belumLunas = $tagihans->count();
        $totalPaket = $paket->count();

        return response()->json([
            'status' => true,
            'message' => 'Data tagihan berhasil diambil.',
            'data' => [
                'tagihans' => $tagihans,
                'pelanggan' => $pelanggan,
                'paket' => $paket,
                'statistics' => [
                    'total_customer' => $totalCustomer,
                    'lunas' => $lunas,
                    'belum_lunas' => $belumLunas,
                    'total_paket' => $totalPaket,
                ],
                'filters' => [
                    'kabupaten' => $kabupatenList,
                    'kecamatan' => $kecamatanList,
                ],
            ],
        ]);
    }

    public function getByIdJson($id)
    {
        // Ambil data tagihan berdasarkan ID + relasi pelanggan & paket
        $item = Tagihan::with(['pelanggan', 'paket'])->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Tagihan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $pelanggan = $item->pelanggan;
        $paket = $item->paket;

        // Bentuk JSON detail (sama dengan indexGetJson)
        $tagihanDetail = [
            'id' => $item->id,
            'nomer_id' => $pelanggan->nomer_id ?? '-',
            'nama_lengkap' => $pelanggan->nama_lengkap ?? '-',
            'alamat_jalan' => $pelanggan->alamat_jalan ?? '-',
            'rt' => $pelanggan->rt ?? '-',
            'rw' => $pelanggan->rw ?? '-',
            'desa' => $pelanggan->desa ?? '-',
            'kecamatan' => $pelanggan->kecamatan ?? '-',
            'kabupaten' => $pelanggan->kabupaten ?? '-',
            'provinsi' => $pelanggan->provinsi ?? '-',
            'kode_pos' => $pelanggan->kode_pos ?? '-',

            'paket' => [
                'id' => $paket->id ?? null,
                'nama_paket' => $paket->nama_paket ?? '-',
                'harga' => $paket->harga ?? 0,
                'kecepatan' => $paket->kecepatan ?? 0,
                'masa_pembayaran' => $paket->masa_pembayaran ?? 0,
                'durasi' => $paket->durasi ?? 0,
            ],

            'tanggal_mulai' => $item->tanggal_mulai,
            'tanggal_berakhir' => $item->tanggal_berakhir,
            'status_pembayaran' => $item->status_pembayaran,
            'tanggal_pembayaran' => $item->tanggal_pembayaran ?? '-',
            'bukti_pembayaran' => $this->resolveBuktiPembayaranUrlByNomorId($item->bukti_pembayaran, $pelanggan->nomer_id ?? null),
            'no_whatsapp' => $pelanggan->no_whatsapp ?? '08xxxxxxxxxx',
            'catatan' => $item->catatan ?? '-',
        ];

        return response()->json([
            'status' => true,
            'message' => 'Detail tagihan berhasil diambil.',
            'data' => $tagihanDetail,
        ]);
    }




    public function konfirmasiBayar(Request $request, $id)
    {
        $tagihan = Tagihan::with('pelanggan', 'paket')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Upload bukti pembayaran (opsional)
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $path = $this->storeBuktiPembayaranByNomorId($file, $tagihan->pelanggan->nomer_id ?? null);
                $tagihan->bukti_pembayaran = $path;
            }

            // Simpan tipe pembayaran jika dikirim dari admin
            if ($request->filled('type_pembayaran')) {
                $typePembayaran = $request->input('type_pembayaran');
                // Jika "cash" atau kosong â†’ null (Cash/Tunai), selain itu simpan UUID rekening apa adanya
                $tagihan->type_pembayaran = ($typePembayaran === 'cash' || empty($typePembayaran))
                    ? null
                    : $typePembayaran;
            }

            // Update status tagihan menjadi lunas
            $tagihan->status_pembayaran = 'lunas';
            $tagihan->tanggal_pembayaran = now();

            // Generate PDF kwitansi
            $pdf = Pdf::loadView('content.apps.pdf.kwitansi', ['tagihan' => $tagihan]);
            $filename = 'kwitansi-' . $tagihan->id . '.pdf';
            $pdfPath = 'kwitansi/' . $filename;
            Storage::disk('public')->put($pdfPath, $pdf->output());

            // Simpan path PDF ke field kwitansi
            $tagihan->kwitansi = $pdfPath;
            $tagihan->save();

            // Buat link publik PDF
            $pdfUrl = asset('storage/' . $pdfPath);

            $tagihan->load(['pelanggan', 'paket', 'rekening']);
            $this->syncIncomeForPaidTagihan($tagihan);

            $pelanggan = $tagihan->pelanggan;
            if ($pelanggan) {
                $this->dispatchCustomerFcmPush(
                    $pelanggan,
                    'Pembayaran Berhasil',
                    "Terima kasih, {$pelanggan->nama_lengkap}. Pembayaran Anda telah kami terima dan dikonfirmasi.",
                    url('/dashboard/customer/tagihan/selesai')
                );
            }

            // ===== Mikrotik Restore Logic (optional) =====
            if (class_exists(\App\Models\Router::class) && class_exists(\App\Services\MikrotikService::class)) {
                try {
                    $routers = \App\Models\Router::all();
                    $service = new \App\Services\MikrotikService();
                    foreach ($routers as $router) {
                        try {
                            // Coba connect ke setiap router (karena kita belum tau user ada di mana)
                            if ($service->connect($router)) {
                                $username = $tagihan->pelanggan->nomer_id; // Asumsi nomer_id = PPPoE user
                                $profile = $tagihan->paket->mikrotik_profile;

                                if ($username && $profile) {
                                    // Restore profile asli
                                    $service->restoreCustomer($username, $profile);
                                }
                            }
                        } catch (\Exception $e) {
                            // Log error tapi jangan gagalkan transaksi pembayaran
                            Log::error('Mikrotik Restore Failed for Router ' . $router->name . ': ' . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Mikrotik Service Error: ' . $e->getMessage());
                }
            } else {
                Log::warning('Mikrotik Restore skipped: Router model or service missing');
            }
            // =============================================

            DB::commit();

            return response()->json([
                'success' => true,
                'pdfUrl' => $pdfUrl,
                'message' => 'Pembayaran berhasil dikonfirmasi dan notifikasi terkirim!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }


    }




    /**
     * Contoh fungsi helper untuk kirim WA (dummy)
     */
    private function sendWA($nomor, $pesan)
    {
        // TODO: implementasi request ke API WhatsApp
        // return true jika berhasil, false jika gagal
        return true;
    }


    public function index(Request $request)
    {
        // Ambil semua pelanggan & paket untuk dropdown modal
        // ? MENGHAPUS QUERY $pelanggan YANG MENGAMBIL SEMUA DATA
        // Karena di view tagihan.blade.php sudah menggunakan AJAX select2, query ini hanya membuang memori dan waktu.

        $paket = Paket::all();

        // ? BUILD QUERY OPTIMIZATION - MENGGUNAKAN JOIN (LEBIH CEPAT DARI WHEREHAS)
        $query = Tagihan::with(['pelanggan', 'paket'])
            ->select('tagihans.*') // Wajib select tagihans.* agar id tagihan tidak tertimpa id pelanggan
            ->join('pelanggans', 'tagihans.pelanggan_id', '=', 'pelanggans.id')
            ->where('tagihans.status_pembayaran', 'belum bayar');

        // ? SEARCH FILTER - OPTIMALKAN DENGAN PELANGGANS. PREFIX
        if ($request->filled('search')) {
            $search = trim($request->search);
            $normalizedSearch = preg_replace('/[\s.\-\/+]+/', '', strtolower($search));
            $isCustomerIdSearch = preg_match('/^[a-z]+(?:[.\-\s]*[a-z]+)*[.\-\s]*\d+$/i', $search);

            $query->where(function ($q) use ($search, $normalizedSearch, $isCustomerIdSearch) {
                if ($isCustomerIdSearch) {
                    $q->whereRaw('LOWER(TRIM(pelanggans.nomer_id)) = ?', [strtolower($search)])
                        ->orWhereRaw(
                            "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(pelanggans.nomer_id, '.', ''), '-', ''), ' ', ''), '/', ''), '+', '')) = ?",
                            [$normalizedSearch]
                        );

                    return;
                }

                $q->where('pelanggans.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('pelanggans.nomer_id', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(pelanggans.nomer_id, '.', ''), '-', ''), ' ', ''), '/', ''), '+', '')) LIKE ?",
                        ["%{$normalizedSearch}%"]
                    )
                    ->orWhere('pelanggans.no_whatsapp', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(pelanggans.no_whatsapp, '.', ''), '-', ''), ' ', ''), '/', ''), '+', '')) LIKE ?",
                        ["%{$normalizedSearch}%"]
                    )
                    ->orWhere('pelanggans.no_telp', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(pelanggans.no_telp, '.', ''), '-', ''), ' ', ''), '/', ''), '+', '')) LIKE ?",
                        ["%{$normalizedSearch}%"]
                    )
                    ->orWhere('pelanggans.alamat_jalan', 'like', "%{$search}%")
                    ->orWhere('pelanggans.rt', 'like', "%{$search}%")
                    ->orWhere('pelanggans.rw', 'like', "%{$search}%")
                    ->orWhere('pelanggans.desa', 'like', "%{$search}%")
                    ->orWhere('pelanggans.kecamatan', 'like', "%{$search}%")
                    ->orWhere('pelanggans.kabupaten', 'like', "%{$search}%")
                    ->orWhere('pelanggans.kode_pos', 'like', "%{$search}%");
            });
        }

        // ? FILTER BULAN & TAHUN
        if ($request->filled('periode')) {
            $periode = $request->periode; // format: 2026-01
            $parts = explode('-', $periode);
            if (count($parts) === 2) {
                $tahun = (int) $parts[0];
                $bulan = (int) $parts[1];
                $query->whereYear('tagihans.tanggal_mulai', $tahun)
                    ->whereMonth('tagihans.tanggal_mulai', $bulan);
            }
        }

        // FILTER BIAYA PAKET
        if ($request->filled('harga_paket')) {
            $hargaPaket = (int) preg_replace('/[^\d]/', '', (string) $request->input('harga_paket'));
            $query->whereHas('paket', function ($q) use ($hargaPaket) {
                $q->where('harga', $hargaPaket);
            });
        }

        // ? PAGINATION
        $tagihans = $query
            ->orderBy('tagihans.created_at', 'desc')
            ->paginate(40, ['tagihans.*'])
            ->withQueryString()
            ->through(function ($item) {
                $pelanggan = $item->pelanggan;
                $paket = $item->paket;

                return [
                    'id' => $item->id,
                    'pelanggan_id' => $item->pelanggan_id,
                    'nomer_id' => $pelanggan->nomer_id ?? '-',
                    'nama_lengkap' => $pelanggan->nama_lengkap ?? '-',
                    'alamat_jalan' => $pelanggan->alamat_jalan ?? '-',
                    'rt' => $pelanggan->rt ?? '-',
                    'rw' => $pelanggan->rw ?? '-',
                    'desa' => $pelanggan->desa ?? '-',
                    'kecamatan' => $pelanggan->kecamatan ?? '-',
                    'kabupaten' => $pelanggan->kabupaten ?? '-',
                    'provinsi' => $pelanggan->provinsi ?? '-',
                    'kode_pos' => $pelanggan->kode_pos ?? '-',
                    'paket' => [
                        'id' => $paket->id ?? null,
                        'nama_paket' => $paket->nama_paket ?? '-',
                        'harga' => $paket->harga ?? 0,
                        'kecepatan' => $paket->kecepatan ?? 0,
                        'masa_pembayaran' => $paket->masa_pembayaran ?? 0,
                        'durasi' => $paket->durasi ?? 0,
                    ],
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_berakhir' => $item->tanggal_berakhir,
                    'status_pembayaran' => $item->status_pembayaran ?? 'belum bayar',
                    'tanggal_pembayaran' => $item->tanggal_pembayaran ?? '-',
                    'bukti_pembayaran' => $this->resolveBuktiPembayaranUrlByNomorId($item->bukti_pembayaran, $pelanggan->nomer_id ?? null),
                    'no_whatsapp' => $pelanggan->no_whatsapp ?? '08xxxxxxxxxx',
                    'catatan' => $item->catatan ?? '-',
                ];
            });

        // ? List kabupaten & kecamatan juga dihapus (tidak perlu lagi)
        // $kabupatenList = ...
        // $kecamatanList = ...

        // Statistik
        $totalCustomer = Pelanggan::where('status', 'approve')->count();
        // Gunakan total tagihan lunas (bukan distinct pelanggan) agar angka konsisten dengan daftar
        $customerLunas = Tagihan::where('status_pembayaran', 'lunas')->count();
        $lunas = $customerLunas; // Jumlah tagihan lunas
        $belumLunas = Tagihan::whereIn('status_pembayaran', ['belum bayar', 'proses_verifikasi'])->count();
        $totalPaket = $paket->count();

        // Rekening list untuk dropdown verifikasi
        $rekeningList = \App\Models\Rekening::select('id', 'nama_bank')->get();

        // ? RETURN VIEW HTML (tanpa kabupatenList & kecamatanList)
        return view('content.apps.Tagihan.tagihan', [
            'tagihans' => $tagihans,
            'pelanggan' => [], // Kirim array kosong karena tidak dipakai lagi di view
            'paket' => $paket,
            'totalCustomer' => $totalCustomer,
            'customerLunas' => $customerLunas,
            'lunas' => $lunas,
            'belumLunas' => $belumLunas,
            'totalPaket' => $totalPaket,
            'rekeningList' => $rekeningList,
        ]);
    }



    public function proses(Request $request)
    {
        // Ambil semua pelanggan & paket untuk dropdown modal
        $pelanggan = Pelanggan::all();
        $paket = Paket::all();

        // Query builder dengan search
        $query = Tagihan::with(['pelanggan', 'paket', 'rekening:id,nama_bank'])
            ->where('status_pembayaran', 'proses_verifikasi');

        // Tambahkan filter search jika ada parameter
        if ($search = $request->input('search')) {
            $search = trim($search);
            $normalizedSearch = preg_replace('/[\s.\-\/+]+/', '', strtolower($search));
            $isCustomerIdSearch = preg_match('/^[a-z]+(?:[.\-\s]*[a-z]+)*[.\-\s]*\d+$/i', $search);

            $query->where(function ($q) use ($search, $normalizedSearch, $isCustomerIdSearch) {
                $q->whereHas('pelanggan', function ($subQ) use ($search, $normalizedSearch, $isCustomerIdSearch) {
                    if ($isCustomerIdSearch) {
                        $subQ->whereRaw('LOWER(TRIM(nomer_id)) = ?', [strtolower($search)])
                            ->orWhereRaw(
                                "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nomer_id, '.', ''), '-', ''), ' ', ''), '/', ''), '+', '')) = ?",
                                [$normalizedSearch]
                            );

                        return;
                    }

                    $subQ->where('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('nomer_id', 'LIKE', "%{$search}%")
                        ->orWhere('no_whatsapp', 'LIKE', "%{$search}%")
                        ->orWhere('alamat_jalan', 'LIKE', "%{$search}%")
                        ->orWhere('desa', 'LIKE', "%{$search}%")
                        ->orWhere('kecamatan', 'LIKE', "%{$search}%")
                        ->orWhere('kabupaten', 'LIKE', "%{$search}%");
                })
                    ->when(! $isCustomerIdSearch, function ($query) use ($search) {
                        $query->orWhereHas('paket', function ($subQ) use ($search) {
                        $subQ->where('nama_paket', 'LIKE', "%{$search}%");
                        });
                    });
            });
        }

        // Pagination dengan withQueryString untuk mempertahankan parameter search
        $tagihans = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $tagihans->getCollection()->transform(function ($item) {
            $item->bukti_pembayaran_resolved = $this->resolveBuktiPembayaranUrlByNomorId(
                $item->bukti_pembayaran,
                optional($item->pelanggan)->nomer_id
            );
            return $item;
        });

        // Ambil list unik untuk filter dropdown
        $kabupatenList = $pelanggan->pluck('kabupaten')->unique();
        $kecamatanList = $pelanggan->pluck('kecamatan')->unique();

        // Statistik
        $totalCustomer = $pelanggan->count();
        $lunas = 0;
        $belumLunas = Tagihan::where('status_pembayaran', 'proses_verifikasi')->count();
        $totalPaket = $paket->count();

        return view('content.apps.Tagihan.proses-tagihan', compact(
            'tagihans',
            'pelanggan',
            'paket',
            'totalCustomer',
            'lunas',
            'belumLunas',
            'totalPaket',
            'kabupatenList',
            'kecamatanList'
        ));
    }

    /**
     * Update status tagihan dari proses_verifikasi kembali ke belum bayar
     * dan hapus bukti pembayaran yang salah
     */
    public function updateStatusToBelumBayar(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Catatan penolakan wajib diisi.',
            'alasan_penolakan.min' => 'Catatan penolakan minimal 5 karakter.',
            'alasan_penolakan.max' => 'Catatan penolakan maksimal 1000 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $tagihan = Tagihan::with('pelanggan', 'paket')->findOrFail($id);

            // Validasi: hanya bisa update jika statusnya proses_verifikasi
            if ($tagihan->status_pembayaran !== 'proses_verifikasi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya tagihan dengan status "Proses Verifikasi" yang bisa diubah ke "Belum Bayar".',
                ], 400);
            }

            // Hapus bukti pembayaran jika ada
            $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $tagihan->pelanggan->nomer_id ?? null);

            // Update status ke belum bayar dan hapus bukti pembayaran
            $tagihan->status_pembayaran = 'belum bayar';
            $tagihan->bukti_pembayaran = null;
            $tagihan->tanggal_pembayaran = null;
            $tagihan->alasan_penolakan = trim($validated['alasan_penolakan']);
            $tagihan->ditolak_at = now();
            $tagihan->save();

            $pelanggan = $tagihan->pelanggan;
            $notificationStatus = 'none';
            if ($pelanggan) {
                $notificationStatus = $this->dispatchCustomerFcmPush(
                    $pelanggan,
                    'Pembayaran Ditolak',
                    "Tagihan ditolak Yth. {$pelanggan->nama_lengkap}. Alasan: {$tagihan->alasan_penolakan}",
                    url('/dashboard/customer/tagihan')
                );
            }

            // ===== Mikrotik Isolate Logic (optional) =====
            if (class_exists(\App\Models\Router::class) && class_exists(\App\Services\MikrotikService::class)) {
                try {
                    $routers = \App\Models\Router::all();
                    $service = new \App\Services\MikrotikService();
                    foreach ($routers as $router) {
                        try {
                            if ($service->connect($router)) {
                                $username = $tagihan->pelanggan->nomer_id;

                                if ($username) {
                                    // Set profile ke 'isolir' (pastikan profile ini ada di Mikrotik)
                                    $service->isolateCustomer($username, 'isolir');
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Mikrotik Isolate Failed for Router ' . $router->name . ': ' . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Mikrotik Service Error: ' . $e->getMessage());
                }
            } else {
                Log::warning('Mikrotik Isolate skipped: Router model or service missing');
            }
            // =============================================

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak dan alasan penolakan telah dikirim ke pelanggan.',
                'notification_status' => $notificationStatus,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating tagihan status to belum bayar', [
                'tagihan_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function rejectLunas($id)
    {
        DB::beginTransaction();

        try {
            $tagihan = Tagihan::with('pelanggan', 'paket')->findOrFail($id);

            if ($tagihan->status_pembayaran !== 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya tagihan dengan status lunas yang bisa ditolak.',
                ], 400);
            }

            $deletedIncomeCount = $this->deleteIncomeForTagihan($tagihan);

            $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $tagihan->pelanggan->nomer_id ?? null);
            $this->deleteKwitansi($tagihan->kwitansi);

            $tagihan->status_pembayaran = 'belum bayar';
            $tagihan->bukti_pembayaran = null;
            $tagihan->kwitansi = null;
            $tagihan->tanggal_pembayaran = null;
            $tagihan->save();

            $pelanggan = $tagihan->pelanggan;
            if ($pelanggan) {
                $this->dispatchCustomerFcmPush(
                    $pelanggan,
                    'Pembayaran Dibatalkan',
                    "Tagihan dibatalkan Yth. {$pelanggan->nama_lengkap}. Status tagihan dikembalikan menjadi belum bayar.",
                    url('/dashboard/customer/tagihan')
                );
            }

            DB::commit();

            $message = $deletedIncomeCount > 0
                ? 'Tagihan lunas berhasil ditolak, status dikembalikan ke belum bayar, dan data administrasi masuk sudah ditarik.'
                : 'Tagihan lunas berhasil ditolak, status dikembalikan ke belum bayar. Data administrasi masuk terkait tidak ditemukan.';

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error rejecting paid tagihan', [
                'tagihan_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function lunas(Request $request)
    {
        // Eager load hanya kolom yang diperlukan
        $query = Tagihan::with([
            'pelanggan:id,nama_lengkap,nomer_id,no_whatsapp,alamat_jalan,rt,rw,desa,kecamatan,kabupaten,provinsi,kode_pos',
            'paket:id,nama_paket,harga,kecepatan,masa_pembayaran',
            'rekening:id,nama_bank'
        ])->where('status_pembayaran', 'lunas');

        // Filter search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($subQ) use ($search) {
                    $subQ->where('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('nomer_id', 'LIKE', "%{$search}%")
                        ->orWhere('no_whatsapp', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filter periode (bulan dan tahun)
        if ($bulan = $request->input('bulan')) {
            $query->whereMonth('tanggal_mulai', $bulan);
        }

        if ($tahun = $request->input('tahun')) {
            $query->whereYear('tanggal_mulai', $tahun);
        }

        // Filter tanggal range (berdasarkan tanggal_pembayaran / tanggal dia bayar)
        if ($tanggalDari = $request->input('tanggal_dari')) {
            $query->whereDate('tanggal_pembayaran', '>=', $tanggalDari);
        }
        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $query->whereDate('tanggal_pembayaran', '<=', $tanggalSampai);
        }

        // Filter Bank
        if ($bankId = $request->input('bank')) {
            $query->where('type_pembayaran', $bankId);
        }

        // PAGINATION - langsung return model tanpa through() untuk kecepatan
        $tagihans = $query->orderBy('created_at', 'desc')
            ->paginate(40)
            ->withQueryString();

        $tagihans->getCollection()->transform(function ($item) {
            $item->bukti_pembayaran_resolved = $this->resolveBuktiPembayaranUrlByNomorId(
                $item->bukti_pembayaran,
                optional($item->pelanggan)->nomer_id
            );
            return $item;
        });

        // Bank totals - query sederhana dengan filter yang sama
        $bankTotalsQuery = Tagihan::leftJoin('rekenings', 'rekenings.id', '=', 'tagihans.type_pembayaran')
            ->leftJoin('pakets', 'pakets.id', '=', 'tagihans.paket_id')
            ->where('tagihans.status_pembayaran', 'lunas');

        // Apply same filters untuk bankTotals
        if ($bulan = $request->input('bulan')) {
            $bankTotalsQuery->whereMonth('tagihans.tanggal_mulai', $bulan);
        }
        if ($tahun = $request->input('tahun')) {
            $bankTotalsQuery->whereYear('tagihans.tanggal_mulai', $tahun);
        }
        if ($tanggalDari = $request->input('tanggal_dari')) {
            $bankTotalsQuery->whereDate('tagihans.tanggal_pembayaran', '>=', $tanggalDari);
        }
        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $bankTotalsQuery->whereDate('tagihans.tanggal_pembayaran', '<=', $tanggalSampai);
        }
        if ($bankId = $request->input('bank')) {
            $bankTotalsQuery->where('tagihans.type_pembayaran', $bankId);
        }

        $bankTotals = $bankTotalsQuery
            ->selectRaw('COALESCE(rekenings.nama_bank, tagihans.type_pembayaran, "Lainnya") as nama_bank, SUM(COALESCE(tagihans.harga, pakets.harga, 0)) as total')
            ->groupByRaw('COALESCE(rekenings.nama_bank, tagihans.type_pembayaran, "Lainnya")')
            ->orderByDesc('total')
            ->get();

        // Ambil list bank dan paket untuk filter/dropdown edit
        $rekeningList = Rekening::select('id', 'nama_bank')->get();
        $paketList = Paket::select('id', 'nama_paket', 'harga', 'kecepatan', 'masa_pembayaran')->orderBy('nama_paket')->get();

        return view('content.apps.Tagihan.tagihan-lunas', compact(
            'tagihans',
            'rekeningList',
            'bankTotals',
            'paketList'
        ));
    }

    public function updateLunas(Request $request, $id)
    {
        $validated = $request->validate([
            'paket_id' => ['required', 'exists:pakets,id'],
            'type_pembayaran' => ['required', 'string'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ]);

        DB::beginTransaction();

        try {
            $tagihan = Tagihan::with(['pelanggan', 'paket', 'rekening'])->findOrFail($id);

            if ($tagihan->status_pembayaran !== 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya tagihan dengan status lunas yang bisa diedit dari halaman ini.',
                ], 400);
            }

            $typePembayaran = $validated['type_pembayaran'];
            if ($typePembayaran !== 'cash' && ! Rekening::whereKey($typePembayaran)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode pembayaran tidak valid.',
                ], 422);
            }

            $oldAmount = $tagihan->harga ?? $tagihan->paket?->harga;
            $oldDescription = $this->incomeDescriptionForTagihan($tagihan);
            $paket = Paket::findOrFail($validated['paket_id']);

            if ($request->hasFile('bukti_pembayaran')) {
                $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $tagihan->pelanggan->nomer_id ?? null);
                $tagihan->bukti_pembayaran = $this->storeBuktiPembayaranByNomorId(
                    $request->file('bukti_pembayaran'),
                    $tagihan->pelanggan->nomer_id ?? null
                );
            }

            $tagihan->paket_id = $paket->id;
            $tagihan->nama_paket = $paket->nama_paket;
            $tagihan->harga = $paket->harga;
            $tagihan->kecepatan = $paket->kecepatan;
            $tagihan->masa_pembayaran = $paket->masa_pembayaran;
            $tagihan->type_pembayaran = $typePembayaran === 'cash' ? null : $typePembayaran;

            if (! $tagihan->tanggal_pembayaran) {
                $tagihan->tanggal_pembayaran = now();
            }

            $this->deleteKwitansi($tagihan->kwitansi);
            $tagihan->kwitansi = null;
            $tagihan->save();
            $tagihan->load(['pelanggan', 'paket', 'rekening']);

            $pdf = Pdf::loadView('content.apps.pdf.kwitansi', ['tagihan' => $tagihan]);
            $pdfPath = 'kwitansi/kwitansi-' . $tagihan->id . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdf->output());
            $tagihan->kwitansi = $pdfPath;
            $tagihan->save();

            $income = $this->syncIncomeForPaidTagihan($tagihan, $oldAmount, $oldDescription);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tagihan lunas berhasil diperbarui. Data administrasi masuk juga sudah disesuaikan.',
                'data' => [
                    'paket_id' => $paket->id,
                    'paket_nama' => $paket->nama_paket,
                    'harga' => $paket->harga,
                    'harga_formatted' => 'Rp ' . number_format($paket->harga ?? 0, 0, ',', '.'),
                    'kecepatan' => ($paket->kecepatan ?? '-') . ' Mbps',
                    'type_pembayaran' => $this->incomePaymentTypeForTagihan($tagihan),
                    'bukti_url' => $this->resolveBuktiPembayaranUrlByNomorId($tagihan->bukti_pembayaran, $tagihan->pelanggan->nomer_id ?? null),
                    'kwitansi_url' => asset('storage/' . $tagihan->kwitansi),
                    'income_id' => $income->id,
                    'income_jumlah' => $income->jumlah,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating paid tagihan', [
                'tagihan_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function searchPelanggan(Request $request)
    {
        $term = trim((string) $request->q);
        $normalizedTerm = preg_replace('/[\s.\-\/]+/', '', strtolower($term));
        $filterNoTagihan = (int) $request->input('filter_no_tagihan', 0) === 1;
        $filterApprove = (int) $request->input('filter_approve', 0) === 1;

        $query = Pelanggan::with('paket');

        if ($filterApprove || $filterNoTagihan) {
            $query->whereRaw('LOWER(TRIM(status)) = ?', ['approve']);
        }

        if ($filterNoTagihan) {
            $periodDate = $request->filled('tanggal_mulai')
                ? Carbon::parse($request->tanggal_mulai)
                : now();
            $periodMonth = $periodDate->month;
            $periodYear = $periodDate->year;
            $query->whereDoesntHave('tagihans', function ($q) use ($periodMonth, $periodYear) {
                $q->whereMonth('tanggal_mulai', $periodMonth)
                    ->whereYear('tanggal_mulai', $periodYear);
            });
        }

        if ($term) {
            $query->where(function ($q) use ($term, $normalizedTerm) {
                $q->where('nama_lengkap', 'LIKE', "%{$term}%")
                    ->orWhere('nomer_id', 'LIKE', "%{$term}%")
                    ->orWhereRaw(
                        "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(nomer_id, '.', ''), '-', ''), ' ', ''), '/', '')) LIKE ?",
                        ["%{$normalizedTerm}%"]
                    )
                    ->orWhere('no_whatsapp', 'LIKE', "%{$term}%")
                    ->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(no_whatsapp, '.', ''), '-', ''), ' ', ''), '/', '') LIKE ?",
                        ["%{$normalizedTerm}%"]
                    );
            });
        }

        $pelanggans = $query->paginate(20);

        $results = [];
        foreach ($pelanggans as $p) {
            $results[] = [
                'id' => $p->id,
                'text' => $p->nomer_id . ' - ' . $p->nama_lengkap,
                'nama' => $p->nama_lengkap, // Untuk data-attribute
                'nomorid' => $p->nomer_id,
                'nowhatsapp' => $p->no_whatsapp,
                'alamat_jalan' => $p->alamat_jalan,
                'rt' => $p->rt,
                'rw' => $p->rw,
                'desa' => $p->desa,
                'kecamatan' => $p->kecamatan,
                'kabupaten' => $p->kabupaten,
                'provinsi' => $p->provinsi,
                'kode_pos' => $p->kode_pos,
                'paket' => $p->paket->nama_paket ?? '-',
                'paket_id' => $p->paket_id,
                'harga' => $p->paket->harga ?? 0,
                'masa' => $p->paket->masa_pembayaran ?? 0,
                'kecepatan' => $p->paket->kecepatan ?? 0,
            ];
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $pelanggans->hasMorePages()
            ]
        ]);
    }







    /**
     * Update data tagihan
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date',
            'catatan' => 'nullable|string',
            'paket_id' => 'required|exists:pakets,id',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'kwitansi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $tagihan = Tagihan::with('pelanggan')->findOrFail($id);
        $paket = Paket::findOrFail($request->paket_id);

        // Parse tanggal
        $tanggalMulai = \Carbon\Carbon::parse($request->tanggal_mulai);
        $tanggalBerakhir = $request->tanggal_berakhir
            ? \Carbon\Carbon::parse($request->tanggal_berakhir)
            : $tanggalMulai->copy()->addDays($paket->masa_pembayaran);

        // Handle bukti_pembayaran
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file lama jika ada
            $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $tagihan->pelanggan->nomer_id ?? null);

            // Simpan file baru
            $tagihan->bukti_pembayaran = $this->storeBuktiPembayaranByNomorId(
                $request->file('bukti_pembayaran'),
                $tagihan->pelanggan->nomer_id ?? null
            );
        }

        // Handle kwitansi jika ada
        if ($request->hasFile('kwitansi')) {
            if ($tagihan->kwitansi && Storage::disk('public')->exists($tagihan->kwitansi)) {
                Storage::disk('public')->delete($tagihan->kwitansi);
            }

            $tagihan->kwitansi = $request->file('kwitansi')
                ->store('kwitansi', 'public');
        }

        // Update field lainnya
        $tagihan->update([
            'paket_id' => $request->paket_id,
            'harga' => $paket->harga,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_berakhir' => $tanggalBerakhir->format('Y-m-d'),
            'catatan' => $request->catatan,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil diperbarui!',
                'data' => [
                    'id' => $tagihan->id,
                    'paket_id' => $paket->id,
                    'paket_nama' => $paket->nama_paket,
                    'kecepatan' => $paket->kecepatan,
                    'harga' => $paket->harga,
                    'harga_formatted' => 'Rp ' . number_format($paket->harga, 0, ',', '.'),
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_berakhir' => $tanggalBerakhir->format('Y-m-d'),
                    'tanggal_mulai_formatted' => $tanggalMulai->translatedFormat('d M Y'),
                    'tanggal_berakhir_formatted' => $tanggalBerakhir->translatedFormat('d M Y'),
                    'catatan' => $request->catatan ?? '-',
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Tagihan berhasil diperbarui!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'paket_id' => 'required|exists:pakets,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);
        if (strtolower(trim((string) $pelanggan->status)) !== 'approve') {
            return redirect()->back()->with('error', 'Tagihan hanya bisa dibuat untuk pelanggan dengan status approve.');
        }

        $paket = Paket::findOrFail($request->paket_id);
        $tanggalMulai = \Carbon\Carbon::parse($request->tanggal_mulai);
        $tanggalBerakhir = $request->tanggal_berakhir
            ? \Carbon\Carbon::parse($request->tanggal_berakhir)
            : $tanggalMulai->copy()->addDays($paket->masa_pembayaran);

        $tagihan = Tagihan::create([
            'pelanggan_id' => $request->pelanggan_id,
            'paket_id' => $request->paket_id,
            'harga' => $paket->harga,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_berakhir' => $tanggalBerakhir->format('Y-m-d'),
            'status_pembayaran' => 'belum bayar',
            'catatan' => $request->catatan,
        ]);

        $fcmToken = trim((string) ($pelanggan->fcm_token ?? ''));
        Log::info('Debug push token pelanggan saat create tagihan', [
            'tagihan_id' => $tagihan->id,
            'pelanggan_id' => $pelanggan->id,
            'has_fcm_token' => $fcmToken !== '',
            'fcm_token_preview' => $fcmToken !== '' ? substr($fcmToken, 0, 12) . '...' : null,
            'has_webpushr_sid' => false,
        ]);

        $notificationSent = false;
        $fcmMode = 'none';

        if ($fcmToken !== '') {
            $fcmMode = $this->dispatchFcmTagihanPush([$tagihan->id], null, 'created');
            $notificationSent = true;
        }

        if ($notificationSent) {
            if ($fcmMode === 'sync' || $fcmMode === 'sync_fallback') {
                $message = 'Tagihan berhasil ditambahkan. Notifikasi FCM dikirim langsung.';
            } else {
                $message = 'Tagihan berhasil ditambahkan. Notifikasi diproses di background.';
            }
        } else {
            $message = 'Tagihan berhasil ditambahkan (tanpa notifikasi - token FCM tidak tersedia).';

            Log::info('Tagihan dibuat tanpa notifikasi', [
                'pelanggan_id' => $request->pelanggan_id,
                'reason' => 'FCM token dan Webpushr SID tidak tersedia',
            ]);
        }

        return redirect()->back()->with('success', $message);
    }


    private function sendOneSignalNotification($playerId, $title, $message)
    {
        $content = [
            'en' => $message,
        ];

        $fields = [
            'app_id' => env('ONESIGNAL_APP_ID'),
            'include_player_ids' => [$playerId],
            'headings' => ['en' => $title],
            'contents' => $content,
        ];

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . env('ONESIGNAL_REST_API_KEY'),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Generate kode otomatis per kategori
     */
    private function getKode($kategori)
    {
        return match (strtolower($kategori)) {
            'internet' => '01',
            'penjualan' => '02',
            'piutang' => '03',
            default => 'O4', // DLL atau kategori custom
        };

    }

    // ? Update tagihan
    public function updateStatus($id)
    {
        $tagihan = \App\Models\Tagihan::with('pelanggan', 'paket')->find($id);

        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan.',
            ], 404);
        }

        // Update status tagihan
        $tagihan->status_pembayaran = 'lunas';
        $tagihan->tanggal_pembayaran = now();
        $tagihan->save();

        $tagihan->load(['pelanggan', 'paket', 'rekening']);
        $this->syncIncomeForPaidTagihan($tagihan);

        $pelanggan = $tagihan->pelanggan;
        if ($pelanggan) {
            $this->dispatchCustomerFcmPush(
                $pelanggan,
                'Pembayaran Berhasil',
                "Terima kasih, {$pelanggan->nama_lengkap}. Pembayaran Anda telah kami terima dan dikonfirmasi.",
                url('/dashboard/customer/tagihan/selesai')
            );
        }

        /*
         * DRAFT NOTIFIKASI TUNGGAKAN (MEMO):
         * --------------------------------------------------
         * Selamat Siang! 
         * Anda memiliki 1 tagihan yang belum dibayar
         * ⚠️ Tunggakan 1 Tagihan
         * Sudah lewat jatuh tempo selama 1 bulan
         * 1 Tunggakan
         * 1 Tagihan
         * --------------------------------------------------
         */

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui menjadi lunas dan income tercatat.',
        ]);
    }

    // ? Hapus tagihan
    public function destroy(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();

        // Jika request AJAX, return JSON (supaya bisa hapus baris tanpa reload)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil dihapus!'
            ]);
        }

        return redirect()->back()->with('success', 'Tagihan berhasil dihapus!');
    }

    // Hapus tagihan hanya jika status lunas
    public function destroyLunas($id)
    {
        $tagihan = Tagihan::with('pelanggan')->findOrFail($id);
        if ($tagihan->status_pembayaran !== 'lunas') {
            return redirect()->back()->with('error', 'Tagihan yang dihapus harus berstatus lunas!');
        }

        try {
            $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $tagihan->pelanggan->nomer_id ?? null);
            $this->deleteKwitansi($tagihan->kwitansi);
        } catch (\Exception $e) {
            // Log error tapi tetap lanjut hapus tagihan
            Log::warning('Error menghapus file tagihan lunas', [
                'tagihan_id' => $id,
                'error' => $e->getMessage(),
            ]);
        }

        $tagihan->delete();

        return redirect()->route('tagihan.lunas')->with('success', 'Tagihan lunas berhasil dihapus!');
    }

    /**
     * Get count of eligible customers for broadcast tagihan
     * (semua pelanggan yang belum punya tagihan di bulan ini)
     */
    public function getBroadcastCount(Request $request)
    {
        try {
            $periodDate = $request->filled('tanggal_mulai')
                ? Carbon::parse($request->tanggal_mulai)
                : now();
            $currentMonth = $periodDate->month;
            $currentYear = $periodDate->year;

            $approveCount = Pelanggan::query()
                ->whereRaw("LOWER(TRIM(status)) = 'approve'")
                ->count();
            $eligibleCount = $this->eligibleBroadcastCustomersQuery($currentMonth, $currentYear)->count();
            $pendingStatusCount = Pelanggan::query()
                ->whereRaw("LOWER(TRIM(status)) IN ('pending', 'proses')")
                ->count();

            return response()->json([
                'count' => $eligibleCount,
                'approve_count' => $approveCount,
                'eligible_count' => $eligibleCount,
                'processable_count' => $eligibleCount,
                'pending_status_count' => $pendingStatusCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting broadcast count', ['error' => $e->getMessage()]);
            return response()->json(['count' => 0, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get IDs of eligible customers for broadcast tagihan
     * (semua pelanggan yang belum punya tagihan di bulan ini)
     */
    public function getBroadcastIds(Request $request)
    {
        try {
            $periodDate = $request->filled('tanggal_mulai')
                ? Carbon::parse($request->tanggal_mulai)
                : now();
            $currentMonth = $periodDate->month;
            $currentYear = $periodDate->year;

            $ids = $this->eligibleBroadcastCustomersQuery($currentMonth, $currentYear)
                ->pluck('id')
                ->toArray();

            return response()->json(['ids' => $ids]);
        } catch (\Exception $e) {
            Log::error('Error getting broadcast ids', ['error' => $e->getMessage()]);
            return response()->json(['ids' => [], 'error' => $e->getMessage()], 500);
        }
    }

    public function massStore(Request $request)
    {
        // Extend execution time for batch processing
        set_time_limit(300); // 5 minutes for large batches

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'mode' => 'nullable|in:all,manual,broadcast',
            'pelanggan_ids' => 'required|array|min:1',
            'pelanggan_ids.*' => 'exists:pelanggans,id',
        ]);

        $pelangganIds = $request->pelanggan_ids;
        $isManualMode = $request->input('mode') === 'manual';

        // Ambil pelanggan yang dipilih, filter yang belum ada tagihan pada periode tanggal_mulai
        $periodDate = Carbon::parse($request->tanggal_mulai);
        $periodMonth = $periodDate->month;
        $periodYear = $periodDate->year;

        $pelanggan = $this->eligibleBroadcastCustomersQuery($periodMonth, $periodYear)
            ->whereIn('id', $pelangganIds)
            ->with('paket')
            ->get();

        if ($pelanggan->isEmpty()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'processed' => 0,
                    'failed' => count($pelangganIds),
                    'message' => 'Tidak ada pelanggan yang bisa dibuatkan tagihan.',
                ]);
            }
            return back()->with('error', 'Tidak ada pelanggan yang bisa dibuatkan tagihan. Mungkin semua sudah memiliki tagihan belum bayar.');
        }

        $successCount = 0;
        $failedCount = 0;
        $fcmTagihanIdsToNotify = [];
        $fcmDeliveryMode = 'none';

        DB::beginTransaction();
        try {
            foreach ($pelanggan as $p) {
                // Defensive guard jika status berubah saat proses berjalan
                if (strtolower(trim((string) $p->status)) !== 'approve') {
                    $failedCount++;
                    continue;
                }

                if (!$p->paket_id || !$p->paket) {
                    $failedCount++;
                    continue;
                }

                $newTagihan = Tagihan::create([
                    'pelanggan_id' => $p->id,
                    'paket_id' => $p->paket_id,
                    'harga' => $p->paket->harga,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_berakhir' => $request->tanggal_berakhir,
                    'status_pembayaran' => 'belum bayar',
                ]);

                $successCount++;

                $fcmToken = trim((string) ($p->fcm_token ?? ''));

                if ($fcmToken !== '') {
                    $fcmTagihanIdsToNotify[] = $newTagihan->id;
                }
            }

            DB::commit();

            if (!empty($fcmTagihanIdsToNotify)) {
                $fcmNotificationChunkSize = max(1, (int) env('TAGIHAN_BROADCAST_FCM_CHUNK_SIZE', 1000));
                foreach (array_chunk($fcmTagihanIdsToNotify, $fcmNotificationChunkSize) as $tagihanIdChunk) {
                    $fcmDeliveryMode = $this->dispatchFcmTagihanPush($tagihanIdChunk, null, 'created');
                }
            }

            $message = "Berhasil membuat tagihan untuk {$successCount} pelanggan.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} pelanggan gagal (tidak memiliki paket atau sudah memiliki tagihan).";
            }
            if (!empty($fcmTagihanIdsToNotify)) {
                $message .= ' Notifikasi diproses di background.';
            }

            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'processed' => $successCount,
                    'failed' => $failedCount,
                    'fcm_notifications' => count($fcmTagihanIdsToNotify),
                    'fcm_delivery_mode' => str_contains($fcmDeliveryMode, 'sync') ? 'sync' : 'queue',
                    'message' => $message,
                ]);
            }

            return back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error mass store tagihan', [
                'error' => $e->getMessage(),
                'pelanggan_ids' => $pelangganIds,
            ]);

            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'processed' => 0,
                    'failed' => count($pelangganIds),
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }





    public function export(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'lunas');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $bank = $request->input('bank');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $exportMode = $request->input('export_mode');
        $tanggalBayar = $request->input('tanggal_bayar');

        if ($exportMode === 'tanggal_bayar') {
            if (empty($tanggalBayar)) {
                return redirect()->back()->with('error', 'Tanggal bayar wajib dipilih untuk export tanggal bayar.');
            }
            $tanggalDari = $tanggalBayar;
            $tanggalSampai = $tanggalBayar;
            $bulan = null;
            $tahun = null;
        }

        $filename = 'Tagihan_Lunas_' .
            ($tanggalBayar ? 'Tanggal_' . $tanggalBayar . '_' : '') .
            ($tanggalDari ? 'From_' . $tanggalDari . '_' : '') .
            ($tanggalSampai ? 'To_' . $tanggalSampai . '_' : '') .
            ($bulan ? 'B' . $bulan . '_' : '') .
            ($tahun ? 'Y' . $tahun . '_' : '') .
            now()->format('Y-m-d_His') .
            '.xlsx';

        return Excel::download(
            new BayarExport($search, $status, $bulan, $tahun, $bank, $tanggalDari, $tanggalSampai),
            $filename
        );
    }

    /**
     * Export khusus tanggal bayar/verifikasi:
     * hanya pelanggan status lunas di tanggal pembayaran yang dipilih.
     */
    public function exportTanggalBayar(Request $request)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'bank' => 'nullable|string',
        ]);

        $tanggalBayar = $request->input('tanggal_bayar');
        $bank = $request->input('bank');

        $filename = 'Tagihan_Lunas_Tanggal_Bayar_' . $tanggalBayar . '_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new BayarExport(
                null,
                'lunas',
                null,
                null,
                $bank,
                $tanggalBayar,
                $tanggalBayar
            ),
            $filename
        );
    }

    /**
     * Export tagihan yang belum lunas (status: belum bayar)
     */
    public function exportBelumLunas(Request $request)
    {
        $search = $request->input('search');
        $periode = $request->input('periode'); // format: 2026-01

        // Generate filename
        $filename = 'Tagihan_Belum_Lunas_' . ($periode ? $periode . '_' : '') . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new TagihanBelumLunasMonthlyExport($search, $periode),
            $filename
        );
    }

    /**
     * Halaman Outstanding - Semua Tagihan (Tanpa Filter Status)
     * Berguna untuk melihat semua tagihan dari bulan lain
     */
    public function outstanding(Request $request)
    {
        // ? Base query dengan eager loading, default hanya yang belum lunas
        $query = Tagihan::with(['pelanggan', 'paket'])
            ->where('status_pembayaran', 'belum bayar');

        // ? Filter berdasarkan bulan/tahun (opsional)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_mulai', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        // ? Filter berdasarkan status (opsional, jika dipilih selain 'semua', override default)
        if ($request->filled('status_filter') && $request->status_filter !== 'semua') {
            $query->where('status_pembayaran', $request->status_filter); // Filter sesuai status
        }
        // Jika status_filter == 'semua', jangan override filter default (hanya 'belum bayar')

        // ? Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($subQ) use ($search) {
                    $subQ->where('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('nomer_id', 'LIKE', "%{$search}%")
                        ->orWhere('no_whatsapp', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('paket', function ($subQ) use ($search) {
                        $subQ->where('nama_paket', 'LIKE', "%{$search}%");
                    });
            });
        }

        // ? Sorting berdasarkan tanggal terbaru
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // ? Pagination
        $perPage = $request->input('per_page', 20);
        $tagihans = $query->paginate($perPage)->withQueryString();

        // ? Map data untuk view
        $tagihans->getCollection()->transform(function ($item) {
            $pelanggan = $item->pelanggan;
            $paket = $item->paket;

            return (object) [
                'id' => $item->id,
                'nomer_id' => $pelanggan->nomer_id ?? '-',
                'nama_lengkap' => $pelanggan->nama_lengkap ?? '-',
                'alamat_jalan' => $pelanggan->alamat_jalan ?? '-',
                'rt' => $pelanggan->rt ?? '-',
                'rw' => $pelanggan->rw ?? '-',
                'desa' => $pelanggan->desa ?? '-',
                'kecamatan' => $pelanggan->kecamatan ?? '-',
                'kabupaten' => $pelanggan->kabupaten ?? '-',
                'provinsi' => $pelanggan->provinsi ?? '-',
                'kode_pos' => $pelanggan->kode_pos ?? '-',
                'paket' => [
                    'id' => $paket->id ?? null,
                    'nama_paket' => $paket->nama_paket ?? '-',
                    'harga' => $paket->harga ?? 0,
                    'kecepatan' => $paket->kecepatan ?? 0,
                    'masa_pembayaran' => $paket->masa_pembayaran ?? 0,
                    'durasi' => $paket->durasi ?? 0,
                ],
                'tanggal_mulai' => $item->tanggal_mulai ?? null,
                'tanggal_berakhir' => $item->tanggal_berakhir ?? null,
                'status_pembayaran' => $item->status_pembayaran ?? 'belum bayar',
                'tanggal_pembayaran' => $item->tanggal_pembayaran ?? '-',
                'bukti_pembayaran' => $item->bukti_pembayaran ?? '-',
                'kwitansi' => $item->kwitansi ?? null,
                'no_whatsapp' => $pelanggan->no_whatsapp ?? '08xxxxxxxxxx',
                'catatan' => $item->catatan ?? '-',
            ];
        });

        // ? Ambil pelanggan & paket untuk dropdown (jika ada modal)
        $pelanggan = Pelanggan::where('status', 'approve')->get();
        $paket = Paket::all();

        // ? Statistik Outstanding
        try {
            $totalTagihan = Tagihan::count();
            $totalBelumBayar = Tagihan::where('status_pembayaran', 'belum bayar')->count();
            $totalProses = Tagihan::where('status_pembayaran', 'proses_verifikasi')->count();
            $totalLunas = Tagihan::where('status_pembayaran', 'lunas')->count();

            // Total tagihan yang overdue (lewat tanggal jatuh tempo)
            $totalOverdue = Tagihan::where('status_pembayaran', '!=', 'lunas')
                ->where('tanggal_berakhir', '<', now())
                ->count();

            // Total nilai outstanding (belum dibayar)
            $nilaiOutstanding = Tagihan::where('status_pembayaran', 'belum bayar')
                ->join('pakets', 'tagihans.paket_id', '=', 'pakets.id')
                ->sum('pakets.harga');

            $statistics = [
                'total' => $totalTagihan,
                'belum_bayar' => $totalBelumBayar,
                'proses' => $totalProses,
                'lunas' => $totalLunas,
                'overdue' => $totalOverdue,
                'nilai_outstanding' => $nilaiOutstanding,
            ];
        } catch (\Exception $e) {
            $statistics = [
                'total' => 0,
                'belum_bayar' => 0,
                'proses' => 0,
                'lunas' => 0,
                'overdue' => 0,
                'nilai_outstanding' => 0,
            ];
        }

        // ? Filter dropdown lists
        $kabupatenList = Pelanggan::pluck('kabupaten')->unique()->filter();
        $kecamatanList = Pelanggan::pluck('kecamatan')->unique()->filter();

        // ? Bulan untuk filter
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // ? Tahun untuk filter (5 tahun terakhir)
        $tahunList = range(date('Y'), date('Y') - 4);

        return view('content.apps.Tagihan.outstanding', compact(
            'tagihans',
            'pelanggan',
            'paket',
            'statistics',
            'kabupatenList',
            'kecamatanList',
            'bulanList',
            'tahunList'
        ));
    }





    public function exportMaster(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $filename = 'Master_Tagihan_Tahun_' . $tahun . '_' . now()->format('Y-m-d_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\MasterTagihanExport($tahun),
            $filename
        );
    }

    public function exportBulanLalu(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $targetDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->subMonth();
        $filename = 'Laporan_Tagihan_' . $targetDate->translatedFormat('F_Y') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\TagihanBulanLaluExport($bulan, $tahun),
            $filename
        );
    }

    public function exportSemuaLunas(Request $request)
    {
        $tahun = date('Y');

        // Ambil tagihan yang lunas tahun ini dan belum diexport
        $tagihans = \App\Models\Tagihan::where('status_pembayaran', 'lunas')
            ->whereYear('tanggal_mulai', $tahun)
            ->where('is_exported', false)
            ->get();

        if ($tagihans->isNotEmpty()) {
            $ids = $tagihans->pluck('id')->toArray();
            // Update jadi exported
            \App\Models\Tagihan::whereIn('id', $ids)->update(['is_exported' => true]);
        }

        $filename = 'Semua_Lunas_Master_' . $tahun . '_' . now()->format('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SemuaLunasExport($tahun),
            $filename
        );
    }
}
