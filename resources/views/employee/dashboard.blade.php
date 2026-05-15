{{-- resources/views/employee/dashboard.blade.php --}}
@extends('layouts.employee')
@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col min-h-screen bg-white text-slate-800" x-data='{
    time: "",
    date: "",
    update() {
        const now = new Date();
        this.time = now.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit", second: "2-digit" });
        this.date = now.toLocaleDateString("id-ID", { weekday: "long", day: "numeric", month: "long", year: "numeric" });
    }
}' x-init="update(); setInterval(() => update(), 1000)">

    {{-- 0. Warning Message --}}
    @if($forgotClockOut)
    <div class="m-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
        <p class="text-sm text-red-700">
            Please note: You haven't clocked out for the previous day. Please complete it as soon as possible so that your honorarium payment can proceed smoothly. Thank you.
        </p>
    </div>
    @endif

    {{-- 1. Greeting --}}
    <div class="px-5 pt-8 pb-4">
        <h4 class="text-sm font-medium text-slate-500">
            @php
                $h = (int)\Carbon\Carbon::now()->format('H');
                echo $h < 12 ? 'Good Morning' : ($h < 17 ? 'Good Afternoon' : 'Good Evening');
            @endphp
        </h4>
        <br />
        <h2 class="text-2xl font-bold text-[#035EA1]">
            {{ auth()->user()->name ?? 'Full Name' }}
        </h2>
    </div>

    @php
        // Attendance logic
        $clockIn  = optional($attendance)->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : null;
        $clockOut = optional($attendance)->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : null;

        $toMinutes = fn(string $t): int => (int)explode(':', $t)[0] * 60 + (int)explode(':', $t)[1];
        
        $workedMins = 0;
        if ($clockIn && $clockOut) {
            $workedMins = $toMinutes($clockOut) - $toMinutes($clockIn);
            $workedMins = max(0, $workedMins);
        } elseif ($clockIn && !$clockOut) {
            $nowMins    = (int)now()->format('H') * 60 + (int)now()->format('i');
            $workedMins = max(0, $nowMins - $toMinutes($clockIn));
        }

        $workedH = round($workedMins / 60, 1);
    @endphp

    {{-- 2. Section: Today's Attendance --}}
    <div class="mx-4 mb-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 mb-4">Today's Attendance</h3>

        <div class="flex flex-col items-center justify-center py-4">
            {{-- Real-time time --}}
            <div class="text-4xl font-extrabold text-[#035EA1]" x-text="time">00:00:00</div>
            {{-- Day, Date --}}
            <div class="text-sm text-slate-500 mt-1" x-text="date">Loading...</div>
        </div>

        {{-- Attendance Button --}}
        <div class="mt-4">
            <a href="{{ route('employee.scan') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-[#035EA1] text-white rounded-xl font-semibold hover:bg-[#024D82] transition-colors shadow-sm shadow-[#035EA1]/20">
                <i class="fa-solid fa-qrcode"></i>
                Scan Attendance
            </a>
        </div>

        {{-- Total work hours --}}
        <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
            <span class="text-sm text-slate-500">Total Work Hours</span>
            <span class="text-sm font-bold {{ $workedH < 9 ? 'text-red-500' : 'text-emerald-500' }}">
                {{ $workedH }} / 9 Hours
            </span>
        </div>
    </div>

    {{-- 3. Today's History --}}
    <div class="mx-4 mb-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-800">Today's History</h3>
            <a href="{{ route('employee.rekap') }}" class="text-xs text-[#035EA1] font-semibold">View All</a>
        </div>

        <div class="space-y-3">
            @if($attendance)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Clock In</p>
                            <p class="text-xs text-slate-400">Today</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-700">
                        {{ $clockIn ?? '–' }}
                    </p>
                </div>

                @if($clockOut)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Clock Out</p>
                            <p class="text-xs text-slate-400">Today</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-700">
                        {{ $clockOut }}
                    </p>
                </div>
                @endif
            @else
                <div class="text-center text-sm text-slate-400 py-4">No attendance record for today.</div>
            @endif
        </div>
    </div>

    {{-- 4. Section: "Today's Salary" --}}
    <div class="mx-4 mb-20 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 mb-4">Today's Salary</h3>

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500">Total Work Hours</span>
                <span class="text-sm font-bold {{ $workedH < 9 ? 'text-red-500' : 'text-emerald-500' }}">
                    {{ $workedH }} Hours
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500">Total Wage</span>
                <span class="text-sm font-bold text-[#035EA1]">
                    {{ $todaySalary ? 'Rp ' . number_format($todaySalary->salary_amount, 0, ',', '.') : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500">Status</span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $todaySalary ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                    {{ $todaySalary ? 'Paid' : 'Pending' }}
                </span>
            </div>
        </div>

        <p class="text-xs text-slate-400 mt-4 text-center italic">
            "Wage is calculated automatically after clocking out"
        </p>
    </div>

    {{-- Bottom Nav --}}
    @include('employee._bottom-nav', ['active' => 'home'])
</div>
@endsection
