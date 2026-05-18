<?php

namespace App\Exports;

use App\Models\Tagihan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TagihanBulanLaluExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $targetBulan;
    protected $targetTahun;

    public function __construct($filterBulan, $filterTahun)
    {
        // Filter April (4) -> Target Maret (3)
        $date = Carbon::createFromDate($filterTahun, $filterBulan, 1)->subMonth();
        $this->targetBulan = $date->month;
        $this->targetTahun = $date->year;
    }

    public function collection()
    {
        return Tagihan::with(['pelanggan', 'paket', 'rekening'])
            ->whereMonth('tanggal_mulai', $this->targetBulan)
            ->whereYear('tanggal_mulai', $this->targetTahun)
            ->where('status_pembayaran', 'lunas')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. ID PELANGGAN',
            'NAMA LENGKAP',
            'NAMA PAKET',
            'HARGA PAKET',
            'TANGGAL MULAI',
            'JATUH TEMPO',
            'STATUS PEMBAYARAN',
            'TANGGAL PEMBAYARAN',
            'KETERANGAN (BULAN LALU)'
        ];
    }

    public function map($tagihan): array
    {
        static $no = 0;
        $no++;

        $billingMonth = Carbon::createFromDate($this->targetTahun, $this->targetBulan, 1);
        $bulanLabel = $billingMonth->translatedFormat('F Y');

        // Cutoff adalah tanggal 7 bulan berikutnya
        $cutoff = $billingMonth->copy()->addMonth()->day(7)->endOfDay();

        $paymentDate = $tagihan->tanggal_pembayaran 
            ? Carbon::parse($tagihan->tanggal_pembayaran)->endOfDay() 
            : now();

        // Jika belum bayar, atau bayar lewat dari tanggal 7 bulan depannya -> Outstanding
        if ($tagihan->status_pembayaran !== 'lunas' || $paymentDate->greaterThan($cutoff)) {
            $keterangan = 'Outstanding ' . $bulanLabel;
        } else {
            $keterangan = 'Pembayaran ' . $bulanLabel;
        }

        return [
            $no,
            $tagihan->pelanggan->nomer_id ?? '-',
            $tagihan->pelanggan->nama_lengkap ?? '-',
            $tagihan->paket->nama_paket ?? '-',
            (float) ($tagihan->paket->harga ?? 0),
            $tagihan->tanggal_mulai ? Carbon::parse($tagihan->tanggal_mulai)->format('d/m/Y') : '-',
            $tagihan->tanggal_berakhir ? Carbon::parse($tagihan->tanggal_berakhir)->format('d/m/Y') : '-',
            strtoupper($tagihan->status_pembayaran),
            $tagihan->tanggal_pembayaran ? Carbon::parse($tagihan->tanggal_pembayaran)->format('d/m/Y H:i') : '-',
            $keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("E2:E{$highestRow}")->getNumberFormat()->setFormatCode('"Rp "0');

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '696CFF']],
            ],
        ];
    }
}
