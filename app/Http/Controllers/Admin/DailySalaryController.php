<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
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

            $query = DailyAttendance::with('employee')
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
                ->addColumn('gaji', function ($row) {
                    return 0;
                })
                ->editColumn('gaji', fn($row) => '0')
                ->make(true);
        }

        return view('admin.rekap-gaji-harian');
    }
}
