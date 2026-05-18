<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TagihanBelumLunasMonthlySheetExport implements FromCollection, WithMapping, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $period;
    protected $items;

    public function __construct(string $period, Collection $items)
    {
        $this->period = $period;
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function map($tagihan): array
    {
        return [
            $tagihan->pelanggan->nomer_id ?? '-',
            $tagihan->pelanggan->nama_lengkap ?? '-',
            $tagihan->pelanggan->alamat_jalan ?? '-',
            $tagihan->pelanggan->rt ?? '-',
            $tagihan->pelanggan->rw ?? '-',
            $tagihan->pelanggan->desa ?? '-',
            $tagihan->pelanggan->kecamatan ?? '-',
            $tagihan->pelanggan->kabupaten ?? '-',
            $tagihan->pelanggan->provinsi ?? '-',
            $tagihan->pelanggan->kode_pos ?? '-',
            $tagihan->paket->nama_paket ?? '-',
            (float) ($tagihan->harga ?? $tagihan->paket->harga ?? 0),
            $tagihan->paket->kecepatan ?? '-',
            $tagihan->tanggal_mulai ?: '-',
            $tagihan->tanggal_berakhir ?: '-',
            ucfirst($tagihan->status_pembayaran ?? '-'),
            $tagihan->bukti_pembayaran ? asset('storage/' . $tagihan->bukti_pembayaran) : '-',
            $tagihan->catatan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor ID', 'Nama Lengkap', 'Alamat Jalan', 'RT', 'RW',
            'Desa', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Kode Pos',
            'Paket', 'Harga', 'Kecepatan',
            'Tanggal Mulai', 'Tanggal Berakhir',
            'Status Pembayaran', 'Bukti Pembayaran', 'Catatan',
        ];
    }

    public function title(): string
    {
        $date = Carbon::createFromFormat('Y-m', $this->period)->locale('id');
        $title = $date->translatedFormat('M Y');
        $title = str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $title);
        return substr($title, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'L' => [
                'numberFormat' => [
                    'formatCode' => '"Rp "#,##0_-',
                ],
            ],
            1 => ['font' => ['bold' => true]],
        ];
    }
}
