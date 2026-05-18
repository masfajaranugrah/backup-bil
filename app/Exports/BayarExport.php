<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BayarExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $search;
    protected $status;
    protected $bulan;
    protected $tahun;
    protected $bank;
    protected $totalsByBank = [];
    protected $totalAll = 0;

    public function __construct(
        $search = null,
        $status = null,
        $bulan = null,
        $tahun = null,
        $bank = null
    )
    {
        $this->search = $search;
        $this->status = $status;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->bank = $bank;

        $this->buildTotals();
    }

    /**
     * Data export
     */
    public function collection()
    {
        $query = Tagihan::with(['pelanggan', 'paket', 'rekening'])
            ->leftJoin('rekenings', 'rekenings.id', '=', 'tagihans.type_pembayaran')
            ->select('tagihans.*');

        if ($this->status) {
            $query->where('status_pembayaran', $this->status);
        }

        if ($this->bulan) {
            $query->whereMonth('tagihans.tanggal_mulai', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('tagihans.tanggal_mulai', $this->tahun);
        }

        if ($this->bank) {
            $query->where('tagihans.type_pembayaran', $this->bank);
        }

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('pelanggan', function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nomer_id', 'LIKE', "%{$search}%")
                  ->orWhere('no_whatsapp', 'LIKE', "%{$search}%");
            });
        }

        $tagihans = $query->orderBy('tagihans.tanggal_mulai', 'asc')->get();

        // Group by pelanggan_id
        $grouped = $tagihans->groupBy('pelanggan_id')->map(function ($group) {
            $first = $group->first();
            
            // Collect all paid months
            $months = $group->map(function ($t) {
                return $t->tanggal_mulai ? \Carbon\Carbon::parse($t->tanggal_mulai)->locale('id')->translatedFormat('M') : '-';
            })->unique()->implode(', ');
            
            // Total payment for these months
            $totalBayar = $group->sum(function ($t) {
                return (float) ($t->paket->harga ?? $t->harga ?? 0);
            });

            // Catatan combined
            $catatan = $group->map(function($t) { return $t->catatan; })->filter()->implode(' | ');

            return (object) [
                'nomer_id' => $first->pelanggan->nomer_id ?? '-',
                'nama_lengkap' => $first->pelanggan->nama_lengkap ?? '-',
                'no_whatsapp' => $first->pelanggan->no_whatsapp ?? '-',
                'nama_paket' => $first->paket->nama_paket ?? '-',
                'total_harga' => $totalBayar,
                'kecepatan' => ($first->paket->kecepatan ?? '-') . ' Mbps',
                'bulan_lunas' => $months,
                'status_pembayaran' => strtoupper($first->status_pembayaran ?? 'LUNAS'),
                'type_pembayaran' => $group->map(function($t) { return $t->rekening->nama_bank ?? '-'; })->unique()->implode(', '),
                'catatan' => $catatan ?: '-',
            ];
        })->values();

        return $grouped;
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'NO',
            'NO. ID PELANGGAN',
            'NAMA LENGKAP',
            'NO. WHATSAPP',
            'NAMA PAKET',
            'TOTAL BAYAR',
            'KECEPATAN',
            'BULAN LUNAS',
            'STATUS PEMBAYARAN',
            'TYPE PEMBAYARAN',
            'CATATAN'
        ];
    }

    /**
     * Mapping data ke Excel
     * ?? HARGA DIKIRIM SEBAGAI ANGKA (BISA DI SUM)
     */
    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->nomer_id,
            $row->nama_lengkap,
            $row->no_whatsapp,
            $row->nama_paket,
            
            // ANGKA MURNI (INI KUNCI SUPAYA SUM BISA)
            (float) $row->total_harga,
            
            $row->kecepatan,
            $row->bulan_lunas,
            $row->status_pembayaran,
            $row->type_pembayaran,
            $row->catatan
        ];
    }

    /**
     * Styling Excel
     */
    public function styles(Worksheet $sheet)
    {
        // ?? FORMAT RUPIAH TANPA KOMA & TITIK
        $highestRow = $sheet->getHighestRow();

        // Kolom F = HARGA PAKET
        $sheet->getStyle("F2:F{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('"Rp "0');

        return [
            // Header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '696CFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Tambahkan ringkasan total per bank di bawah data.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $dataLastRow = $sheet->getHighestRow();
                $startRow = $dataLastRow + 2;

                $sheet->setCellValue("A{$startRow}", 'RINGKASAN TOTAL PER BANK');
                $sheet->mergeCells("A{$startRow}:E{$startRow}");
                $sheet->getStyle("A{$startRow}")
                    ->getFont()->setBold(true);

                $row = $startRow + 1;
                foreach ($this->totalsByBank as $bank => $total) {
                    $sheet->setCellValue("A{$row}", $bank);
                    $formattedTotal = 'Rp ' . number_format((float) $total, 0, ',', '.');
                    $sheet->setCellValueExplicit("B{$row}", $formattedTotal, DataType::TYPE_STRING);
                    $row++;
                }

                // Total keseluruhan
                $sheet->setCellValue("A{$row}", 'Total Semua Bank');
                $formattedTotalAll = 'Rp ' . number_format((float) $this->totalAll, 0, ',', '.');
                $sheet->setCellValueExplicit("B{$row}", $formattedTotalAll, DataType::TYPE_STRING);
                $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);

                // Border ringkasan
                $sheet->getStyle("A{$startRow}:B{$row}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }

    /**
     * Hitung total per bank sesuai filter export.
     */
    private function buildTotals(): void
    {
        $query = Tagihan::leftJoin('rekenings', 'rekenings.id', '=', 'tagihans.type_pembayaran')
            ->leftJoin('pakets', 'pakets.id', '=', 'tagihans.paket_id')
            ->selectRaw('COALESCE(rekenings.nama_bank, "Tanpa Bank") as bank, SUM(COALESCE(tagihans.harga, pakets.harga, 0)) as total');

        if ($this->status) {
            $query->where('tagihans.status_pembayaran', $this->status);
        }

        if ($this->bulan) {
            $query->whereMonth('tagihans.tanggal_mulai', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('tagihans.tanggal_mulai', $this->tahun);
        }

        if ($this->bank) {
            $query->where('tagihans.type_pembayaran', $this->bank);
        }

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('pelanggan', function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nomer_id', 'LIKE', "%{$search}%")
                  ->orWhere('no_whatsapp', 'LIKE', "%{$search}%");
            });
        }

        $this->totalsByBank = $query->groupBy('bank')->pluck('total', 'bank')->toArray();
        $this->totalAll = array_sum($this->totalsByBank);
    }
}
