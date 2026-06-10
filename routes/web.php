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

// Employee Controllers
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\AttendanceActionController;
use App\Http\Controllers\Employee\PermitController;
use App\Http\Controllers\Employee\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'))->name('home');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/attachment/{path}', [\App\Http\Controllers\AttachmentController::class, 'download'])
        ->where('path', '.*')
        ->name('attachment.download');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'todayAttendanceData'])->name('dashboard.data');
    Route::get('/halaman-absensi', [DashboardController::class, 'halamanAbsensi'])->name('halaman-absensi');
    Route::get('/halaman-absensi/data', [DashboardController::class, 'halamanAbsensiData'])->name('halaman-absensi.data');
    Route::get('/generate-token', [DashboardController::class, 'generateToken'])->name('generate-token');

    // --- Attendance (Presensi) ---
    Route::get('/rekap-presensi', [AttendanceController::class, 'index'])->name('rekap-presensi');
    Route::get('/rekap-presensi/data', [AttendanceController::class, 'data'])->name('rekap-presensi.data');
    Route::get('/presensi-bermasalah', [AttendanceController::class, 'problematicIndex'])->name('presensi-bermasalah');
    Route::get('/presensi-bermasalah/data', [AttendanceController::class, 'problematicData'])->name('presensi-bermasalah.data');
    Route::post('/presensi-bermasalah/{id}/approve', [AttendanceController::class, 'approve'])->name('presensi-bermasalah.approve');

    // --- Perizinan Cuti ---
    Route::get('/perizinan', [\App\Http\Controllers\Admin\PermitController::class, 'index'])->name('perizinan');
    Route::get('/perizinan/data', [\App\Http\Controllers\Admin\PermitController::class, 'data'])->name('perizinan.data');
    Route::post('/perizinan/{id}/approve', [\App\Http\Controllers\Admin\PermitController::class, 'approve'])->name('perizinan.approve');
    Route::post('/perizinan/{id}/reject', [\App\Http\Controllers\Admin\PermitController::class, 'reject'])->name('perizinan.reject');

    // --- Settings ---
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');
    Route::put('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [SettingController::class, 'updatePassword'])->name('password.update');

    // --- Salary Recaps ---
    Route::get('/rekap-gaji-harian', [DailySalaryController::class, 'index'])->name('rekap-gaji-harian');
    Route::post('/rekap-gaji-harian', [DailySalaryController::class, 'store'])->name('rekap-gaji-harian.store');
    Route::put('/rekap-gaji-harian/{id}', [DailySalaryController::class, 'update'])->name('rekap-gaji-harian.update');
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
    Route::get('/dashboard', [EmployeeDashboardController::class, 'dashboard'])->name('dashboard');
    
    // Scan Attendance
    Route::get('/scan', [AttendanceActionController::class, 'scan'])->name('scan');
    Route::post('/scan', [AttendanceActionController::class, 'processScan'])->name('scan.process');
    Route::get('/history', [AttendanceActionController::class, 'history'])->name('history');
    Route::get('/rekap', fn() => redirect()->route('employee.history'))->name('rekap'); // redirect rekap to history
    
    // Presensi Bermasalah
    Route::get('/presensi-bermasalah', [AttendanceActionController::class, 'problematicIndex'])->name('presensi-bermasalah');
    Route::post('/presensi-bermasalah', [AttendanceActionController::class, 'problematicStore'])->name('presensi-bermasalah.store');
    
    // Permits / Leave
    Route::get('/izin', [PermitController::class, 'index'])->name('izin');
    Route::post('/izin', [PermitController::class, 'store'])->name('izin.store');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
