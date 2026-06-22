<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelangganProgressExport implements FromCollection, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $search = null,
        private readonly ?string $status = null,
    )
    {
    }

    public function collection(): Collection
    {
        $pelanggan = Pelanggan::query()
            ->with(['paket:id,nama_paket,kecepatan,harga', 'user:id,name'])
            ->where(function ($query): void {
                $query->whereIn('status', ['pending', 'proses'])
                    ->orWhereNull('progres')
                    ->orWhere('progres', '')
                    ->orWhere('progres', Pelanggan::PROGRES_BELUM_DIPROSES)
                    ->orWhere('progres', Pelanggan::PROGRES_TARIK_KABEL)
                    ->orWhere('progres', Pelanggan::PROGRES_AKTIVASI);
            })
            ->when($this->status, function ($query): void {
                if ($this->status === 'proses') {
                    $query->where('status', 'pending');

                    return;
                }

                $query->where('status', $this->status);
            })
            ->when($this->search, function ($query): void {
                $search = $this->search;
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('nomer_id', 'LIKE', "%{$search}%")
                        ->orWhere('nama_lengkap', 'LIKE', "%{$search}%")
                        ->orWhere('no_whatsapp', 'LIKE', "%{$search}%")
                        ->orWhere('alamat_jalan', 'LIKE', "%{$search}%")
                        ->orWhere('kecamatan', 'LIKE', "%{$search}%")
                        ->orWhere('kabupaten', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%")
                        ->orWhere('progres', 'LIKE', "%{$search}%")
                        ->orWhere('rt', 'LIKE', "%{$search}%")
                        ->orWhere('rw', 'LIKE', "%{$search}%");
                });
            })
            ->orderByRaw('FIELD(progres, ?, ?, ?, ?) ASC', [
                Pelanggan::PROGRES_BELUM_DIPROSES,
                Pelanggan::PROGRES_TARIK_KABEL,
                Pelanggan::PROGRES_AKTIVASI,
                Pelanggan::PROGRES_REGISTRASI,
            ])
            ->latest()
            ->get();

        return $pelanggan->values()->map(function (Pelanggan $item, int $index): array {
            return [
                $index + 1,
                $item->nomer_id ?? '-',
                $item->nama_lengkap ?? '-',
                $item->no_whatsapp ?? '-',
                $this->formatAlamat($item),
                $item->paket->nama_paket ?? '-',
                $item->paket->kecepatan ?? '-',
                $item->status ? ucfirst($item->status) : '-',
                $item->progres ?: Pelanggan::PROGRES_BELUM_DIPROSES,
                $item->progress_note ?: '-',
                $item->user->name ?? '-',
                optional($item->created_at)->format('d/m/Y H:i') ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'ID PELANGGAN',
            'NAMA LENGKAP',
            'NO. WHATSAPP',
            'ALAMAT',
            'PAKET',
            'KECEPATAN',
            'STATUS',
            'TAHAP PROGRESS',
            'CATATAN PROGRESS',
            'MARKETING/USER',
            'TANGGAL DAFTAR',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '18181B'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setWrapText(true);
                $sheet->getStyle("J2:J{$lastRow}")->getAlignment()->setWrapText(true);
                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:{$lastColumn}1");
            },
        ];
    }

    private function formatAlamat(Pelanggan $pelanggan): string
    {
        $parts = array_filter([
            $pelanggan->alamat_jalan,
            ($pelanggan->rt || $pelanggan->rw) ? 'RT ' . ($pelanggan->rt ?: '-') . '/RW ' . ($pelanggan->rw ?: '-') : null,
            $pelanggan->desa,
            $pelanggan->kecamatan,
            $pelanggan->kabupaten,
            $pelanggan->provinsi,
        ]);

        return $parts ? implode(', ', $parts) : '-';
    }
}
