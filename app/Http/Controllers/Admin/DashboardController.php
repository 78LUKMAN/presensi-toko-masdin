<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Employee;
use App\Models\Admin;
use App\Models\DailyAttendance;
use App\Models\MonthlySalary;
use App\Models\DailySalary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = Employee::count();
        $totalAdmins = Admin::count() ?? 3; // fallback if admins table logic differs
        
        $today = Carbon::today();
        
        // Attendance stats
        $presentToday = DailyAttendance::whereDate('date', $today)->where('status', 'Hadir')->count();
        $attendancePercentage = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0;
        
        $problematicCount = DailyAttendance::whereDate('date', $today)
            ->where(function($q) {
                $q->where('status', '!=', 'Hadir')
                  ->orWhereNotNull('notes')
                  ->orWhere('approval_status', 'Pending');
            })->count();

        $absentToday = DailyAttendance::whereDate('date', $today)->where('status', 'Tidak Hadir')->count();

        // Work days this month (simple logic, assuming 22 working days minus holidays)
        // Can be refined later based on actual work calendar.
        $workDaysThisMonth = 22;

        // Total salary this month
        $currentMonth = $today->month;
        $currentYear = $today->year;
        $totalSalaryThisMonth = DailySalary::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('salary_amount') ?? 0;

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalAdmins',
            'presentToday',
            'attendancePercentage',
            'problematicCount',
            'absentToday',
            'workDaysThisMonth',
            'totalSalaryThisMonth'
        ));
    }

    public function todayAttendanceData(Request $request)
    {
        $today = Carbon::today();
        $query = DailyAttendance::with('employee')->whereDate('date', $today);

        return response()->json([
            'data' => $query->get()->map(function($record) {
                return [
                    'nama' => $record->employee->name ?? '-',
                    'bagian' => $record->employee->section ?? '-',
                    'masuk' => $record->check_in_time ? Carbon::parse($record->check_in_time)->format('H:i') : '–',
                    'status' => $record->status
                ];
            })
        ]);
    }

    public function karyawan()
    {
        return view('admin.karyawan');
    }

    public function rekapPresensi()
    {
        return view('admin.rekap-presensi');
    }

    public function presensiRermasalah()
    {
        return view('admin.presensi-bermasalah');
    }

    public function rekapGajiHarian()
    {
        return view('admin.rekap-gaji-harian');
    }

    public function rekapGajiBulanan()
    {
        return view('admin.rekap-gaji-bulanan');
    }

    public function daftarAdmin()
    {
        return view('admin.daftar-admin');
    }

    public function pengaturan()
    {
        return view('admin.pengaturan');
    }

    public function halamanAbsensi()
    {
        return view('admin.halaman-absensi');
    }

    public function halamanAbsensiData()
    {
        $today = Carbon::today();
        
        // Fetch all employees and their attendance for today
        $employees = Employee::with(['attendances' => function($q) use ($today) {
            $q->whereDate('date', $today);
        }])->get();

        $data = $employees->map(function($emp) {
            $attendance = $emp->attendances->first();
            return [
                'id' => $emp->noreg ?? '-',
                'nama' => $emp->name,
                'masuk' => $attendance && $attendance->check_in_time ? Carbon::parse($attendance->check_in_time)->format('H:i') : null,
                'pulang' => $attendance && $attendance->check_out_time ? Carbon::parse($attendance->check_out_time)->format('H:i') : null,
            ];
        })->sortBy('masuk')->values(); // sort by jam masuk if needed, or keeping it as is

        return response()->json($data);
    }
}
