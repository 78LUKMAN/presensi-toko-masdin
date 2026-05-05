<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class AdminUserController extends Controller
{
    /**
     * Display the Daftar Admin page.
     */
    public function index()
    {
        return view('admin.daftar-admin');
    }

    /**
     * Return JSON data for DataTables (server-side).
     */
    public function data(Request $request)
    {
        $admins = User::where('role', 'admin')->select('id', 'name', 'email', 'role', 'created_at');

        return DataTables::of($admins)
            ->addIndexColumn()
            ->editColumn('created_at', fn($row) => $row->created_at?->format('d/m/Y'))
            ->addColumn('aksi', fn($row) => $row->id)
            ->make(true);
    }

    /**
     * Store a new admin user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return response()->json(['message' => 'Admin berhasil ditambahkan.']);
    }

    /**
     * Return a single admin record for the edit modal.
     */
    public function show(int $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        return response()->json([
            'id'    => $admin->id,
            'name'  => $admin->name,
            'email' => $admin->email,
            'role'  => $admin->role,
        ]);
    }

    /**
     * Update an existing admin user.
     */
    public function update(Request $request, int $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => 'admin',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json(['message' => 'Admin berhasil diperbarui.']);
    }

    /**
     * Delete an admin user.
     */
    public function destroy(int $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return response()->json(['message' => 'Admin berhasil dihapus.']);
    }
}
