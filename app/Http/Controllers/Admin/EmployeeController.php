<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display the Daftar Karyawan page.
     */
    public function index()
    {
        return view('admin.karyawan');
    }

    /**
     * Return JSON data for DataTables (server-side).
     */
    public function data(Request $request)
    {
        $employees = Employee::with('user')
            ->select('employees.*');

        return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('email', fn($row) => $row->user?->email ?? '-')
            ->editColumn('join_date', fn($row) => $row->join_date?->format('d/m/Y') ?? '-')
            ->addColumn('aksi', function ($row) {
                return $row->id; // used by JS to build buttons
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Store a new employee (and optionally a linked user account).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'noreg'      => 'required|string|max:50|unique:employees,noreg',
            'name'       => 'required|string|max:255',
            'section'    => 'required|string|max:255',
            'join_date'  => 'required|date',
            'email'      => 'nullable|email|max:255|unique:users,email',
            'password'   => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($validated) {
            $userId = null;

            // Create linked user account if email is provided
            if (!empty($validated['email'])) {
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => $validated['password'] ?? 'password123',
                    'role'     => 'employee',
                ]);
                $userId = $user->id;
            }

            Employee::create([
                'user_id'    => $userId,
                'noreg'      => $validated['noreg'],
                'name'       => $validated['name'],
                'section'    => $validated['section'],
                'join_date'  => $validated['join_date'],
            ]);
        });

        return response()->json(['message' => 'Karyawan berhasil ditambahkan.']);
    }

    /**
     * Return a single employee record for the edit modal.
     */
    public function show(int $id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        return response()->json([
            'id'         => $employee->id,
            'noreg'      => $employee->noreg,
            'name'       => $employee->name,
            'section'    => $employee->section,
            'join_date'  => $employee->join_date?->format('Y-m-d'),
            'email'      => $employee->user?->email,
        ]);
    }

    /**
     * Update an existing employee.
     */
    public function update(Request $request, int $id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        $validated = $request->validate([
            'noreg'      => ['required', 'string', 'max:50', Rule::unique('employees', 'noreg')->ignore($employee->id)],
            'name'       => 'required|string|max:255',
            'section'    => 'required|string|max:255',
            'join_date'  => 'required|date',
            'email'      => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->user_id)],
            'password'   => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($employee, $validated) {
            $employee->update([
                'noreg'      => $validated['noreg'],
                'name'       => $validated['name'],
                'section'    => $validated['section'],
                'join_date'  => $validated['join_date'],
            ]);

            if (!empty($validated['email'])) {
                if ($employee->user) {
                    $updateData = ['name' => $validated['name'], 'email' => $validated['email']];
                    if (!empty($validated['password'])) {
                        $updateData['password'] = $validated['password'];
                    }
                    $employee->user->update($updateData);
                } else {
                    $user = User::create([
                        'name'     => $validated['name'],
                        'email'    => $validated['email'],
                        'password' => $validated['password'] ?? 'password123',
                        'role'     => 'employee',
                    ]);
                    $employee->update(['user_id' => $user->id]);
                }
            }
        });

        return response()->json(['message' => 'Karyawan berhasil diperbarui.']);
    }

    /**
     * Delete an employee (and their linked user account).
     */
    public function destroy(int $id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        DB::transaction(function () use ($employee) {
            $employee->user?->delete();
            $employee->delete();
        });

        return response()->json(['message' => 'Karyawan berhasil dihapus.']);
    }
}
