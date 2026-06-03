<?php
// app/Http/Controllers/IklanController.php

namespace App\Http\Controllers;

use App\Models\Iklan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Jobs\SendIklanJob;

class IklanController extends Controller
{
    public function index()
    {
        $iklans = Iklan::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('content.apps.Iklan.iklan', compact('iklans'));
    }

    public function create()
    {
        return view('content.apps.Iklan.add-iklan');
    }

public function store(Request $request)
{
    try {
        Log::info('Iklan store request', [
            'user_id' => Auth::id(),
            'data' => $request->except('image')
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
            'type' => 'required|in:informasi,maintenance,iklan',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            Log::info('Uploading image');
            $imagePath = $request->file('image')->store('iklan', 'public');
            Log::info('Image uploaded', ['path' => $imagePath]);
        }

        $iklanData = [
            'id' => (string) Str::uuid(),
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'image' => $imagePath,
            'status' => 'active',
            'total_sent' => 0,
            'created_by' => Auth::id()
        ];

        Log::info('Creating iklan', $iklanData);
        $iklan = Iklan::create($iklanData);
        Log::info('Iklan created successfully', ['iklan_id' => $iklan->id]);

        $initialTotal = $this->resolvePushTargetTotal();
        Cache::put('iklan_push_progress:' . $iklan->id, [
            'status' => 'queued',
            'total' => $initialTotal,
            'processed' => 0,
            'sent' => 0,
            'failed' => 0,
        ], now()->addHours(6));

        // Kirim push notification setelah iklan dibuat via queue.
        if (!$this->queueReadyForLargeBroadcast()) {
            Cache::put('iklan_push_progress:' . $iklan->id, [
                'status' => 'failed',
                'total' => $initialTotal,
                'processed' => 0,
                'sent' => 0,
                'failed' => $initialTotal,
                'finished_at' => now()->toDateTimeString(),
            ], now()->addHours(6));

            return redirect()->route('iklan.index')
                ->with('error', 'Iklan berhasil dibuat, tetapi notifikasi tidak dikirim karena queue belum aman untuk broadcast besar.')
                ->with('iklan_progress_id', $iklan->id);
        }

        SendIklanJob::dispatch($iklan->id);

        return redirect()->route('iklan.index')
            ->with('success', 'Iklan berhasil dibuat dan notifikasi sedang dikirim!')
            ->with('iklan_progress_id', $iklan->id);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation error', ['errors' => $e->errors()]);
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        Log::error('Error creating iklan', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return redirect()->back()
            ->with('error', 'Gagal membuat iklan: ' . $e->getMessage())
            ->withInput();
    }
}

// ? Method untuk kirim push notification via WebPushr
private function sendPushNotification($iklan)
{
    try {
        $pelanggans = Pelanggan::whereNotNull('webpushr_sid')
            ->where('webpushr_sid', '!=', '')
            ->get();

        if ($pelanggans->isEmpty()) {
            Log::info('Tidak ada pelanggan dengan webpushr_sid');
            return;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($pelanggans as $pelanggan) {
            try {
                $result = $this->sendWebpushrNotification([
                    'title' => $iklan->title,
                    'message' => $iklan->message,
                    'target_url' => url('https://layanan.jernih.net.id/dashboard/customer/tagihan/home'),
                    'sid' => $pelanggan->webpushr_sid,
                ]);

                if ($result['success']) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }

            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Error sending to ' . $pelanggan->nama_lengkap, ['error' => $e->getMessage()]);
                continue;
            }
        }

        // Update total sent
        $iklan->update(['total_sent' => $sentCount]);

        Log::info('Push notification summary', [
            'sent' => $sentCount,
            'failed' => $failedCount,
            'total' => $pelanggans->count()
        ]);

    } catch (\Exception $e) {
        Log::error('Push notification error: ' . $e->getMessage());
    }
}

// ? Copy method dari PushNotificationController
private function sendWebpushrNotification($data)
{
    try {
        $ch = curl_init('https://api.webpushr.com/v1/notification/send/sid');

        $payload = [
            'title' => $data['title'] ?? 'Notifikasi',
            'message' => $data['message'] ?? '',
            'target_url' => $data['target_url'] ?? url('/'),
            'sid' => $data['sid'],
        ];

        $headers = [
            'Content-Type: application/json',
            'webpushrKey: ' . env('WEBPUSHR_KEY', ''),
            'webpushrAuthToken: ' . env('WEBPUSHR_TOKEN', ''),
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode == 200 && !empty($response)) {
            return ['success' => true, 'response' => $responseData];
        } else {
            return ['success' => false, 'error' => $curlError ?: 'HTTP Code: ' . $httpCode];
        }

    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}


public function edit($id)
{
    $iklan = Iklan::findOrFail($id);

    return view('content.apps.Iklan.edit-iklan', compact('iklan'));
}

public function update(Request $request, $id)
{
    try {
        $iklan = Iklan::findOrFail($id);

        // ? Validasi dengan type
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
            'type' => 'required|in:informasi,maintenance,iklan', // ? Tambah validasi type
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($iklan->image && Storage::disk('public')->exists($iklan->image)) {
                Storage::disk('public')->delete($iklan->image);
            }
            $validated['image'] = $request->file('image')->store('iklan', 'public');
        }

        $iklan->update($validated);

        return redirect()->route('iklan.index')
            ->with('success', 'Iklan berhasil diupdate tanpa mengirim notifikasi ulang.');

    } catch (\Exception $e) {
        Log::error('Error updating iklan', ['message' => $e->getMessage()]);

        return redirect()->back()
            ->with('error', 'Gagal update iklan: ' . $e->getMessage())
            ->withInput();
    }
}
    public function send($id)
    {
        try {
            $iklan = Iklan::find($id);

            if (!$iklan) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Iklan tidak ditemukan'
                ], 404);
            }

            // Tandai status sebagai queued (opsional, abaikan jika kolom tidak ada)
            $iklan->update(['status' => 'queued']);

            if (!$this->queueReadyForLargeBroadcast()) {
                return response()->json([
                    'success' => false,
                    'queued' => false,
                    'message' => 'Queue belum aman untuk kirim iklan. Gunakan database/redis queue dan jalankan queue worker.',
                    'iklan_id' => $iklan->id,
                ], 422);
            }

            // Dorong ke queue agar berjalan di background
            $initialTotal = $this->resolvePushTargetTotal();
            Cache::put('iklan_push_progress:' . $iklan->id, [
                'status' => 'queued',
                'total' => $initialTotal,
                'processed' => 0,
                'sent' => 0,
                'failed' => 0,
            ], now()->addHours(6));

            SendIklanJob::dispatch($iklan->id);

            return response()->json([
                'success' => true,
                'queued' => true,
                'message' => 'Iklan sedang dikirim di background melalui queue',
                'iklan_id' => $iklan->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending iklan', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'queued' => false,
                'message' => 'Gagal mengirim iklan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function progress($id)
    {
        $iklan = Iklan::find($id);

        if (!$iklan) {
            return response()->json([
                'success' => false,
                'message' => 'Iklan tidak ditemukan',
            ], 404);
        }

        $progress = Cache::get('iklan_push_progress:' . $id);

        if (!is_array($progress)) {
            $estimatedTotal = $this->resolvePushTargetTotal();

            return response()->json([
                'success' => true,
                'status' => $iklan->sent_at ? 'completed' : 'queued',
                'total' => $iklan->sent_at ? (int) ($iklan->total_sent ?? 0) : $estimatedTotal,
                'processed' => (int) ($iklan->total_sent ?? 0),
                'sent' => (int) ($iklan->total_sent ?? 0),
                'failed' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $progress['status'] ?? 'processing',
            'total' => (int) ($progress['total'] ?? 0),
            'processed' => (int) ($progress['processed'] ?? 0),
            'sent' => (int) ($progress['sent'] ?? 0),
            'failed' => (int) ($progress['failed'] ?? 0),
            'finished_at' => $progress['finished_at'] ?? null,
        ]);
    }

    private function resolvePushTargetTotal(): int
    {
        $fcmTargets = Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) != ''")
            ->count();

        $webpushFallbackTargets = Pelanggan::query()
            ->whereRaw("TRIM(COALESCE(fcm_token, '')) = ''")
            ->whereRaw("TRIM(COALESCE(webpushr_sid, '')) != ''")
            ->count();

        return $fcmTargets + $webpushFallbackTargets;
    }

    private function queueReadyForLargeBroadcast(): bool
    {
        $queueConnection = (string) config('queue.default', 'database');
        $jobsTable = (string) config('queue.connections.database.table', 'jobs');

        if ($queueConnection === 'sync') {
            return false;
        }

        return !($queueConnection === 'database' && !Schema::hasTable($jobsTable));
    }

    public function destroy($id)
    {
        try {
            $iklan = Iklan::findOrFail($id);
            $iklan->delete();

            return redirect()->route('iklan.index')
                ->with('success', 'Iklan berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting iklan', ['message' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus iklan: ' . $e->getMessage());
        }
    }
}
