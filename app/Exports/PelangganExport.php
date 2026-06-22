<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PelangganExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct()
    {
        // No month/year filter needed, exporting all time
    }

    public function sheets(): array
    {
        // Base condition: hanya pelanggan yang statusnya approve
        $baseCondition = function ($q) {
            $q->where('status', 'approve');
        };

        // Ambil SEMUA pelanggan berdasarkan tanggal data dibuat sebagai tanggal daftar.
        $pelangganAll = Pelanggan::query()
            ->with(['paket:id,nama_paket,kecepatan,harga'])
            ->where($baseCondition)
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        // Group by tanggal daftar (created_at)
        $groupedByDate = $pelangganAll->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });

        $sheets = [];

        // Buat sheet per tanggal
        foreach ($groupedByDate as $date => $items) {
            // Hitung total kumulatif: semua pelanggan dari awal sampai tanggal daftar ini.
            $totalSampaiTanggal = Pelanggan::where($baseCondition)
                ->whereNotNull('created_at')
                ->where('created_at', '<=', Carbon::parse($date)->endOfDay())
                ->count();

            $sheets[] = new PelangganDateSheetExport($date, $items, $totalSampaiTanggal);
        }

        // Jika tidak ada data, buat sheet kosong
        if (empty($sheets)) {
            $dateLabel = now()->format('Y-m-d');
            $sheets[] = new PelangganDateSheetExport($dateLabel, collect(), 0);
        }

        return $sheets;
    }
}
