<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DailyAttendance;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    public function download($path)
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return Storage::disk('public')->download($path);
        }

        if ($user->role === 'employee') {
            $employee = $user->employee;
            if (!$employee) {
                abort(403, 'Unauthorized access.');
            }

            $attendance = DailyAttendance::where('employee_id', $employee->id)
                ->where('attachment', $path)
                ->first();

            if (!$attendance) {
                abort(403, 'Anda tidak memiliki akses ke lampiran ini.');
            }

            return Storage::disk('public')->download($path);
        }

        abort(403, 'Unauthorized access.');
    }
}

