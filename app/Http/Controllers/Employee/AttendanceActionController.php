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
                
                // Check late status
                $shiftStart = Carbon::createFromTime(7, 0, 0); // 07:00
                $gracePeriod = 15; // 15 mins
                if ($now->greaterThan($shiftStart->copy()->addMinutes($gracePeriod))) {
                    $attendance->status = 'Terlambat';
                } else {
                    $attendance->status = 'Hadir';
                }
                
                $attendance->save();
                $message = 'Clock In berhasil';
            } else {
                // Clocking out
                $attendance->check_out_time = $now->format('H:i:s');
                
                // Calculate total hours
                $checkIn = Carbon::parse($attendance->check_in_time);
                $diffInMinutes = $now->diffInMinutes($checkIn);
                $attendance->total_hours = round($diffInMinutes / 60, 2);
                
                $attendance->save();

                // Automatically fill daily salary with 50,000
                \App\Models\DailySalary::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date' => $today,
                    ],
                    [
                        'total_hours' => $attendance->total_hours,
                        'salary_amount' => 50000,
                        'notes' => 'Otomatis dari sistem (Absen Pulang)',
                    ]
                );

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

        return view('employee.history', compact('histories'));
    }
}
