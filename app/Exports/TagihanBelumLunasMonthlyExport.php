<?php

namespace App\Exports;

use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TagihanBelumLunasMonthlyExport implements WithMultipleSheets
{
    use Exportable;

    protected $search;
    protected $periode;

    public function __construct($search = null, $periode = null)
    {
        $this->search = $search;
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        $rows = $this->queryRows();
        $sheets = [];

        $grouped = $rows->groupBy(function ($row) {
            return Carbon::parse($row->tanggal_mulai)->format('Y-m');
        });

        foreach ($grouped as $period => $items) {
            $sheets[] = new TagihanBelumLunasMonthlySheetExport($period, $items);
        }

        if (empty($sheets)) {
            $fallbackPeriod = $this->periode ?: now()->format('Y-m');
            $sheets[] = new TagihanBelumLunasMonthlySheetExport($fallbackPeriod, collect());
        }

        return $sheets;
    }

    protected function queryRows(): Collection
    {
        return Tagihan::with(['pelanggan', 'paket'])
            ->where('status_pembayaran', 'belum bayar')
            ->when($this->search, function ($query, $search) {
                $query->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('nomer_id', 'like', '%' . $search . '%')
                        ->orWhere('no_whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->when($this->periode, function ($query, $periode) {
                $parts = explode('-', $periode);
                if (count($parts) === 2) {
                    $query->whereYear('tanggal_mulai', (int) $parts[0])
                        ->whereMonth('tanggal_mulai', (int) $parts[1]);
                }
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
    }
}
