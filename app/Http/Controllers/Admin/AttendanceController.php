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
        $query = DailyAttendance::with('employee')->whereNotIn('status', ['Izin', 'Sakit', 'Cuti']);

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
            ->addColumn('id_pegawai', fn($row) => $row->employee->noreg ?? '-')
            ->addColumn('nama', fn($row) => $row->employee->name ?? '-')
            ->addColumn('bagian', fn($row) => $row->employee->section ?? '-')
            ->filterColumn('id_pegawai', function($query, $keyword) {
                $query->whereHas('employee', function($q) use ($keyword) {
                    $q->where('noreg', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nama', function($query, $keyword) {
                $query->whereHas('employee', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('bagian', function($query, $keyword) {
                $query->whereHas('employee', function($q) use ($keyword) {
                    $q->where('section', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('date', fn($row) => $row->date->format('Y-m-d'))
            ->addColumn('masuk', fn($row) => $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('H:i') : '-')
            ->addColumn('pulang', fn($row) => $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('H:i') : '-')
            ->editColumn('total_hours', fn($row) => formatWorkingHours($row->total_hours))
            ->make(true);
    }

    /**
     * Display the problematic attendance page.
     */
    public function problematicIndex()
    {
        // Use shared scope — same query as dashboard and DataTable
        $pendingCount = DailyAttendance::problematic()->count();

        return view('admin.presensi-bermasalah', compact('pendingCount'));
    }

    /**
     * Return JSON data for Problematic Attendance DataTables.
     */
    public function problematicData(Request $request)
    {
        // Use shared scope — same query as dashboard and problematicIndex
        $query = DailyAttendance::with('employee')->problematic();

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
            ->addColumn('pulang', fn($row) => $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('H:i') : '')
            ->addColumn('ket', fn($row) => $row->notes ?: 'Lupa presensi pulang')
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
            
            $diffInMinutes = $checkIn->diffInMinutes($checkOut, false);
            $totalHours = $diffInMinutes < 0 ? null : round($diffInMinutes / 60, 2);

            $attendance->update([
                'check_out_time' => $request->check_out_time,
                'total_hours' => $totalHours,
                'status' => 'Hadir (Manual)',
                'approval_status' => 'Done',
                'notes' => $request->notes ?? 'Disetujui Admin secara manual.',
            ]);

            // 2. Create/Update Daily Salary (only happens on admin approval)
            \App\Models\DailySalary::updateOrCreate(
                [
                    'employee_id' => $attendance->employee_id,
                    'date' => $attendance->date,
                ],
                [
                    'total_hours' => $totalHours,
                    'salary_amount' => $request->salary_amount,
                    'salary_status' => 'manual',
                    'notes' => 'Disetujui admin secara manual',
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
