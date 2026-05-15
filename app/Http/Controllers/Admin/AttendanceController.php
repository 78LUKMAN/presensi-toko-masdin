<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display the attendance recap page.
     */
    public function index()
    {
        return view('admin.rekap-presensi');
    }

    /**
     * Return JSON data for Attendance Recap DataTables.
     */
    public function data(Request $request)
    {
        $query = DailyAttendance::with('employee');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->filled('name')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('id_karyawan', fn($row) => $row->employee->noreg ?? '-')
            ->addColumn('nama', fn($row) => $row->employee->name ?? '-')
            ->addColumn('bagian', fn($row) => $row->employee->section ?? '-')
            ->editColumn('date', fn($row) => $row->date->format('Y-m-d'))
            ->addColumn('masuk', fn($row) => $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('H:i') : '-')
            ->addColumn('pulang', fn($row) => $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('H:i') : '-')
            ->editColumn('total_hours', fn($row) => $row->total_hours . 'j')
            ->make(true);
    }

    /**
     * Display the problematic attendance page.
     */
    public function problematicIndex()
    {
        return view('admin.presensi-bermasalah');
    }

    /**
     * Return JSON data for Problematic Attendance DataTables.
     */
    public function problematicData(Request $request)
    {
        // Define problematic as: check_out_time is null AND approval_status is Pending
        $query = DailyAttendance::with('employee')
            ->whereNull('check_out_time')
            ->where('approval_status', 'Pending');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->filled('name')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('date', fn($row) => $row->date->format('Y-m-d'))
            ->addColumn('nama', fn($row) => $row->employee->name ?? '-')
            ->addColumn('bagian', fn($row) => $row->employee->section ?? '-')
            ->addColumn('masuk', fn($row) => $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('H:i') : '-')
            ->addColumn('ket', fn($row) => $row->notes ?: 'Lupa absen pulang')
            ->addColumn('status', fn($row) => $row->approval_status)
            ->addColumn('aksi', function ($row) {
                return $row; // Return full row for modal
            })
            ->make(true);
    }

    /**
     * Approve a problematic attendance record by setting clock-out and salary.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'check_out_time' => 'required',
            'salary_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($request, $id) {
            $attendance = DailyAttendance::findOrFail($id);
            
            // 1. Update Attendance
            $checkIn = \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->check_in_time);
            $checkOut = \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $request->check_out_time);
            
            $diffInMinutes = $checkOut->diffInMinutes($checkIn);
            $totalHours = round($diffInMinutes / 60, 2);

            $attendance->update([
                'check_out_time' => $request->check_out_time,
                'total_hours' => $totalHours,
                'status' => 'Hadir (Manual)',
                'approval_status' => 'Done',
                'notes' => $request->notes ?? 'Disetujui Admin secara manual.',
            ]);

            // 2. Create/Update Daily Salary
            \App\Models\DailySalary::updateOrCreate(
                [
                    'employee_id' => $attendance->employee_id,
                    'date' => $attendance->date,
                ],
                [
                    'total_hours' => $totalHours,
                    'salary_amount' => $request->salary_amount,
                ]
            );

            // 3. Add Log for Check-out
            \Illuminate\Support\Facades\DB::table('attendance_logs')->insert([
                'employee_id' => $attendance->employee_id,
                'timestamp' => $checkOut,
                'type' => 'out',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Presensi berhasil diselesaikan dan gaji telah diinput.']);
    }
}
