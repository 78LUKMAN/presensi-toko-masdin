<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('admin.dashboard'));

// Admin Routes
// Add ->middleware(['auth']) when authentication is ready
Route::prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard & misc pages ---
    Route::get('/dashboard',           [AdminController::class, 'dashboard'])          ->name('dashboard');
    Route::get('/rekap-presensi',      [AdminController::class, 'rekapPresensi'])      ->name('rekap-presensi');
    Route::get('/presensi-bermasalah', [AdminController::class, 'presensiRermasalah'])->name('presensi-bermasalah');
    Route::get('/rekap-gaji-harian',   [AdminController::class, 'rekapGajiHarian'])    ->name('rekap-gaji-harian');
    Route::get('/rekap-gaji-bulanan',  [AdminController::class, 'rekapGajiBulanan'])   ->name('rekap-gaji-bulanan');
    Route::get('/pengaturan',          [AdminController::class, 'pengaturan'])         ->name('pengaturan');
    Route::get('/halaman-absensi',     [AdminController::class, 'halamanAbsensi'])     ->name('halaman-absensi');

    // --- Daftar Karyawan (CRUD + DataTables) ---
    Route::get('/karyawan',            [KaryawanController::class, 'index'])   ->name('karyawan');
    Route::get('/karyawan/data',       [KaryawanController::class, 'data'])    ->name('karyawan.data');
    Route::post('/karyawan',           [KaryawanController::class, 'store'])   ->name('karyawan.store');
    Route::get('/karyawan/{id}',       [KaryawanController::class, 'show'])    ->name('karyawan.show');
    Route::put('/karyawan/{id}',       [KaryawanController::class, 'update'])  ->name('karyawan.update');
    Route::delete('/karyawan/{id}',    [KaryawanController::class, 'destroy']) ->name('karyawan.destroy');

    // --- Daftar Admin (CRUD + DataTables) ---
    Route::get('/daftar-admin',        [AdminUserController::class, 'index'])   ->name('daftar-admin');
    Route::get('/daftar-admin/data',   [AdminUserController::class, 'data'])    ->name('daftar-admin.data');
    Route::post('/daftar-admin',       [AdminUserController::class, 'store'])   ->name('daftar-admin.store');
    Route::get('/daftar-admin/{id}',   [AdminUserController::class, 'show'])    ->name('daftar-admin.show');
    Route::put('/daftar-admin/{id}',   [AdminUserController::class, 'update'])  ->name('daftar-admin.update');
    Route::delete('/daftar-admin/{id}',[AdminUserController::class, 'destroy']) ->name('daftar-admin.destroy');
});

// Placeholder logout route (replace with Fortify/Breeze when auth is set up)
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ── Employee (Karyawan) Routes ──────────────────────────────────────────────
// Add ->middleware(['auth', 'role:karyawan']) when authentication is ready
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login',     fn() => view('employee.login'))     ->name('login');
    Route::post('/login',    fn() => back())                     ->name('login.submit');
    Route::get('/dashboard', fn() => view('employee.dashboard')) ->name('dashboard');
    Route::get('/scan',      fn() => view('employee.scan'))      ->name('scan');
    Route::get('/izin',      fn() => view('employee.izin'))      ->name('izin');
    Route::get('/profile',   fn() => view('employee.profile'))   ->name('profile');
    Route::get('/rekap',     fn() => view('employee.dashboard')) ->name('rekap'); // placeholder
    Route::get('/forgot-password', fn() => back())               ->name('forgot-password');
});
