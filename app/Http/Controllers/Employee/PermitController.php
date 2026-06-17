<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PermitController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            abort(403, 'Profil Karyawan tidak ditemukan.');
        }
        
        // Fetch history of permits (Izin, Sakit, Cuti)
        $histories = DailyAttendance::where('employee_id', $employee->id)
            ->whereIn('status', ['Izin', 'Sakit', 'Cuti'])
            ->orderBy('date', 'desc')
            ->get();

        // Group continuous dates if needed, or just display raw entries.
        // For simplicity in the view, we'll pass the raw entries or group them by notes.
        // The Blade view expects a grouped format if it spans multiple days, 
        // but passing individual days works fine for the history tab too.
        
        return view('employee.izin', compact('histories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Izin,Sakit,Cuti',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $employee = Auth::user()->employee;
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Profil Karyawan tidak ditemukan.'], 403);
        }
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $period = CarbonPeriod::create($request->start_date, $request->end_date);

        // Check for existing records on these dates
        foreach ($period as $date) {
            $exists = DailyAttendance::where('employee_id', $employee->id)
                ->where('date', $date->format('Y-m-d'))
                ->exists();
                
            if ($exists) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Sudah ada presensi atau pengajuan untuk tanggal ' . $date->format('d M Y') . '. Tidak dapat mengajukan izin tumpang tindih.'
                ], 400);
            }
        }

        foreach ($period as $date) {
            DailyAttendance::create(
                [
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'status' => $request->type,
                    'notes' => $request->reason,
                    'attachment' => $attachmentPath,
                    'approval_status' => 'Pending'
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dikirim.']);
    }
}
