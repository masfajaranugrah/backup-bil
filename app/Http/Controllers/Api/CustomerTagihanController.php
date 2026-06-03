<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    // Ambil tagihan aktif
    public function getTagihanJson()
    {
        $pelanggan = auth()->user();

        if (! $pelanggan) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $tagihans = Tagihan::with('paket')
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status_pembayaran', ['proses_verifikasi', 'belum bayar'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $tagihans]);
    }

    // Ambil tagihan selesai
    public function getTagihanSelesaiJson()
    {
        $pelanggan = auth()->user();

        if (! $pelanggan) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $tagihans = Tagihan::with('paket')
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status_pembayaran', 'lunas')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $tagihans]);
    }

    // Detail tagihan tertentu
    public function showJson($id)
    {
        $pelanggan = auth()->user();

        $tagihan = Tagihan::with('paket')
            ->where('pelanggan_id', $pelanggan->id)
            ->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $tagihan]);
    }

    // Upload bukti pembayaran
    public function uploadJson(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $pelanggan = auth()->user();

        $tagihan = Tagihan::where('pelanggan_id', $pelanggan->id)->findOrFail($id);

        try {
            $this->deleteBuktiPembayaranByNomorId($tagihan->bukti_pembayaran, $pelanggan->nomer_id ?? null);

            $path = $this->storeBuktiPembayaranByNomorId($request->file('bukti_pembayaran'), $pelanggan->nomer_id ?? null);

            $tagihan->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'proses_verifikasi',
            ]);

            // Kirim notifikasi Telegram ke admin
            try {
                $telegramService = new \App\Services\TelegramService();
                $telegramService->sendPaymentNotification($tagihan);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi Telegram: ' . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bukti pembayaran berhasil diupload! Status menunggu verifikasi admin.',
                'data' => ['path' => $path],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal upload: '.$e->getMessage(),
            ], 500);
        }
    }
}
