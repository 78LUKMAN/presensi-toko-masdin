<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailySalary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MonthlySalaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->get('year', now()->year);
            $month = $request->get('month', now()->month);

            $query = DailySalary::with('employee')
                ->select('employee_id', 
                    DB::raw('COUNT(*) as total_days'),
                    DB::raw('SUM(total_hours) as total_hours'),
                    DB::raw('SUM(salary_amount) as total_salary')
                )
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->groupBy('employee_id');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama', fn($row) => $row->employee->name)
                ->addColumn('bagian', fn($row) => $row->employee->section)
                ->editColumn('total_hours', fn($row) => $row->total_hours . 'j')
                ->editColumn('total_salary', fn($row) => 'Rp ' . number_format($row->total_salary, 0, ',', '.'))
                ->make(true);
        }

        return view('admin.rekap-gaji-bulanan');
    }
}
