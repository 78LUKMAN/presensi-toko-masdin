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
        
        // Use shared scope so dashboard count matches the Presensi Bermasalah page exactly
        $problematicCount = DailyAttendance::problematic()->count();

        $absentToday = DailyAttendance::whereDate('date', $today)->where('status', 'Tidak Hadir')->count();

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
            'problematicCount',
            'absentToday',
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

    public function generateToken()
    {
        // Clean up expired tokens
        \App\Models\QrToken::where('expires_at', '<', now())->delete();

        $token = \Illuminate\Support\Str::random(32);
        $expiresAt = now()->addSeconds(30); // Valid for 30 seconds

        \App\Models\QrToken::create([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'success' => true,
            'token' => $token,
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function attendanceChartData(Request $request)
    {
        $days = $request->get('days', 7);
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($days - 1);
        
        $dates = [];
        $labels = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dates[] = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('d M');
        }
        
        $records = DailyAttendance::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
            
        $presentData = [];
        $absentData = [];
        $lateData = [];
        $leaveData = [];
        
        foreach ($dates as $date) {
            $dayRecords = $records->filter(function($item) use ($date) {
                return $item->date->format('Y-m-d') == $date;
            });
            $presentData[] = $dayRecords->where('status', 'Hadir')->count();
            $absentData[] = $dayRecords->where('status', 'Tidak Hadir')->count();
            $lateData[] = $dayRecords->where('status', 'Terlambat')->count();
            $leaveData[] = $dayRecords->whereIn('status', ['Izin', 'Cuti', 'Sakit'])->count();
        }
        
        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $presentData,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $lateData,
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label' => 'Izin/Cuti',
                    'data' => $leaveData,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $absentData,
                    'backgroundColor' => '#ef4444',
                ],
            ]
        ]);
    }
}
