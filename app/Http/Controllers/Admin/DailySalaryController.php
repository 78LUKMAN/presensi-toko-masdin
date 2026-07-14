<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use App\Models\DailySalary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DailySalaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->filled('date') ? $request->get('date') : null;
            $section = $request->get('section');

            // Preload employee and ALL of their daily salaries (or just the filtered date)
            $query = DailyAttendance::with(['employee', 'employee.dailySalaries' => function($q) use ($date) {
                    if ($date) {
                        $q->where('date', $date);
                    }
                }])
                ->whereNotIn('status', ['Izin', 'Sakit', 'Cuti']);

            if ($date) {
                $query->whereDate('date', $date);
            }

            if ($section) {
                $query->whereHas('employee', function ($q) use ($section) {
                    $q->where('section', $section);
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('id_pegawai', fn($row) => $row->employee->noreg)
                ->addColumn('nama', fn($row) => $row->employee->name)
                ->addColumn('bagian', fn($row) => $row->employee->section)
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
                ->addColumn('waktu', fn($row) => formatWorkingHours($row->total_hours))
                ->addColumn('gaji', function ($row) {
                    $salary = $row->employee->dailySalaries->where('date', $row->date)->first();
                    return $salary ? $salary->salary_amount : 0;
                })
                ->addColumn('salary_id', function ($row) {
                    $salary = $row->employee->dailySalaries->where('date', $row->date)->first();
                    return $salary ? $salary->id : null;
                })
                ->addColumn('salary_status', function ($row) {
                    $salary = $row->employee->dailySalaries->where('date', $row->date)->first();
                    return $salary ? $salary->salary_status : null;
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->employee_id;
                })
                ->addColumn('attendance_date', function ($row) {
                    return $row->date->format('Y-m-d');
                })
                ->addColumn('total_hours', function ($row) {
                    return $row->total_hours;
                })
                ->make(true);
        }

        return view('admin.rekap-gaji-harian');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'salary_amount' => 'required|numeric|min:0',
        ]);

        $salary = DailySalary::findOrFail($id);
        $salary->update([
            'salary_amount' => $request->salary_amount,
            'salary_status' => 'manual',
            'notes' => $request->notes ?? 'Diinput manual oleh admin',
        ]);

        return response()->json(['success' => true, 'message' => 'Gaji berhasil diperbarui']);
    }

    /**
     * Optional: store if not exist but admin wants to set it manually
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'date' => 'required|date',
            'salary_amount' => 'required|numeric|min:0',
        ]);

        DailySalary::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
            ],
            [
                'salary_amount' => $request->salary_amount,
                'salary_status' => 'manual',
                'total_hours' => $request->total_hours ?? 0,
                'notes' => $request->notes ?? 'Diinput manual oleh admin',
            ]
        );

        return response()->json(['success' => true, 'message' => 'Gaji berhasil disimpan']);
    }
}
