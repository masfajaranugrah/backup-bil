<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the welcome dashboard page.
     */
    public function index(Request $request)
    {
        $paket = Paket::all();
        
        // Filter Periode
        $periode = $request->periode;
        $filterDate = function($query) use ($periode) {
            if ($periode) {
                $parts = explode('-', $periode);
                if (count($parts) === 2) {
                    $query->whereYear('tanggal_mulai', $parts[0])
                          ->whereMonth('tanggal_mulai', $parts[1]);
                }
            }
        };

        // Status filter for main customers
        $baseCondition = function($q) {
            $q->where('progres', Pelanggan::PROGRES_REGISTRASI)
              ->orWhere('status', 'approve');
        };

        // Statistik
        $totalCustomer = Pelanggan::where($baseCondition)->count();
        
        $customerLunas = Tagihan::where('status_pembayaran', 'lunas')
            ->where($filterDate)
            ->count();
            
        $belumLunas = Tagihan::where('status_pembayaran', 'belum bayar')
            ->where($filterDate)
            ->count();
            
        $totalPaket = $paket->count();

        // Status Active/Inactive
        $activeCustomers = Pelanggan::where($baseCondition)->whereHas('loginStatus', function($q) {
            $q->where('is_active', true);
        })->count();
        
        $inactiveCustomers = Pelanggan::where($baseCondition)->where(function($q) {
            $q->whereHas('loginStatus', function($subQ) {
                $subQ->where('is_active', false);
            })->orWhereDoesntHave('loginStatus');
        })->count();

        return view('content.apps.Dashboard.welcome', [
            'totalCustomer' => $totalCustomer,
            'customerLunas' => $customerLunas,
            'belumLunas' => $belumLunas,
            'totalPaket' => $totalPaket,
            'activeCustomers' => $activeCustomers,
            'inactiveCustomers' => $inactiveCustomers,
        ]);
    }
}
