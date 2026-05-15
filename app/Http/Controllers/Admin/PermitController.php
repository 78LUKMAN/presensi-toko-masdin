<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PermitController extends Controller
{
    /**
     * Display the perizinan page.
     */
    public function index()
    {
        return view('admin.perizinan');
    }

    /**
     * Return JSON data for Perizinan DataTables.
     */
    public function data(Request $request)
    {
        // Only include permit statuses (Izin, Sakit, Cuti)
        $query = DailyAttendance::with('employee')
            ->whereIn('status', ['Izin', 'Sakit', 'Cuti'])
            ->orderBy('created_at', 'desc');

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
            ->addColumn('tipe', fn($row) => $row->status)
            ->addColumn('ket', fn($row) => $row->notes ?: '-')
            ->addColumn('status', fn($row) => $row->approval_status)
            ->addColumn('aksi', function ($row) {
                return $row; // Return full row for modal
            })
            ->make(true);
    }

    /**
     * Approve a permit request.
     */
    public function approve(Request $request, $id)
    {
        $attendance = DailyAttendance::findOrFail($id);
        
        $attendance->update([
            'approval_status' => 'Done',
        ]);

        return response()->json(['message' => 'Pengajuan berhasil disetujui.']);
    }

    /**
     * Reject a permit request.
     */
    public function reject(Request $request, $id)
    {
        $attendance = DailyAttendance::findOrFail($id);
        
        $attendance->update([
            'approval_status' => 'Decline',
        ]);

        return response()->json(['message' => 'Pengajuan telah ditolak.']);
    }
}
