<?php

namespace App\Http\Controllers;

use App\Models\LaporanKabel;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanKabelController extends Controller
{
    private const PER_PAGE = 40;

    public function index(Request $request)
    {
        $filters = $request->validate([
            'date' => 'nullable|date',
            'wilayah' => 'nullable|in:Klaten,Gunung Kidul,Boyolali',
            'search' => 'nullable|string|max:255',
        ]);

        $query = LaporanKabel::with('employee:id,full_name');
        $date = $filters['date'] ?? null;
        $wilayah = $filters['wilayah'] ?? null;
        $search = trim((string) ($filters['search'] ?? ''));

        if (filled($date)) {
            $query->whereDate('created_at', Carbon::parse($date)->toDateString());
        }

        if (filled($wilayah)) {
            $query->where('wilayah', $wilayah);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('wilayah', 'like', "%{$search}%")
                    ->orWhere('jenis_kabel', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($eq) use ($search) {
                        $eq->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        $laporanKabel = $query->latest()->paginate(self::PER_PAGE)->appends($request->query());
        $employees = Employee::orderBy('full_name')->get(['id', 'full_name']);

        return view('content.apps.Logistik.laporan-kabel.index', compact('laporanKabel', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'wilayah' => 'required|in:Klaten,Gunung Kidul,Boyolali',
            'employee_id' => 'required|exists:employees,id',
            'alamat' => 'required|string|max:1000',
            'tarikan_meter' => 'required|numeric|min:0',
            'jenis_kabel' => 'required|in:1c,4c,12c',
            'sisa_kabel' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:2000',
        ]);

        LaporanKabel::create([
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'wilayah' => $validated['wilayah'],
            'employee_id' => $validated['employee_id'],
            'alamat' => $validated['alamat'],
            'tarikan_meter' => $validated['tarikan_meter'],
            'jenis_kabel' => $validated['jenis_kabel'],
            // Tetap simpan ke kolom lama agar tidak perlu ubah struktur DB.
            'sisi_core' => $validated['sisa_kabel'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('logistik.laporan-kabel.index')
            ->with('success', 'Laporan kabel berhasil ditambahkan.');
    }

    public function update(Request $request, LaporanKabel $laporanKabel)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'wilayah' => 'required|in:Klaten,Gunung Kidul,Boyolali',
            'employee_id' => 'required|exists:employees,id',
            'alamat' => 'required|string|max:1000',
            'tarikan_meter' => 'required|numeric|min:0',
            'jenis_kabel' => 'required|in:1c,4c,12c',
            'sisa_kabel' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:2000',
        ]);

        $laporanKabel->update([
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'wilayah' => $validated['wilayah'],
            'employee_id' => $validated['employee_id'],
            'alamat' => $validated['alamat'],
            'tarikan_meter' => $validated['tarikan_meter'],
            'jenis_kabel' => $validated['jenis_kabel'],
            'sisi_core' => $validated['sisa_kabel'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('logistik.laporan-kabel.index');
    }

    public function destroy(LaporanKabel $laporanKabel)
    {
        $laporanKabel->delete();

        return redirect()->route('logistik.laporan-kabel.index');
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->validate([
            'date' => 'nullable|date',
            'wilayah' => 'nullable|in:Klaten,Gunung Kidul,Boyolali',
            'search' => 'nullable|string|max:255',
        ]);

        $query = LaporanKabel::with('employee:id,full_name');
        $date = $filters['date'] ?? null;
        $wilayah = $filters['wilayah'] ?? null;
        $search = trim((string) ($filters['search'] ?? ''));

        if (filled($date)) {
            $query->whereDate('created_at', Carbon::parse($date)->toDateString());
        }

        if (filled($wilayah)) {
            $query->where('wilayah', $wilayah);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('wilayah', 'like', "%{$search}%")
                    ->orWhere('jenis_kabel', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($eq) use ($search) {
                        $eq->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        $laporanKabel = $query->latest()->get();

        $pdf = Pdf::loadView('content.apps.Logistik.laporan-kabel.export-pdf', [
            'laporanKabel' => $laporanKabel,
            'date' => $date,
            'wilayah' => $wilayah,
            'search' => $search,
            'printedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-kabel-' . now()->format('Ymd-His') . '.pdf');
    }
}
