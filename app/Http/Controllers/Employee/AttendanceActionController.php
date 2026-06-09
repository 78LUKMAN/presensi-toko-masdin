<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceActionController extends Controller
{
    public function scan()
    {
        return view('employee.scan');
    }

    public function processScan(Request $request)
    {
        // Simple scan simulation handler
        // In reality, it should validate a token from the QR code.
        $user = Auth::user();
        $employee = $user->employee;
        $today = Carbon::today();
        $now = Carbon::now();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee data not found'], 404);
        }

        $token = $request->token;

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token is required'], 400);
        }

        $qrToken = \App\Models\QrToken::where('token', $token)->first();

        if (!$qrToken) {
            return response()->json(['success' => false, 'message' => 'QR code is invalid'], 400);
        }

        if ($qrToken->expires_at->isPast()) {
            return response()->json(['success' => false, 'message' => 'QR code has expired'], 400);
        }

        try {
            DB::beginTransaction();

            // Log raw scan
            DB::table('attendance_logs')->insert([
                'employee_id' => $employee->id,
                'timestamp' => $now,
                'type' => 'in', // simplify
                'qr_token' => $request->token ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update or create daily attendance
            $attendance = DailyAttendance::firstOrNew([
                'employee_id' => $employee->id,
                'date' => $today
            ]);

            if (!$attendance->exists || !$attendance->check_in_time) {
                // Clocking in
                $attendance->check_in_time = $now->format('H:i:s');
                $attendance->status = 'Hadir';
                $attendance->approval_status = 'Done'; // Normal clock-in doesn't need approval
                $attendance->save();
                $message = 'Clock In berhasil';
            } else {
                // Issue 2 Fix: Prevent duplicate checkout on the same day
                if ($attendance->check_out_time) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan presensi pulang hari ini'
                    ], 422);
                }

                // Clocking out
                $attendance->check_out_time = $now->format('H:i:s');

                // Calculate total hours
                $checkIn = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->check_in_time);
                $checkOut = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->check_out_time);
                $diffInMinutes = $checkIn->diffInMinutes($checkOut, false);
                $attendance->total_hours = $diffInMinutes < 0 ? null : round($diffInMinutes / 60, 2);

                // Set status based on 9 hours requirement
                if ($attendance->total_hours < 9) {
                    $attendance->status = 'Pulang Cepat';
                } else {
                    $attendance->status = 'Hadir';
                }

                $attendance->approval_status = 'Done'; // Normal clock-out doesn't need approval
                $attendance->save();

                // Issue 3 Fix: Only auto-set salary to 50,000 if hours >= 9
                // Otherwise mark as pending_manual for admin to fill
                if ($attendance->total_hours >= 9) {
                    \App\Models\DailySalary::updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'date' => $today,
                        ],
                        [
                            'total_hours' => $attendance->total_hours,
                            'salary_amount' => 50000,
                            'salary_status' => 'auto',
                            'notes' => 'Otomatis dari sistem (9 jam terpenuhi)',
                        ]
                    );
                } else {
                    \App\Models\DailySalary::updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'date' => $today,
                        ],
                        [
                            'total_hours' => $attendance->total_hours,
                            'salary_amount' => 0,
                            'salary_status' => 'pending_manual',
                            'notes' => 'Jam kerja kurang dari 9 jam, menunggu input admin',
                        ]
                    );
                }

                $message = 'Clock Out berhasil';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'time' => $now->format('H:i'),
                'date' => $today->format('d M Y'),
                'type' => $attendance->check_out_time ? 'Out' : 'In',
                'status' => $attendance->status
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function history()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            abort(403, 'Profil Karyawan tidak ditemukan.');
        }
        $histories = DailyAttendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->get();
            
        $salaries = \App\Models\DailySalary::where('employee_id', $employee->id)
            ->get()
            ->keyBy(function($item) {
                return $item->date->format('Y-m-d');
            });

        return view('employee.history', compact('histories', 'salaries'));
    }
    public function problematicIndex()
    {
        $employee = Auth::user()->employee;
        if (!$employee) abort(403, 'Profil tidak ditemukan.');

        $yesterday = Carbon::yesterday();
        $attendance = DailyAttendance::where('employee_id', $employee->id)
            ->whereDate('date', $yesterday)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->first();

        if (!$attendance) {
            return redirect()->route('employee.dashboard')->with('success', 'Tidak ada presensi bermasalah.');
        }

        return view('employee.presensi-bermasalah', compact('attendance'));
    }

    public function problematicStore(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:daily_attendance,id',
            'check_out_time' => 'required',
            'notes' => 'required|string|max:255'
        ]);

        $employee = Auth::user()->employee;
        $attendance = DailyAttendance::where('id', $request->attendance_id)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        $checkIn = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->check_in_time);
        $checkOut = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $request->check_out_time);
        
        $diffInMinutes = $checkIn->diffInMinutes($checkOut, false);
        $totalHours = $diffInMinutes < 0 ? null : round($diffInMinutes / 60, 2);

        $attendance->update([
            'check_out_time' => $request->check_out_time,
            'total_hours' => $totalHours,
            'status' => 'Hadir',
            'approval_status' => 'Pending',
            'notes' => $request->notes,
        ]);

        // Issue 1 Fix: Do NOT create salary here.
        // Salary will only be created when admin approves via AttendanceController@approve.
        // This ensures admin verification is required before salary appears.

        return redirect()->route('employee.dashboard')->with('success', 'Laporan berhasil dikirim. Menunggu verifikasi admin sebelum gaji dihitung.');
    }
}
