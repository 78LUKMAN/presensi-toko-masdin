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
            $date = $request->get('date', now()->format('Y-m-d'));
            $section = $request->get('section');

            $query = DailyAttendance::with(['employee', 'employee.dailySalaries' => function($q) use ($date) {
                    $q->where('date', $date);
                }])
                ->where('date', $date);

            if ($section) {
                $query->whereHas('employee', function ($q) use ($section) {
                    $q->where('section', $section);
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('id_karyawan', fn($row) => $row->employee->noreg)
                ->addColumn('nama', fn($row) => $row->employee->name)
                ->addColumn('bagian', fn($row) => $row->employee->section)
                ->addColumn('waktu', fn($row) => $row->total_hours . 'j')
                ->addColumn('gaji', function ($row) use ($date) {
                    $salary = $row->employee->dailySalaries->first();
                    return $salary ? $salary->salary_amount : 0;
                })
                ->addColumn('salary_id', function ($row) {
                    $salary = $row->employee->dailySalaries->first();
                    return $salary ? $salary->id : null;
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
            'notes' => $request->notes ?? $salary->notes,
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
                'total_hours' => $request->total_hours ?? 0,
                'notes' => $request->notes,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Gaji berhasil disimpan']);
    }
}
