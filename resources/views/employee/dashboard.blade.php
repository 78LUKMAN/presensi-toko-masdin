{{-- resources/views/employee/dashboard.blade.php --}}
@extends('layouts.employee')
@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col min-h-screen bg-white text-slate-800" x-data='{
    hourMin: "",
    sec: "",
    date: "",
    offset: {{ $serverTimestampMs }} - Date.now(),
    update() {
        const now = new Date(Date.now() + this.offset);
        const h = String(now.getHours()).padStart(2, "0");
        const m = String(now.getMinutes()).padStart(2, "0");
        const s = String(now.getSeconds()).padStart(2, "0");
        this.hourMin = `${h}:${m}`;
        this.sec = s;
        this.date = now.toLocaleDateString("id-ID", { weekday: "long", day: "numeric", month: "long", year: "numeric" });
    }
}' x-init="update(); setInterval(() => update(), 1000)">

    {{-- Session Flash Messages (Modal Overlay) --}}
    @if(session('success'))
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 px-6" x-data="{ show: true }" x-show="show" x-transition>
        <div class="bg-white rounded-3xl p-8 w-full max-w-xs text-center shadow-2xl">
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-check text-emerald-500 text-4xl"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800 mb-1">Berhasil!</h3>
            <p class="text-sm text-slate-500 mb-6">{{ session('success') }}</p>
            <button @click="show = false"
                    class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-sm active:scale-95 transition-all shadow-md shadow-emerald-500/20">
                OK
            </button>
        </div>
    </div>
    @endif

    {{-- 0. Warning Message --}}
    @if($forgotClockOut)
    <div class="m-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm text-red-700">
                Perhatian: Anda belum absen pulang untuk hari sebelumnya. Segera lengkapi agar pembayaran honorarium Anda dapat diproses dengan lancar. Terima kasih.
            </p>
            <a href="{{ route('employee.presensi-bermasalah') }}" class="inline-block mt-3 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors">
                Selesaikan Sekarang
            </a>
        </div>
    </div>
    @endif

    {{-- 1. Greeting --}}
    <div class="px-5 pt-8 pb-4">
        <h4 class="text-sm font-medium text-slate-500">
            @php
                $h = (int)\Carbon\Carbon::now()->format('H');
                echo $h < 12 ? 'Selamat Pagi' : ($h < 17 ? 'Selamat Siang' : 'Selamat Malam');
            @endphp
        </h4>
        <br />
        <h2 class="text-2xl font-bold text-[#035EA1]">
            {{ auth()->user()->name ?? 'Nama Pegawai' }}
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
        $workedFormatted = formatWorkingHoursFromMinutes($workedMins);
    @endphp

    {{-- 2. Section: Today's Attendance --}}
    <div class="mx-4 mb-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 mb-4">Presensi Hari Ini</h3>

        <div class="flex flex-col items-center justify-center py-4">
            {{-- Real-time time --}}
            <div class="text-4xl font-extrabold text-[#035EA1] flex items-baseline justify-center">
                <span x-text="hourMin">00:00</span>
                <!-- <span class="text-2xl font-bold ml-1 text-[#035EA1]/70">:<span x-text="sec">00</span></span> -->
            </div>
            {{-- Day, Date --}}
            <div class="text-sm text-slate-500 mt-1" x-text="date">Memuat...</div>
        </div>

        {{-- Attendance Button --}}
        <div class="mt-4">
            <a href="{{ route('employee.scan') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-[#035EA1] text-white rounded-xl font-semibold hover:bg-[#024D82] transition-colors shadow-sm shadow-[#035EA1]/20">
                <i class="fa-solid fa-qrcode"></i>
                Scan Presensi
            </a>
        </div>

        {{-- Total work hours --}}
        <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
            <span class="text-sm text-slate-500">Total Jam Kerja</span>
            <span class="text-sm font-bold {{ $workedH < 9 ? 'text-red-500' : 'text-emerald-500' }}">
                {{ $workedFormatted }} / 9 jam
            </span>
        </div>
    </div>

    {{-- 3. Today's History --}}
    <div class="mx-4 mb-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-800">Riwayat Hari Ini</h3>
            <a href="{{ route('employee.rekap') }}" class="text-xs text-[#035EA1] font-semibold">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @if($attendance)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Jam Masuk</p>
                            <p class="text-xs text-slate-400">Hari Ini</p>
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
                            <p class="text-sm font-semibold">Jam Pulang</p>
                            <p class="text-xs text-slate-400">Hari Ini</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-700">
                        {{ $clockOut }}
                    </p>
                </div>
                @endif
            @else
                <div class="text-center text-sm text-slate-400 py-4">Belum ada data presensi hari ini.</div>
            @endif
        </div>
    </div>

    {{-- 4. Section: "Today's Salary" --}}
    <div class="mx-4 mb-20 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 mb-4">Gaji Hari Ini</h3>

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500">Total Jam Kerja</span>
                <span class="text-sm font-bold {{ $workedH < 9 ? 'text-red-500' : 'text-emerald-500' }}">
                    {{ $workedFormatted }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500">Total Upah</span>
                <span class="text-sm font-bold text-[#035EA1]">
                    {{ $todaySalary ? 'Rp ' . number_format($todaySalary->salary_amount, 0, ',', '.') : 'Rp 0' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500">Status</span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $todaySalary ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                    {{ $todaySalary ? 'Dibayar' : 'Menunggu' }}
                </span>
            </div>
        </div>

        <p class="text-xs text-slate-400 mt-4 text-center italic">
            "Upah dihitung otomatis setelah absen pulang"
        </p>
    </div>

    {{-- Bottom Nav --}}
    @include('employee._bottom-nav', ['active' => 'home'])
</div>
@endsection
