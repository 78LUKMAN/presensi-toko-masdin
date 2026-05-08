<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== ADMIN ====================
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@makdin.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // ==================== KARYAWAN ====================
        // Karyawan 1 (dengan akun login)
        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@makdin.com',
            'password' => 'password123',
            'role' => 'employee',
        ]);

        Employee::create([
            'user_id' => $user1->id,
            'noreg' => 'EMP001',
            'name' => 'Budi Santoso',
            'department' => 'Produksi',
            'join_date' => '2024-01-15',
        ]);

        // Karyawan 2 (dengan akun login)
        $user2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@makdin.com',
            'password' => 'password123',
            'role' => 'employee',
        ]);

        Employee::create([
            'user_id' => $user2->id,
            'noreg' => 'EMP002',
            'name' => 'Siti Aminah',
            'department' => 'Keuangan',
            'join_date' => '2024-02-20',
        ]);

        // Karyawan 3 (tanpa akun login – hanya scan QR)
        Employee::create([
            'user_id' => null,
            'noreg' => 'EMP003',
            'name' => 'Ahmad Wijaya',
            'department' => 'Logistik',
            'join_date' => '2024-03-10',
        ]);

        // ==================== PENGATURAN TOKO ====================
        $settings = [
            ['key' => 'company_name', 'value' => 'Presensi Toko Makdin', 'description' => 'Nama perusahaan/toko'],
            ['key' => 'daily_salary_default', 'value' => '50000', 'description' => 'Gaji harian default (dalam rupiah)'],
            ['key' => 'min_work_hours', 'value' => '9', 'description' => 'Minimal jam kerja per hari untuk dianggap penuh'],
            ['key' => 'address', 'value' => 'Jl. Raya Makmur No. 123, Surabaya', 'description' => 'Alamat toko'],
            ['key' => 'phone', 'value' => '081234567890', 'description' => 'Nomor telepon toko'],
            ['key' => 'overtime_rate', 'value' => '1.5', 'description' => 'Rate lembur per jam (x gaji normal)'],
            ['key' => 'currency', 'value' => 'IDR', 'description' => 'Mata uang'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'description' => 'Zona waktu'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }

        $this->command->info('Seeder completed: Admin, employees, and settings created.');
    }
}