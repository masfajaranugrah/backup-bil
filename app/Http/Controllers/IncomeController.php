<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\LedgerDaily;
use App\Models\Rekening;
use App\Exports\IncomeExport;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Filter Month & Year (Default: Current Month)
        $filterMonth = request('filter_month', $today->month);
        $filterYear = request('filter_year', $today->year);
        $filterDate = request('filter_date');

        $query = $this->incomeRowsQuery($filterMonth, $filterYear, $filterDate);

        // Search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', '%' . $search . '%')
                    ->orWhere('nomer_id', 'like', '%' . $search . '%')
                    ->orWhere('nama_paket', 'like', '%' . $search . '%')
                    ->orWhere('tipe_pembayaran', 'like', '%' . $search . '%')
                    ->orWhere('catatan', 'like', '%' . $search . '%');
            });
        }

        $incomes = (clone $query)->orderByDesc('tanggal_pembayaran')->paginate(20)->withQueryString();

        // ── REKAP PER BANK ───────────────────────────────────────────────────
        $bankTotals = (clone $query)
            ->selectRaw('tipe_pembayaran as nama_bank, SUM(jumlah) as total')
            ->groupBy('tipe_pembayaran')
            ->orderByDesc('total')
            ->get();

        // ── TOTAL HARIAN ─────────────────────────────────────────────────────
        $dailyTotals = (clone $query)
            ->selectRaw('DATE(tanggal_pembayaran) as date, SUM(jumlah) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // ── TOTAL BULANAN ─────────────────────────────────────────────────────
        $monthlyTotal = (clone $query)->sum('jumlah') ?? 0;

        $monthLabel = $filterDate
            ? Carbon::parse($filterDate)->locale('id')->isoFormat('D MMMM YYYY')
            : Carbon::createFromDate($filterYear, $filterMonth, 1)->locale('id')->isoFormat('MMMM YYYY');

        return view('content.apps.Laba.masuk.masuk', compact(
            'incomes',
            'bankTotals',
            'today',
            'dailyTotals',
            'filterMonth',
            'filterYear',
            'filterDate',
            'monthlyTotal',
            'monthLabel'
        ));
    }

    private function incomeRowsQuery($filterMonth, $filterYear, $filterDate = null)
    {
        $tagihanRows = DB::table('tagihans')
            ->where('tagihans.status_pembayaran', 'lunas')
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'tagihans.pelanggan_id')
            ->leftJoin('pakets', 'pakets.id', '=', 'tagihans.paket_id')
            ->leftJoin('rekenings', 'rekenings.id', '=', 'tagihans.type_pembayaran')
            ->selectRaw('tagihans.id as id')
            ->selectRaw('tagihans.tanggal_pembayaran as tanggal_pembayaran')
            ->selectRaw('COALESCE(tagihans.harga, pakets.harga, 0) as harga')
            ->selectRaw('tagihans.catatan as catatan')
            ->selectRaw('COALESCE(NULLIF(tagihans.nama_paket, ""), pakets.nama_paket, "-") as nama_paket')
            ->selectRaw('pelanggans.nama_lengkap as nama_pelanggan')
            ->selectRaw('pelanggans.nomer_id as nomer_id')
            ->selectRaw('COALESCE(rekenings.nama_bank, NULLIF(tagihans.type_pembayaran, ""), "cash") as tipe_pembayaran')
            ->selectRaw('COALESCE(tagihans.harga, pakets.harga, 0) as jumlah')
            ->selectRaw('"tagihan" as sumber');

        $manualRows = DB::table('incomes')
            ->whereNull('incomes.tagihan_id')
            ->selectRaw('incomes.id as id')
            ->selectRaw('incomes.tanggal_masuk as tanggal_pembayaran')
            ->selectRaw('incomes.jumlah as harga')
            ->selectRaw('incomes.keterangan as catatan')
            ->selectRaw('incomes.kategori as nama_paket')
            ->selectRaw('COALESCE(NULLIF(incomes.keterangan, ""), incomes.kategori, "Pemasukan Manual") as nama_pelanggan')
            ->selectRaw('"-" as nomer_id')
            ->selectRaw('COALESCE(NULLIF(incomes.tipe_pembayaran, ""), "cash") as tipe_pembayaran')
            ->selectRaw('incomes.jumlah as jumlah')
            ->selectRaw('"income" as sumber');

        if ($filterDate) {
            $tagihanRows->whereDate('tagihans.tanggal_pembayaran', $filterDate);
            $manualRows->whereDate('incomes.tanggal_masuk', $filterDate);
        } else {
            $tagihanRows->whereMonth('tagihans.tanggal_pembayaran', $filterMonth)
                ->whereYear('tagihans.tanggal_pembayaran', $filterYear);

            $manualRows->whereMonth('incomes.tanggal_masuk', $filterMonth)
                ->whereYear('incomes.tanggal_masuk', $filterYear);
        }

        return DB::query()->fromSub($tagihanRows->unionAll($manualRows), 'income_rows');
    }

    public function export(Request $request)
    {
        $today = Carbon::today();
        $filterMonth = $request->input('filter_month', $today->month);
        $filterYear = $request->input('filter_year', $today->year);
        $search = $request->input('search');

        $filename = 'Laba_Masuk_' . Carbon::createFromDate($filterYear, $filterMonth, 1)->format('Y_m') . '.xlsx';

        return Excel::download(new IncomeExport($filterMonth, $filterYear, $search, false), $filename);
    }

    public function exportDedicated(Request $request)
    {
        $today = Carbon::today();
        $filterMonth = $request->input('filter_month', $today->month);
        $filterYear = $request->input('filter_year', $today->year);
        $search = $request->input('search');

        $filename = 'Laba_Masuk_Dedicated_' . Carbon::createFromDate($filterYear, $filterMonth, 1)->format('Y_m') . '.xlsx';

        return Excel::download(new IncomeExport($filterMonth, $filterYear, $search, true), $filename);
    }

    public function exportMonthly(Request $request)
    {
        $today = Carbon::today();
        $filterMonth = $request->input('filter_month', $today->month);
        $filterYear = $request->input('filter_year', $today->year);

        $filename = 'Laba_Masuk_Bulanan_1_Sheet_' . Carbon::createFromDate($filterYear, $filterMonth, 1)->format('Y_m') . '.xlsx';

        return Excel::download(new \App\Exports\IncomeMonthlyExport($filterMonth, $filterYear), $filename);
    }


    public function create()
    {
        $kategori_default = ['Internet', 'Penjualan', 'Piutang', 'DLL'];

        return view('content.apps.Laba.masuk.add-masuk', compact('kategori_default'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'kategori_dll' => 'nullable|string', // untuk DLL input manual
            'tanggal_masuk' => 'nullable|date',   // bisa diisi tanggal bebas
            'tipe_pembayaran' => 'required|string|in:cash,transfer',
        ]);

        // Tentukan kategori final
        $kategori = $request->kategori === 'DLL' && $request->kategori_dll
            ? $request->kategori_dll
            : $request->kategori;

        // Generate kode otomatis berdasarkan kategori
        $kode = $this->getKode($kategori);

        // Convert tanggal_masuk ke Carbon, default sekarang jika kosong
        $tanggalMasuk = $request->tanggal_masuk
            ? \Carbon\Carbon::parse($request->tanggal_masuk)
            : now();

        // Simpan income ke database
        $income = Income::create([
            'kategori' => $kategori,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'kode' => $kode,
            'tipe_pembayaran' => $request->tipe_pembayaran,
            'tanggal_masuk' => $tanggalMasuk,
            'created_at' => $tanggalMasuk,
            'updated_at' => $tanggalMasuk,
        ]);

        // Update ledger harian otomatis sesuai tanggal masuk
        $this->updateLedger($tanggalMasuk->toDateString());

        return redirect()->route('income.index', [
            'filter_date' => $tanggalMasuk->toDateString(),
            'filter_month' => $tanggalMasuk->month,
            'filter_year' => $tanggalMasuk->year,
        ])->with('success', 'Laba Masuk berhasil ditambahkan.');
    }

    /**
     * Update ledger harian otomatis sesuai tanggal.
     * Sekarang menggunakan LedgerDaily::recalculateForDate() yang terpusat.
     * Note: Method ini juga dipanggil otomatis oleh Income model events,
     * tapi tetap tersedia sebagai fallback jika dibutuhkan.
     */
    private function updateLedger($tanggal)
    {
        LedgerDaily::recalculateForDate($tanggal);
    }


    /**
     * Generate kode otomatis per kategori
     */

    private function getKode($kategori)
    {
        return match (strtolower($kategori)) {
            'internet' => '01',
            'penjualan' => '02',
            'piutang' => '03',
            default => 'O4', // DLL atau kategori custom
        };

    }

    public function edit($id)
    {
        $income = Income::findOrFail($id);
        $kategori_default = ['Internet', 'Penjualan', 'Piutang', 'DLL'];

        return view('content.apps.Laba.masuk.edit-masuk', compact('income', 'kategori_default'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'kategori_dll' => 'nullable|string',
            'tanggal_masuk' => 'nullable|date',
        ]);

        $income = Income::findOrFail($id);

        $kategori = $request->kategori === 'DLL' && $request->kategori_dll
            ? $request->kategori_dll
            : $request->kategori;

        // Simpan tanggal lama sebagai string
        $tanggalSebelumnya = Carbon::parse($income->tanggal_masuk)->toDateString();

        // Parse tanggal masuk baru
        $tanggalMasukBaru = $request->tanggal_masuk
            ? Carbon::parse($request->tanggal_masuk)
            : Carbon::parse($income->tanggal_masuk);

        $income->update([
            'kategori' => $kategori,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal_masuk' => $tanggalMasukBaru,
        ]);

        // Update ledger untuk tanggal lama dan tanggal baru
        $this->updateLedger($tanggalSebelumnya);
        $this->updateLedger($tanggalMasukBaru->toDateString());

        return redirect()->route('income.index')->with('success', 'Laba Masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $income = Income::findOrFail($id);

        // Perbaiki: Parse tanggal_masuk ke Carbon terlebih dahulu
        $tanggal = Carbon::parse($income->tanggal_masuk)->toDateString();

        $income->delete();

        $this->updateLedger($tanggal);

        return redirect()->route('income.index')->with('success', 'Laba Masuk berhasil dihapus.');
    }

}
