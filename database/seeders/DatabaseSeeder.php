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
            'name' => 'Admin Masdin',
            'email' => 'admin@masdin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Seed 5 Employees
        $employeeData = [
            ['name' => 'Budi Santoso', 'section' => 'Produksi'],
            ['name' => 'Siti Aminah', 'section' => 'Gudang'],
            ['name' => 'Ahmad Wijaya', 'section' => 'Administrasi'],
            ['name' => 'Rina Permata', 'section' => 'Keamanan'],
            ['name' => 'Eko Prasetyo', 'section' => 'Logistik'],
        ];

        $employees = [];
        foreach ($employeeData as $index => $data) {
            $num = $index + 1;
            $user = User::create([
                'name' => $data['name'],
                'email' => "karyawan{$num}@masdin.com",
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

        // 4. Seed Attendance History (Last 7 Days)
        $today = \Carbon\Carbon::today();
        foreach ($employees as $employee) {
            // Seed for the last 7 days
            for ($i = 7; $i >= 1; $i--) {
                $date = $today->copy()->subDays($i);
                
                // Skip Sundays (assuming no work on Sunday)
                if ($date->dayOfWeek === \Carbon\Carbon::SUNDAY) continue;

                // Random check-in around 07:00 - 08:30
                $checkIn = $date->copy()->setTime(rand(7, 8), rand(0, 59));
                // Random check-out around 16:00 - 18:00
                $checkOut = $date->copy()->setTime(rand(16, 17), rand(0, 59));
                
                $diffInMinutes = $checkOut->diffInMinutes($checkIn);
                $totalHours = round($diffInMinutes / 60, 2);

                DailyAttendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'check_in_time' => $checkIn->toTimeString(),
                    'check_out_time' => $checkOut->toTimeString(),
                    'total_hours' => $totalHours,
                    'status' => $totalHours >= 9 ? 'Hadir' : 'Hadir (Kurang Jam)',
                    'approval_status' => 'approved',
                ]);

                // Create Salary Record
                DailySalary::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'total_hours' => $totalHours,
                    'salary_amount' => 50000,
                ]);

                // Create Logs
                DB::table('attendance_logs')->insert([
                    [
                        'employee_id' => $employee->id,
                        'timestamp' => $checkIn,
                        'type' => 'in',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'employee_id' => $employee->id,
                        'timestamp' => $checkOut,
                        'type' => 'out',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        }

        $this->command->info('DatabaseSeeder: Toko Masdin sync complete.');
    }
}