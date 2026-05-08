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
}
