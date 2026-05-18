<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SemuaLunasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tahun;

    public function __construct($tahun = null)
    {
        $this->tahun = $tahun ?: date('Y');
    }

    public function collection()
    {
        // Ambil semua pelanggan beserta tagihan Lunas di tahun terpilih
        return Pelanggan::with(['paket', 'tagihans' => function($query) {
            $query->where('status_pembayaran', 'lunas')
                  ->whereYear('tanggal_mulai', $this->tahun)
                  ->orderBy('tanggal_mulai', 'asc');
        }])
        ->whereHas('tagihans', function($query) {
            $query->where('status_pembayaran', 'lunas')
                  ->whereYear('tanggal_mulai', $this->tahun);
        })
        ->orderBy('nama_lengkap', 'asc')
        ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'NOMER ID',
            'ALAMAT',
            'JUMLAH LANGGANAN',
            'PEMBAYARAN BULAN'
        ];
    }

    public function map($pelanggan): array
    {
        static $no = 0;
        $no++;

        // Kumpulkan bulan apa saja yang dibayar
        $bulanDibayar = [];
        foreach ($pelanggan->tagihans as $tagihan) {
            if ($tagihan->tanggal_mulai) {
                // Konversi tanggal_mulai ke format bulan
                $bulan = Carbon::parse($tagihan->tanggal_mulai)->locale('id')->translatedFormat('F');
                if (!in_array($bulan, $bulanDibayar)) {
                    $bulanDibayar[] = $bulan;
                }
            }
        }
        $stringBulan = implode(', ', $bulanDibayar);

        // Gabungkan alamat
        $alamat = $pelanggan->alamat_jalan;
        if ($pelanggan->desa) $alamat .= ' ' . $pelanggan->desa;
        if ($pelanggan->kecamatan) $alamat .= ', ' . $pelanggan->kecamatan;

        return [
            $no,
            $pelanggan->nama_lengkap ?? '-',
            $pelanggan->nomer_id ?? '-',
            $alamat ?? '-',
            $pelanggan->paket ? 'Rp ' . number_format($pelanggan->paket->harga, 0, ',', '.') : '-',
            $stringBulan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ]
        ];
    }
}
