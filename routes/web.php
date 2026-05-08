<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DailySalaryController;
use App\Http\Controllers\Admin\MonthlySalaryController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('admin.dashboard'));

// Base login route for middleware redirection
Route::get('/login', fn() => redirect()->route('admin.login'))->name('login');

// Auth Routes
Route::middleware(['guest'])->group(function () {
    // Admin Login
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::get('/admin/forgot-password', fn() => redirect()->back()->with('error', 'Fitur belum tersedia'))->name('admin.forgot-password');

    // Employee Login
    Route::get('/employee/login', [AuthController::class, 'showEmployeeLoginForm'])->name('employee.login');
    Route::post('/employee/login', [AuthController::class, 'employeeLogin'])->name('employee.login.submit');
    Route::get('/employee/forgot-password', fn() => redirect()->back()->with('error', 'Fitur belum tersedia'))->name('employee.forgot-password');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'todayAttendanceData'])->name('dashboard.data');
    Route::get('/halaman-absensi', [DashboardController::class, 'halamanAbsensi'])->name('halaman-absensi');
    Route::get('/halaman-absensi/data', [DashboardController::class, 'halamanAbsensiData'])->name('halaman-absensi.data');

    // --- Attendance (Presensi) ---
    Route::get('/rekap-presensi', [AttendanceController::class, 'index'])->name('rekap-presensi');
    Route::get('/rekap-presensi/data', [AttendanceController::class, 'data'])->name('rekap-presensi.data');
    Route::get('/presensi-bermasalah', [AttendanceController::class, 'problematicIndex'])->name('presensi-bermasalah');
    Route::get('/presensi-bermasalah/data', [AttendanceController::class, 'problematicData'])->name('presensi-bermasalah.data');
    Route::post('/presensi-bermasalah/{id}/approve', [AttendanceController::class, 'approve'])->name('presensi-bermasalah.approve');

    // --- Settings ---
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');

    // --- Salary Recaps ---
    Route::get('/rekap-gaji-harian', [DailySalaryController::class, 'index'])->name('rekap-gaji-harian');
    Route::get('/rekap-gaji-bulanan', [MonthlySalaryController::class, 'index'])->name('rekap-gaji-bulanan');

    // --- Employee List (CRUD + DataTables) ---
    Route::get('/karyawan', [EmployeeController::class, 'index'])->name('karyawan');
    Route::get('/karyawan/data', [EmployeeController::class, 'data'])->name('karyawan.data');
    Route::post('/karyawan', [EmployeeController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{id}', [EmployeeController::class, 'show'])->name('karyawan.show');
    Route::put('/karyawan/{id}', [EmployeeController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [EmployeeController::class, 'destroy'])->name('karyawan.destroy');

    // --- Admin List (CRUD + DataTables) ---
    Route::get('/daftar-admin', [AdminController::class, 'index'])->name('daftar-admin');
    Route::get('/daftar-admin/data', [AdminController::class, 'data'])->name('daftar-admin.data');
    Route::post('/daftar-admin', [AdminController::class, 'store'])->name('daftar-admin.store');
    Route::get('/daftar-admin/{id}', [AdminController::class, 'show'])->name('daftar-admin.show');
    Route::put('/daftar-admin/{id}', [AdminController::class, 'update'])->name('daftar-admin.update');
    Route::delete('/daftar-admin/{id}', [AdminController::class, 'destroy'])->name('daftar-admin.destroy');
});

// ── Employee (Karyawan) Routes ──────────────────────────────────────────────
Route::prefix('employee')->name('employee.')->middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/dashboard', fn() => view('employee.dashboard'))->name('dashboard');
    Route::get('/scan', fn() => view('employee.scan'))->name('scan');
    Route::get('/izin', fn() => view('employee.izin'))->name('izin');
    Route::get('/profile', fn() => view('employee.profile'))->name('profile');
    Route::get('/rekap', fn() => view('employee.dashboard'))->name('rekap'); // placeholder
    Route::get('/history', fn() => view('employee.history'))->name('history');
});
