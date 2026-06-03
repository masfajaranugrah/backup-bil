<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LedgerDaily extends Model
{
    protected $table = 'ledger_dailies';

    protected $fillable = ['tanggal', 'total_masuk', 'total_keluar', 'saldo'];

    public $timestamps = true;

    public $incrementing = false; // Karena UUID bukan auto-increment

    protected $keyType = 'string'; // UUID berupa string

    // Generate UUID otomatis saat membuat record baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Recalculate ledger_dailies untuk tanggal tertentu.
     * Method ini akan menghitung ulang total_masuk dari tabel incomes
     * dan total_keluar dari tabel expenses, lalu update/create record di ledger_dailies.
     *
     * Dipanggil otomatis oleh model events pada Income dan Expense.
     *
     * @param string|Carbon $tanggal Tanggal yang akan dihitung ulang
     * @return LedgerDaily
     */
    public static function recalculateForDate($tanggal): self
    {
        $dateString = $tanggal instanceof Carbon
            ? $tanggal->toDateString()
            : Carbon::parse($tanggal)->toDateString();

        $totalMasuk = Income::whereDate('tanggal_masuk', $dateString)->sum('jumlah');
        $totalKeluar = Expense::whereDate('tanggal_keluar', $dateString)->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        $ledger = self::firstOrCreate(['tanggal' => $dateString]);
        $ledger->total_masuk = $totalMasuk;
        $ledger->total_keluar = $totalKeluar;
        $ledger->saldo = $saldo;
        $ledger->save();

        return $ledger;
    }
}
