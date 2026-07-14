<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'noreg',
        'name',
        'section',
        'join_date',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    /**
     * The user account linked to this employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance records for the employee.
     */
    public function attendances()
    {
        return $this->hasMany(DailyAttendance::class);
    }

    public function dailySalaries()
    {
        return $this->hasMany(DailySalary::class);
    }

    /**
     * Automatically fill missing attendance records with 'Alpha' up to yesterday.
     */
    public function syncAlphas()
    {
        $earliest = \Carbon\Carbon::parse($this->join_date ?? \Carbon\Carbon::today()->subDays(30));
        $latest = \Carbon\Carbon::yesterday();
        
        if ($earliest->isAfter($latest)) {
            return;
        }
        
        $existingDates = DailyAttendance::where('employee_id', $this->id)
            ->whereBetween('date', [$earliest->format('Y-m-d'), $latest->format('Y-m-d')])
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();
            
        $newRecords = [];
        for ($date = $latest->copy(); $date->gte($earliest); $date->subDay()) {
            if ($date->isSunday()) continue; // Skip Sundays
            
            $dateString = $date->format('Y-m-d');
            if (!in_array($dateString, $existingDates)) {
                $newRecords[] = [
                    'employee_id' => $this->id,
                    'date' => $dateString,
                    'status' => 'Alpha',
                    'notes' => 'Tidak hadir tanpa keterangan',
                    'approval_status' => 'Approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        if (count($newRecords) > 0) {
            DailyAttendance::insert($newRecords);
        }
    }
}
