<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use App\Models\DailySalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Profil Pegawai tidak ditemukan. Silakan hubungi Administrator.');
        }

        $today = Carbon::today();

        // Today's attendance
        $attendance = DailyAttendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        // Check if forgot to clock out yesterday
        $yesterday = Carbon::yesterday();
        $forgotClockOut = DailyAttendance::where('employee_id', $employee->id)
            ->whereDate('date', $yesterday)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->exists();

        // Today's salary
        $todaySalary = DailySalary::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        // Stats for current month
        $currentMonth = $today->month;
        $currentYear = $today->year;

        $hadirCount = DailyAttendance::where('employee_id', $employee->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Hadir')
            ->count();

        $izinCount = DailyAttendance::where('employee_id', $employee->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Izin')
            ->where('approval_status', 'Done')
            ->count();

        $cutiCount = DailyAttendance::where('employee_id', $employee->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Cuti')
            ->where('approval_status', 'Done')
            ->count();

        $stats = [
            'hadir' => $hadirCount,
            'izin' => $izinCount,
            'cuti' => $cutiCount,
        ];

        // Recent Activity (last 5 records)
        $activities = DailyAttendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($record) {
                // Determine icon and color based on status and clock in/out
                $icon = 'fa-right-to-bracket';
                $color = 'bg-emerald-100 text-emerald-600';
                $label = 'Clock In';
                $time = $record->check_in_time ? Carbon::parse($record->check_in_time)->format('H:i') : '–';
                $statusColor = 'text-emerald-600';

                if ($record->status == 'Izin' || $record->status == 'Cuti' || $record->status == 'Sakit') {
                    $icon = 'fa-file-lines';
                    $color = 'bg-amber-100 text-amber-600';
                    $label = $record->status . ' Diajukan';
                    $time = '–';
                    $statusColor = $record->approval_status == 'Pending' ? 'text-amber-600' : ($record->approval_status == 'Done' ? 'text-emerald-600' : 'text-red-600');
                } else if ($record->check_out_time) {
                    $icon = 'fa-right-from-bracket';
                    $color = 'bg-blue-100 text-blue-600';
                    $label = 'Clock Out';
                    $time = Carbon::parse($record->check_out_time)->format('H:i');
                } else if ($record->status == 'Terlambat') {
                    $color = 'bg-amber-100 text-amber-600';
                    $statusColor = 'text-amber-600';
                }

                $dateStr = '';
                if ($record->date->isToday()) {
                    $dateStr = 'Hari ini';
                } else if ($record->date->isYesterday()) {
                    $dateStr = 'Kemarin';
                } else {
                    $dateStr = $record->date->diffForHumans();
                }

                return [
                    'icon' => $icon,
                    'color' => $color,
                    'time' => $time,
                    'label' => $label,
                    'date' => $dateStr,
                    'status' => $record->status,
                    'statusColor' => $statusColor,
                ];
            });

        return view('employee.dashboard', compact('attendance', 'stats', 'activities', 'forgotClockOut', 'todaySalary'));
    }
}
