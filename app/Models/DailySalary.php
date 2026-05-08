<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySalary extends Model
{
    protected $table = 'daily_salary';

    protected $fillable = [
        'employee_id',
        'date',
        'total_hours',
        'salary_amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
