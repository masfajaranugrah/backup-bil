<?php

namespace App\Http\Controllers;

use App\Models\CustomerTagihan;
use App\Models\Pelanggan;
use App\Models\Rekening;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // pastikan import model Rekening
use Illuminate\Support\Str;

class MobileTagihanController extends Controller
{
    private function kwitansiUrl(Tagihan $tagihan, string $routeName = 'kwitansi.preview'): ?string
    {
        if (!$tagihan->kwitansi) {
            return null;
        }

        $nomerId = strtoupper(str_replace(['.', '-', ' '], '', (string) ($tagihan->pelanggan->nomer_id ?? $tagihan->nomer_id ?? '')));
        $baseUrl = str_starts_with($nomerId, 'JMKGK')
            ? rtrim(config('app.jmkgk_app_url', 'https://layanan.beningmedia.co.id'), '/')
            : rtrim(config('app.jmk_app_url', 'https://layanan.jernih.net.id'), '/');

        return $baseUrl . route($routeName, [
            'tagihan_id' => $tagihan->id,
            'code' => $this->kwitansiCode($tagihan),
        ], false);
    }

    private function kwitansiCode(Tagihan $tagihan): string
    {
        $paymentDate = $tagihan->tanggal_pembayaran
            ? \Carbon\Carbon::parse($tagihan->tanggal_pembayaran)->format('Y-m-d H:i:s')
            : '-';

        $payload = implode('|', [
            (string) $tagihan->id,
            (string) ($tagihan->nomer_id ?? ''),
            (string) ($tagihan->pelanggan_id ?? ''),
            $paymentDate,
            (string) ($tagihan->status_pembayaran ?? ''),
            (string) ($tagihan->kwitansi ?? ''),
        ]);

        return strtoupper(substr(hash_hmac('sha256', $payload, (string) config('app.key')), 0, 16));
    }

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

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        if (is_dir($targetDir) && is_writable($targetDir)) {
            $filename = now()->format('YmdHis') . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move($targetDir, $filename);
            return 'bukti_pembayaran/' . $filename;
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
        $absolutePath = $basePath . '/' . ltrim($buktiPath, '/');
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
            return;
        }

        if (Storage::disk('public')->exists($buktiPath)) {
            Storage::disk('public')->delete($buktiPath);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
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
                ]);

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

        // Ambil tagihan pelanggan berdasarkan status tertentu
        $tagihans = Tagihan::with('pelanggan.user')
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status_pembayaran', ['proses_verifikasi', 'belum bayar'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Ambil semua rekening untuk ditampilkan di form pembayaran
        $rekenings = Rekening::all();

        return view('content.apps.Mobile.tagihan.tagihan', compact('tagihans', 'rekenings'));
    }

public function indexHome()
{
    // Ambil pelanggan yang sedang login via guard 'customer'
    $pelanggan = Auth::guard('customer')->user();

    if (!$pelanggan) {
        return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu.');
    }

    // Hitung statistik tagihan berdasarkan status_pembayaran
    $totalTagihan = Tagihan::where('pelanggan_id', $pelanggan->id)->count();
    
    $tagihanLunas = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->where('status_pembayaran', 'lunas')
        ->count();
    
    $tagihanMenunggu = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->where('status_pembayaran', 'proses_verifikasi')
        ->count();
    
    $tagihanBelum = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->where('status_pembayaran', 'belum bayar')
        ->count();

    // Ambil tagihan pelanggan berdasarkan status tertentu
    $tagihans = Tagihan::with('pelanggan.user')
        ->where('pelanggan_id', $pelanggan->id)
        ->whereIn('status_pembayaran', ['proses_verifikasi', 'belum bayar'])
        ->orderBy('tanggal_mulai', 'desc')
        ->get();

    // Ambil aktivitas terakhir (recent activities) - 5 terakhir
    $recentActivities = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->orderBy('updated_at', 'desc')
        ->limit(5)
        ->get();

    // ? AMBIL IKLAN/INFORMASI YANG ACTIVE
    $iklans = \App\Models\Iklan::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->limit(5) // Ambil max 5 iklan terbaru
        ->get();

    return view('content.apps.Mobile.tagihan.home', compact(
        'tagihans', 
        'totalTagihan', 
        'tagihanLunas', 
        'tagihanMenunggu', 
        'tagihanBelum',
        'recentActivities',
        'iklans' // ? Pass iklan ke view
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

        // Ambil tagihan pelanggan dengan status 'lunas'
        $tagihans = Tagihan::with(['pelanggan.user', 'rekening'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pembayaran', 'lunas')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('content.apps.Mobile.tagihan.lunas-tagihan', compact('tagihans'));
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

                'kwitansi' => $this->kwitansiUrl($tagihan),
                'kwitansi_download' => $this->kwitansiUrl($tagihan, 'kwitansi.download'),
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

        // Ambil tagihan pelanggan yang belum lunas / proses_verifikasi
        // sekaligus relasi pelanggan dan paket
        $tagihans = Tagihan::with(['pelanggan', 'paket']) // pastikan relasi paket ada di model Tagihan
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status_pembayaran', ['proses_verifikasi', 'belum bayar'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Bisa kita map supaya data lebih rapi
        $tagihansData = $tagihans->map(function ($tagihan) {
            return [
                'id' => $tagihan->id,
                'pelanggan_id' => $tagihan->pelanggan_id,
                'paket_id' => $tagihan->paket_id,
                'nama_paket' => $tagihan->paket->nama ?? null,
                'harga' => $tagihan->paket->harga,
                'kecepatan' => $tagihan->paket->kecepatan ?? null,
                'masa_pembayaran' => $tagihan->masa_pembayaran,
                'tanggal_mulai' => $tagihan->tanggal_mulai,
                'tanggal_berakhir' => $tagihan->tanggal_berakhir,
                'status_pembayaran' => $tagihan->status_pembayaran,
                'tanggal_pembayaran' => $tagihan->tanggal_pembayaran,
                'catatan' => $tagihan->catatan,
                'bukti_pembayaran' => $tagihan->bukti_pembayaran,
                'kwitansi' => $this->kwitansiUrl($tagihan),
                'kwitansi_download' => $this->kwitansiUrl($tagihan, 'kwitansi.download'),
                'pelanggan' => [
                    'id' => $tagihan->pelanggan->id,
                    'nama_lengkap' => $tagihan->pelanggan->nama_lengkap,
                    'no_ktp' => $tagihan->pelanggan->no_ktp,
                    'no_whatsapp' => $tagihan->pelanggan->no_whatsapp,
                    'alamat_jalan' => $tagihan->pelanggan->alamat_jalan,
                    'nomer_id' => $tagihan->pelanggan->nomer_id,
                    // Tambahkan field lain yang dibutuhkan
                ],
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $tagihansData,
        ]);
    }

public function summaryJson()
{
    $pelanggan = Auth::guard('customer')->user();

    if (! $pelanggan) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    $totalTagihan = Tagihan::where('pelanggan_id', $pelanggan->id)->count();

    $totalLunas = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->where('status_pembayaran', 'lunas')
        ->count();

    $totalMenunggu = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->where('status_pembayaran', 'proses_verifikasi')
        ->count();

    $totalBelumBayar = Tagihan::where('pelanggan_id', $pelanggan->id)
        ->where('status_pembayaran', 'belum bayar')
        ->count();

    return response()->json([
        'status' => 'success',
        'data' => [
            'total_tagihan' => $totalTagihan,
            'total_lunas' => $totalLunas,
            'total_menunggu' => $totalMenunggu,
            'total_belum_bayar' => $totalBelumBayar,
        ],
    ]);
}



public function show($id)
{
    $pelanggan = Auth::guard('customer')->user();

    if (! $pelanggan) {
        abort(401);
    }

    $tagihan = Tagihan::with(['pelanggan', 'paket', 'rekening'])
        ->where('pelanggan_id', $pelanggan->id)
        ->findOrFail($id);

    return view('content.apps.Mobile.tagihan.show', compact('tagihan'));
}
}
