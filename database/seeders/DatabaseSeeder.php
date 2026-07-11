<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\DailyAttendance;
use App\Models\DailySalary;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate tables to ensure a clean slate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Employee::truncate();
        Setting::truncate();
        DailyAttendance::truncate();
        DailySalary::truncate();
        DB::table('attendance_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Settings
        $settings = [
            ['key' => 'company_name', 'value' => 'Presensi Toko Masdin', 'description' => 'Nama perusahaan/toko'],
            ['key' => 'daily_salary_default', 'value' => '50000', 'description' => 'Gaji harian default (dalam rupiah)'],
            ['key' => 'min_work_hours', 'value' => '9', 'description' => 'Minimal jam kerja per hari untuk dianggap penuh'],
            ['key' => 'address', 'value' => 'Jl. Raya Makmur No. 123, Surabaya', 'description' => 'Alamat toko'],
            ['key' => 'phone', 'value' => '081234567890', 'description' => 'Nomor telepon toko'],
            ['key' => 'overtime_rate', 'value' => '1.5', 'description' => 'Rate lembur per jam (x gaji normal)'],
            ['key' => 'currency', 'value' => 'IDR', 'description' => 'Mata uang'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'description' => 'Zona waktu'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // 2. Seed Admin
        User::create([
            'name' => 'Mas Din',
            'email' => 'admin@masdin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Seed 5 Employees
        $employeeData = [
            ['name' => 'Mudah', 'section' => 'Kasir'],
            ['name' => 'Jannah', 'section' => 'Kasir'],
            ['name' => 'Ais', 'section' => 'Kasir'],
            ['name' => 'Santoso', 'section' => 'Juru Timbang'],
            ['name' => 'Imam', 'section' => 'Juru Timbangan/Kurir'],
            ['name' => 'Gunawan', 'section' => 'Kurir'],
            ['name' => 'Noval', 'section' => 'Kurir'],
        ];

        $employees = [];
        foreach ($employeeData as $index => $data) {
            $num = $index + 1;
            $email = strtolower(str_replace(' ', '', $data['name'])) . '@masdin.com';
            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'noreg' => 'MAS' . str_pad($num, 3, '0', STR_PAD_LEFT),
                'name' => $data['name'],
                'section' => $data['section'],
                'join_date' => \Carbon\Carbon::now()->subMonths(6)->toDateString(),
            ]);
            $employees[] = $employee;
        }

        $this->command->info('DatabaseSeeder: Toko Masdin sync complete.');
    }
}