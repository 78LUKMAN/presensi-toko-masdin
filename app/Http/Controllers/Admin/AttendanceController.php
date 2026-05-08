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

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('section')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('section', $request->section);
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
        // Define problematic as:
        // status is not 'Hadir' OR notes is not null OR approval_status is Pending
        $query = DailyAttendance::with('employee')
            ->where(function($q) {
                $q->where('status', '!=', 'Hadir')
                  ->orWhereNotNull('notes')
                  ->orWhere('approval_status', 'Pending');
            });

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('section')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('section', $request->section);
            });
        }
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('date', fn($row) => $row->date->format('Y-m-d'))
            ->addColumn('nama', fn($row) => $row->employee->name ?? '-')
            ->addColumn('bagian', fn($row) => $row->employee->section ?? '-')
            ->addColumn('ket', fn($row) => $row->notes ?: 'Tidak ada keterangan')
            ->addColumn('status', fn($row) => $row->approval_status)
            ->addColumn('aksi', function ($row) {
                return $row; // Return full row for modal attributes
            })
            ->make(true);
    }

    /**
     * Approve or Decline a problematic attendance record.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approval_status' => 'required|in:Done,Decline',
            'notes' => 'nullable|string'
        ]);

        $attendance = DailyAttendance::findOrFail($id);
        
        $attendance->approval_status = $request->approval_status;
        if ($request->filled('notes')) {
            $attendance->notes = $request->notes;
        }
        
        $attendance->save();

        return response()->json(['message' => 'Status presensi berhasil diperbarui.']);
    }
}
