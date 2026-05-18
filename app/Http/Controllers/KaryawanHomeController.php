<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Employee;
use App\Models\Gaji;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class KaryawanHomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        $user = Auth::user();
        $employee = Employee::where('full_name', $user?->name)->first();
        $today = now()->setTimezone('Asia/Jakarta')->toDateString();
        $todayAttendance = Absensi::where('user_id', $user?->id)
            ->whereDate('date', $today)
            ->first();
        $jabatan = $employee?->jabatan ?: $this->formatRoleLabel($user?->role);

        $todayStr = now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $activeJobsCount = \App\Models\Ticket::where('user_id', $user?->id)
            ->whereIn('status', ['pending', 'assigned', 'progress'])
            ->where(function($q) use ($todayStr) {
                $q->whereDate('assignment_date', $todayStr)
                  ->orWhere(function($subQ) use ($todayStr) {
                      $subQ->whereNull('assignment_date')
                           ->whereDate('created_at', $todayStr);
                  });
            })
            ->count();

        return view('content.apps.Karyawan.home.index', [
            'userName' => $user?->name ?? 'Staff',
            'jabatan' => $jabatan,
            'hariIni' => now()->translatedFormat('l'),
            'tanggalHariIni' => now()->translatedFormat('j F Y'),
            'jamKerja' => '07.45 - 16.00 WIB',
            'timeIn' => $todayAttendance?->time_in?->setTimezone('Asia/Jakarta')->format('H:i:s') ?? '--:--:--',
            'timeOut' => $todayAttendance?->time_out?->setTimezone('Asia/Jakarta')->format('H:i:s') ?? '--:--:--',
            'activeJobsCount' => $activeJobsCount,
        ]);
    }

    public function lembur()
    {
        $user = Auth::user();
        $today = now()->setTimezone('Asia/Jakarta')->toDateString();
        $todayAttendance = Absensi::where('user_id', $user?->id)
            ->whereDate('date', $today)
            ->first();

        $lemburList = Absensi::where('user_id', $user?->id)
            ->whereNotNull('lembur_in')
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get();

        return view('content.apps.Karyawan.home.lembur', [
            'lemburIn' => $todayAttendance?->lembur_in?->setTimezone('Asia/Jakarta')->format('H:i:s') ?? '--:--:--',
            'lemburOut' => $todayAttendance?->lembur_out?->setTimezone('Asia/Jakarta')->format('H:i:s') ?? '--:--:--',
            'lemburList' => $lemburList,
        ]);
    }

    public function slipGaji()
    {
        $user = Auth::user();
        $employee = Employee::where('full_name', $user?->name)->first();
        $salaries = collect();

        if ($employee) {
            $salaries = Gaji::where('employee_id', $employee->id)
                ->latest()
                ->get();
        }

        return view('content.apps.Karyawan.home.slip-gaji', [
            'userName' => $user?->name ?? 'Staff',
            'employee' => $employee,
            'salaries' => $salaries,
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        $employee = Employee::where('full_name', $user?->name)->first();

        return view('content.apps.Karyawan.home.profile', [
            'user' => $user,
            'employee' => $employee,
        ]);
    }

    private function formatRoleLabel(?string $role): string
    {
        if (!$role) {
            return 'Karyawan';
        }

        return ucwords(str_replace('_', ' ', strtolower($role)));
    }
}
