<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function karyawan()
    {
        return view('admin.karyawan');
    }

    public function rekapPresensi()
    {
        return view('admin.rekap-presensi');
    }

    public function presensiRermasalah()
    {
        return view('admin.presensi-bermasalah');
    }

    public function rekapGajiHarian()
    {
        return view('admin.rekap-gaji-harian');
    }

    public function rekapGajiBulanan()
    {
        return view('admin.rekap-gaji-bulanan');
    }

    public function daftarAdmin()
    {
        return view('admin.daftar-admin');
    }

    public function pengaturan()
    {
        return view('admin.pengaturan');
    }

    public function halamanAbsensi()
    {
        return view('admin.halaman-absensi');
    }
}
