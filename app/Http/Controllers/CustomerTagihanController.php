<?php

namespace App\Http\Controllers;

use App\Models\CustomerTagihan;
use App\Models\Pelanggan;
use App\Models\Rekening;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage; // pastikan import model Rekening
use Illuminate\Support\Str;

class CustomerTagihanController extends Controller
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
        $targetDir = $basePath.'/bukti_pembayaran';

        if (! is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        if (is_dir($targetDir) && is_writable($targetDir)) {
            $filename = now()->format('YmdHis').'_'.Str::random(20).'.'.$file->getClientOriginalExtension();
            $file->move($targetDir, $filename);

            return 'bukti_pembayaran/'.$filename;
        }

        return $file->store('bukti_pembayaran', 'public');
    }

    private function deleteBuktiPembayaranByNomorId(?string $buktiPath, ?string $nomorId): void
    {
        $buktiPath = trim((string) $buktiPath);
        if ($buktiPath === '') {
            return;
        }

        $basePath = $this->getBuktiPembayaranBasePathByNomorId($nomorId);
        $absolutePath = $basePath.'/'.ltrim($buktiPath, '/');
        if (is_file($absolutePath)) {
            @unlink($absolutePath);

            return;
        }

        if (Storage::disk('public')->exists($buktiPath)) {
            Storage::disk('public')->delete($buktiPath);
        }
    }

    private function markCustomerTagihansAsRead($pelangganId): void
    {
        try {
            $hasReadAtColumn = Cache::remember('tagihans_has_read_at_column', now()->addHour(), function (): bool {
                return Schema::hasColumn('tagihans', 'read_at');
            });

            if (! $hasReadAtColumn) {
                return;
            }

            Tagihan::where('pelanggan_id', $pelangganId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } catch (\Throwable $e) {
            Log::warning('Gagal update read_at pada tagihan pelanggan', [
                'pelanggan_id' => $pelangganId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getUnreadInformationCount(): int
    {
        $lastInformationReadAt = session('customer_information_read_at');

        return \App\Models\Iklan::where('status', 'active')
            ->when($lastInformationReadAt, function ($query) use ($lastInformationReadAt) {
                $query->where('created_at', '>', $lastInformationReadAt);
            })
            ->count();
    }

    public function profile()
    {
        $user = Auth::guard('customer')->user();

        if (! $user) {
            return redirect()->route('users.member');
        }

        return view('content.apps.Customer.profile.profile', compact('user'));
    }

    public function riwayat()
    {
        $user = Auth::guard('customer')->user();

        if (! $user) {
            return redirect()->route('users.member');
        }

        $this->markCustomerTagihansAsRead($user->id);

        $tagihans = Tagihan::with(['paket', 'rekening'])
            ->where('pelanggan_id', $user->id)
            ->where('status_pembayaran', 'lunas')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('content.apps.Customer.riwayat.riwayat', compact('user', 'tagihans'));
    }

    public function faq()
    {
        $user = Auth::guard('customer')->user();

        return view('content.apps.Customer.faq.faq', compact('user'));
    }

    public function previewKwitansi($id)
    {
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $tagihan = Tagihan::where('pelanggan_id', $pelanggan->id)->findOrFail($id);

        if (! $tagihan->kwitansi) {
            abort(404, 'Kwitansi belum tersedia.');
        }

        $kwitansiPath = trim((string) $tagihan->kwitansi);
        $kwitansiPath = Str::startsWith($kwitansiPath, ['http://', 'https://'])
            ? ltrim((string) parse_url($kwitansiPath, PHP_URL_PATH), '/')
            : ltrim($kwitansiPath, '/');

        if (Str::startsWith($kwitansiPath, 'storage/')) {
            $kwitansiPath = Str::after($kwitansiPath, 'storage/');
        }

        if (Str::startsWith($kwitansiPath, 'app/public/')) {
            $kwitansiPath = Str::after($kwitansiPath, 'app/public/');
        }

        $candidatePaths = [
            storage_path('app/public/'.$kwitansiPath),
        ];

        if (! Str::startsWith($kwitansiPath, 'kwitansi/')) {
            $candidatePaths[] = storage_path('app/public/kwitansi/'.$kwitansiPath);
        }

        $externalStorageRoots = [
            rtrim(env('JMKGK_PUBLIC_STORAGE_PATH', '/var/www/billingJMKGK/storage/app/public'), '/'),
            rtrim(env('JMK_PUBLIC_STORAGE_PATH', '/var/www/billingjmk/storage/app/public'), '/'),
        ];

        foreach ($externalStorageRoots as $storageRoot) {
            $candidatePaths[] = $storageRoot.'/'.$kwitansiPath;
            if (! Str::startsWith($kwitansiPath, 'kwitansi/')) {
                $candidatePaths[] = $storageRoot.'/kwitansi/'.$kwitansiPath;
            }
        }

        $candidatePaths = array_values(array_unique($candidatePaths));

        $filePath = collect($candidatePaths)->first(fn ($path) => is_file($path));
        if (! is_file($filePath)) {
            Log::error('Customer kwitansi preview file not found', [
                'tagihan_id' => $id,
                'pelanggan_id' => $pelanggan->id,
                'kwitansi_field' => $tagihan->kwitansi,
                'normalized_path' => $kwitansiPath,
                'candidate_paths' => $candidatePaths,
            ]);

            abort(404, 'File kwitansi tidak ditemukan.');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/pdf';
        $fileSize = filesize($filePath);

        return response()->stream(function () use ($filePath) {
            if (ob_get_level()) {
                ob_end_clean();
            }

            $stream = fopen($filePath, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline; filename="'.basename($filePath).'"',
            'Cache-Control' => 'private, max-age=300',
            'Pragma' => 'public',
            'Accept-Ranges' => 'bytes',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function previewBuktiPembayaran($id)
    {
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $tagihan = Tagihan::where('pelanggan_id', $pelanggan->id)->findOrFail($id);

        if (! $tagihan->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran belum tersedia.');
        }

        $buktiPath = trim((string) $tagihan->bukti_pembayaran);
        $buktiPath = Str::startsWith($buktiPath, ['http://', 'https://'])
            ? ltrim((string) parse_url($buktiPath, PHP_URL_PATH), '/')
            : ltrim($buktiPath, '/');

        if (Str::startsWith($buktiPath, 'storage/')) {
            $buktiPath = Str::after($buktiPath, 'storage/');
        }

        if (Str::startsWith($buktiPath, 'app/public/')) {
            $buktiPath = Str::after($buktiPath, 'app/public/');
        }

        $candidatePaths = [
            storage_path('app/public/'.$buktiPath),
        ];

        if (! Str::startsWith($buktiPath, 'bukti_pembayaran/')) {
            $candidatePaths[] = storage_path('app/public/bukti_pembayaran/'.$buktiPath);
        }

        $externalStorageRoots = [
            $this->getBuktiPembayaranBasePathByNomorId($pelanggan->nomer_id ?? null),
            rtrim(env('JMKGK_PUBLIC_STORAGE_PATH', '/var/www/billingJMKGK/storage/app/public'), '/'),
            rtrim(env('JMK_PUBLIC_STORAGE_PATH', '/var/www/billingjmk/storage/app/public'), '/'),
        ];

        foreach (array_unique($externalStorageRoots) as $storageRoot) {
            $candidatePaths[] = $storageRoot.'/'.$buktiPath;
            if (! Str::startsWith($buktiPath, 'bukti_pembayaran/')) {
                $candidatePaths[] = $storageRoot.'/bukti_pembayaran/'.$buktiPath;
            }
        }

        $candidatePaths = array_values(array_unique($candidatePaths));
        $filePath = collect($candidatePaths)->first(fn ($path) => is_file($path));

        if (! is_file($filePath)) {
            Log::error('Customer bukti pembayaran preview file not found', [
                'tagihan_id' => $id,
                'pelanggan_id' => $pelanggan->id,
                'bukti_pembayaran' => $tagihan->bukti_pembayaran,
                'normalized_path' => $buktiPath,
                'candidate_paths' => $candidatePaths,
            ]);

            abort(404, 'File bukti pembayaran tidak ditemukan.');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        $fileSize = filesize($filePath);

        return response()->stream(function () use ($filePath) {
            if (ob_get_level()) {
                ob_end_clean();
            }

            $stream = fopen($filePath, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline; filename="'.basename($filePath).'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Accept-Ranges' => 'bytes',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function informasi()
    {
        $user = Auth::guard('customer')->user();

        if (! $user) {
            return redirect()->route('users.member');
        }

        $iklans = Cache::remember('customer_active_iklans', now()->addMinutes(5), function () {
            return \App\Models\Iklan::query()
                ->select(['id', 'title', 'message', 'image', 'type', 'created_at'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
        });

        session(['customer_information_read_at' => now()]);
        session(['customer_unread_information_count' => 0]);

        return view('content.apps.Customer.informasi.informasi', compact('user', 'iklans'));
    }

    public function update(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg|max:5120',
            'type_pembayaran' => 'required|exists:rekenings,id',
        ]);

        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        // Pastikan tagihan milik pelanggan
        $tagihan = Tagihan::where('pelanggan_id', $pelanggan->id)->findOrFail($id);

        try {
            // Upload bukti pembayaran
            if ($request->hasFile('bukti_pembayaran')) {

                // Hapus file lama jika ada
                $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $pelanggan->nomer_id ?? null);

                $path = $this->storeBuktiPembayaranByNomorId($request->file('bukti_pembayaran'), $pelanggan->nomer_id ?? null);

                // Update tagihan
                $tagihan->update([
                    'bukti_pembayaran' => $path,
                    'type_pembayaran' => $request->type_pembayaran, // ID rekening
                    'status_pembayaran' => 'proses_verifikasi',
                    'alasan_penolakan' => null,
                    'ditolak_at' => null,
                ]);

                // Kirim notifikasi Telegram ke admin
                try {
                    $telegramService = new \App\Services\TelegramService;
                    $telegramService->sendPaymentNotification($tagihan);
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim notifikasi Telegram: '.$e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diupload! Status menunggu verifikasi admin.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File bukti pembayaran tidak ditemukan.',
            ], 400);

        } catch (\Exception $e) {
            // Debugging untuk mengetahui penyebab error
            \Log::error('Tagihan update error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal upload: '.$e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        // Ambil pelanggan yang sedang login via guard 'customer'
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu.');
        }

        $this->markCustomerTagihansAsRead($pelanggan->id);

        // Ambil tagihan pelanggan berdasarkan status tertentu
        $tagihans = Tagihan::with([
            'pelanggan:id,user_id,nama_lengkap,nomer_id,no_whatsapp,alamat_jalan',
            'pelanggan.user:id,name',
            'paket:id,nama_paket,harga,kecepatan',
        ])
            ->select([
                'id',
                'pelanggan_id',
                'paket_id',
                'tanggal_mulai',
                'tanggal_berakhir',
                'status_pembayaran',
                'catatan',
                'bukti_pembayaran',
                'kwitansi',
                'type_pembayaran',
                'alasan_penolakan',
                'ditolak_at',
                'updated_at',
            ])
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status_pembayaran', ['proses_verifikasi', 'belum bayar'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Ambil semua rekening untuk ditampilkan di form pembayaran
        $rekenings = Cache::remember('customer_payment_rekenings', now()->addMinutes(10), function () {
            return Rekening::query()
                ->select(['id', 'nama_bank', 'nomor_rekening', 'nama_pemilik'])
                ->get();
        });

        return view('content.apps.Customer.tagihan.tagihan', compact('tagihans', 'rekenings'));
    }

    public function indexHome()
    {
        // Ambil pelanggan yang sedang login via guard 'customer'
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu.');
        }

        // Hitung semua statistik dalam satu query supaya dashboard customer lebih cepat.
        $tagihanStats = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->selectRaw('COUNT(*) as total_tagihan')
            ->selectRaw("SUM(CASE WHEN status_pembayaran = 'lunas' THEN 1 ELSE 0 END) as tagihan_lunas")
            ->selectRaw("SUM(CASE WHEN status_pembayaran = 'proses_verifikasi' THEN 1 ELSE 0 END) as tagihan_menunggu")
            ->selectRaw("SUM(CASE WHEN status_pembayaran = 'belum bayar' THEN 1 ELSE 0 END) as tagihan_belum")
            ->first();

        $totalTagihan = (int) ($tagihanStats->total_tagihan ?? 0);
        $tagihanLunas = (int) ($tagihanStats->tagihan_lunas ?? 0);
        $tagihanMenunggu = (int) ($tagihanStats->tagihan_menunggu ?? 0);
        $tagihanBelum = (int) ($tagihanStats->tagihan_belum ?? 0);

        $this->markCustomerTagihansAsRead($pelanggan->id);

        $unreadInformationCount = $this->getUnreadInformationCount();
        session(['customer_unread_information_count' => $unreadInformationCount]);

        return view('content.apps.Customer.tagihan.home', compact(
            'totalTagihan',
            'tagihanLunas',
            'tagihanMenunggu',
            'tagihanBelum',
            'unreadInformationCount'
        ));
    }

    public function selesai()
    {
        // Ambil pelanggan yang sedang login via guard 'customer'
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu.');
        }

        $this->markCustomerTagihansAsRead($pelanggan->id);

        // Ambil tagihan pelanggan dengan status 'lunas'
        $tagihans = Tagihan::with(['pelanggan.user', 'paket', 'rekening'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pembayaran', 'lunas')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('content.apps.Customer.tagihan.lunas-tagihan', compact('tagihans'));
    }

    public function getInvoiceJson()
    {
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        $this->markCustomerTagihansAsRead($pelanggan->id);

        $tagihans = Tagihan::with(['pelanggan', 'paket', 'rekening'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pembayaran', 'lunas')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Mapping supaya data lebih rapi dan termasuk tanggal pembayaran + type pembayaran
        $tagihansData = $tagihans->map(function ($tagihan) {
            return [
                'id' => $tagihan->id,
                'pelanggan_id' => $tagihan->pelanggan_id,
                'nama_pelanggan' => $tagihan->pelanggan->nama_lengkap ?? null,
                'nomer_id' => $tagihan->pelanggan->nomer_id ?? null,

                'harga' => $tagihan->paket->harga,

                'tanggal_mulai' => $tagihan->tanggal_mulai,
                'tanggal_berakhir' => $tagihan->tanggal_berakhir,
                'tanggal_pembayaran' => $tagihan->tanggal_pembayaran,

                // AMBIL NAMA BANK DARI RELASI REKENING
                'type_pembayaran' => $tagihan->rekening->nama_bank ?? null,

                'kwitansi' => $tagihan->kwitansi,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $tagihansData,
        ]);
    }

    public function getTagihanJson()
    {
        // Ambil pelanggan yang sedang login via guard 'customer'
        $pelanggan = Auth::guard('customer')->user();

        if (! $pelanggan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        $this->markCustomerTagihansAsRead($pelanggan->id);

        // Ambil tagihan pelanggan yang belum lunas / proses_verifikasi
        // sekaligus relasi pelanggan dan paket
        $tagihans = Tagihan::query()
            ->select(['id', 'pelanggan_id', 'status_pembayaran', 'bukti_pembayaran', 'updated_at'])
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status_pembayaran', ['proses_verifikasi', 'belum bayar'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $tagihansData = $tagihans->map(function ($tagihan) {
            return [
                'id' => $tagihan->id,
                'pelanggan_id' => $tagihan->pelanggan_id,
                'status_pembayaran' => $tagihan->status_pembayaran,
                'bukti_pembayaran' => $tagihan->bukti_pembayaran,
                'updated_at' => optional($tagihan->updated_at)->toISOString(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $tagihansData,
        ]);
    }

    public function show($id)
    {
        $tagihan = CustomerTagihan::with('tagihan.paket')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Tandai tagihan sebagai sudah dibaca
        if ($tagihan->tagihan) {
            $tagihan->tagihan->markAsRead();
        }

        return view('content.customer.tagihan.show', compact('tagihan'));
    }
}
