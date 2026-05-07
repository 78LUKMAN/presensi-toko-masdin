{{-- resources/views/employee/dashboard.blade.php --}}
@extends('layouts.employee')
@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col min-h-screen bg-[#F0F4F8]" x-data="{ activeNav: 'home' }">

    {{-- ══════════════════════════════════════
         HEADER (navy gradient)
    ════════════════════════════════════════ --}}
    <div class="relative px-5 pt-12 pb-24 text-white" style="background: linear-gradient(135deg, #1E2A5E 0%, #2D3F8F 60%, #3B82F6 100%);">
        {{-- Decorative circle --}}
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-10 bg-white" style="transform:translate(30%,-30%)"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full opacity-5 bg-white" style="transform:translate(-20%,40%)"></div>

        {{-- Top row: greeting + avatar --}}
        <div class="relative flex items-center justify-between mb-1">
            <div>
                <p class="text-blue-200 text-xs font-medium tracking-wide uppercase">
                    {{ \Carbon\Carbon::now()->format('l, d M Y') }}
                </p>
                <p class="text-white/70 text-sm mt-0.5">
                    @php
                        $h = (int)\Carbon\Carbon::now()->format('H');
                        echo $h < 12 ? 'Selamat Pagi' : ($h < 17 ? 'Selamat Siang' : 'Selamat Sore');
                    @endphp,
                </p>
                <h1 class="text-white text-xl font-bold leading-tight">
                    {{ auth()->user()->name ?? 'Budi Santoso' }}
                </h1>
            </div>
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-12 h-12 rounded-full bg-white/20 border-2 border-white/40 overflow-hidden flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-user text-white text-xl"></i>
                </div>
                <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full font-medium">
                    {{ auth()->user()->noreg ?? 'KRY-001' }}
                </span>
            </div>
        </div>

        {{-- Notification bell --}}
        <button class="absolute top-11 right-16 w-9 h-9 rounded-full bg-white/15 flex items-center justify-center">
            <i class="fa-regular fa-bell text-white text-base"></i>
        </button>
    </div>

    {{-- ══════════════════════════════════════
         STATS CARD (overlaps header)
    ════════════════════════════════════════ --}}
    <div class="px-4 -mt-16 relative z-10">
        <div class="bg-white rounded-2xl shadow-xl p-4">
            <div class="grid grid-cols-3 divide-x divide-slate-100">

                <div class="flex flex-col items-center gap-1 px-2">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center mb-1" style="background: linear-gradient(135deg,#DBEAFE,#BFDBFE);">
                        <i class="fa-solid fa-calendar-check text-blue-700 text-base"></i>
                    </div>
                    <span class="text-2xl font-extrabold text-[#1E2A5E]">22</span>
                    <span class="text-xs text-slate-400 font-medium text-center">Hadir</span>
                </div>

                <div class="flex flex-col items-center gap-1 px-2">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center mb-1" style="background: linear-gradient(135deg,#FEF3C7,#FDE68A);">
                        <i class="fa-solid fa-file-lines text-amber-600 text-base"></i>
                    </div>
                    <span class="text-2xl font-extrabold text-[#1E2A5E]">2</span>
                    <span class="text-xs text-slate-400 font-medium text-center">Izin</span>
                </div>

                <div class="flex flex-col items-center gap-1 px-2">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center mb-1" style="background: linear-gradient(135deg,#D1FAE5,#A7F3D0);">
                        <i class="fa-solid fa-umbrella-beach text-emerald-700 text-base"></i>
                    </div>
                    <span class="text-2xl font-extrabold text-[#1E2A5E]">1</span>
                    <span class="text-xs text-slate-400 font-medium text-center">Cuti</span>
                </div>
            </div>

            {{-- Today's status row --}}
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-semibold text-slate-600">Status Hari Ini</span>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-circle-check mr-1"></i>Hadir
                </span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         QUICK ACTIONS
    ════════════════════════════════════════ --}}
    <div class="px-4 mt-5">
        <div class="grid grid-cols-4 gap-3">

            <a href="{{ route('employee.scan') }}"
               class="flex flex-col items-center gap-1.5 bg-white rounded-2xl py-4 shadow-sm hover:shadow-md active:scale-95 transition-all">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#1E2A5E,#3B82F6);">
                    <i class="fa-solid fa-qrcode text-white text-lg"></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-600 text-center leading-tight">Scan<br>Absen</span>
            </a>

            <a href="{{ route('employee.izin') }}"
               class="flex flex-col items-center gap-1.5 bg-white rounded-2xl py-4 shadow-sm hover:shadow-md active:scale-95 transition-all">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-amber-100">
                    <i class="fa-solid fa-file-signature text-amber-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-600 text-center leading-tight">Ajukan<br>Izin</span>
            </a>

            <a href="{{ route('employee.rekap') }}"
               class="flex flex-col items-center gap-1.5 bg-white rounded-2xl py-4 shadow-sm hover:shadow-md active:scale-95 transition-all">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-purple-100">
                    <i class="fa-solid fa-chart-bar text-purple-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-600 text-center leading-tight">Rekap<br>Absen</span>
            </a>

            <a href="{{ route('employee.profile') }}"
               class="flex flex-col items-center gap-1.5 bg-white rounded-2xl py-4 shadow-sm hover:shadow-md active:scale-95 transition-all">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-rose-100">
                    <i class="fa-solid fa-user-gear text-rose-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-600 text-center leading-tight">Profil<br>Saya</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         TODAY'S SCHEDULE
    ════════════════════════════════════════ --}}
    @php
        // ── Default to null; replace with real DB values when backend is ready ──
        $clockIn  = $attendance->clock_in  ?? null;   // e.g. '07:02' or null
        $clockOut = $attendance->clock_out ?? null;   // e.g. '16:30' or null

        // ── Configuration ────────────────────────────────────────────────────
        $shiftStart   = '07:00';   // Standard start time
        $targetHours  = 9;         // Required working hours
        $lateMinutes  = 15;        // Grace period (minutes) before marked Late

        // ── Helpers (Carbon-free, works on plain "HH:mm" strings) ────────────
        $toMinutes = fn(string $t): int => (int)explode(':', $t)[0] * 60 + (int)explode(':', $t)[1];

        $shiftStartMins = $toMinutes($shiftStart);
        $targetMins     = $targetHours * 60;          // 540 minutes

        // ── Calculate actual worked minutes ──────────────────────────────────
        $workedMins = 0;
        if ($clockIn && $clockOut) {
            $workedMins = $toMinutes($clockOut) - $toMinutes($clockIn);
            $workedMins = max(0, $workedMins);
        } elseif ($clockIn && !$clockOut) {
            // Still working: compare to now
            $nowMins    = (int)now()->format('H') * 60 + (int)now()->format('i');
            $workedMins = max(0, $nowMins - $toMinutes($clockIn));
        }

        // ── Format worked duration ───────────────────────────────────────────
        $workedH   = intdiv($workedMins, 60);
        $workedM   = $workedMins % 60;
        $workedStr = $workedMins > 0 ? "{$workedH}j {$workedM}m" : '–';

        // ── Progress toward 9-hour target (0–100) ───────────────────────────
        $progress = $targetMins > 0 ? min(100, round($workedMins / $targetMins * 100)) : 0;

        // ── Derive clock-in status ───────────────────────────────────────────
        if (!$clockIn) {
            $inStatus   = ['label' => 'Belum Absen', 'class' => 'text-slate-400 bg-slate-100', 'icon' => 'fa-circle-minus'];
        } elseif ($toMinutes($clockIn) <= $shiftStartMins + $lateMinutes) {
            $inStatus   = ['label' => 'Tepat Waktu',  'class' => 'text-emerald-700 bg-emerald-50', 'icon' => 'fa-circle-check'];
        } else {
            $lateBy     = $toMinutes($clockIn) - $shiftStartMins;
            $lateH      = intdiv($lateBy, 60);
            $lateM      = $lateBy % 60;
            $lateLabel  = $lateH > 0 ? "Terlambat {$lateH}j{$lateM}m" : "Terlambat {$lateM}m";
            $inStatus   = ['label' => $lateLabel, 'class' => 'text-amber-700 bg-amber-50', 'icon' => 'fa-triangle-exclamation'];
        }

        // ── Derive clock-out status ──────────────────────────────────────────
        if (!$clockIn) {
            $outStatus  = ['label' => 'Belum Absen', 'class' => 'text-slate-400 bg-slate-100', 'icon' => 'fa-circle-minus'];
        } elseif (!$clockOut) {
            $outStatus  = ['label' => 'Sedang Bekerja', 'class' => 'text-blue-700 bg-blue-50', 'icon' => 'fa-circle-dot'];
        } elseif ($workedMins >= $targetMins) {
            $outStatus  = ['label' => 'Lengkap',     'class' => 'text-emerald-700 bg-emerald-50', 'icon' => 'fa-circle-check'];
        } else {
            $shortBy    = $targetMins - $workedMins;
            $shortH     = intdiv($shortBy, 60);
            $shortM     = $shortBy % 60;
            $shortLabel = $shortH > 0 ? "Pulang Cepat -{$shortH}j{$shortM}m" : "Pulang Cepat -{$shortM}m";
            $outStatus  = ['label' => $shortLabel, 'class' => 'text-red-700 bg-red-50', 'icon' => 'fa-circle-exclamation'];
        }

        // ── Overall day status (for the progress bar colour) ─────────────────
        $barColor = match(true) {
            $progress >= 100                    => '#10B981', // emerald
            $progress >= 50                     => '#3B82F6', // blue
            $clockIn && $workedMins < $targetMins => '#F59E0B', // amber
            default                             => '#94a3b8', // slate
        };
    @endphp

    <div class="px-4 mt-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-slate-800">Jadwal Hari Ini</h2>
            <span class="text-xs text-blue-600 font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- ── Clock-in / Clock-out row ── --}}
            <div class="flex divide-x divide-slate-100">

                {{-- Jam Masuk --}}
                <div class="flex-1 p-4">
                    <p class="text-xs text-slate-400 font-medium mb-1">
                        <i class="fa-solid fa-right-to-bracket mr-1 text-emerald-500 text-[10px]"></i>Jam Masuk
                    </p>
                    <p class="text-xl font-extrabold text-[#1E2A5E]">
                        {{ $clockIn ?? '– –' }}
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full mt-1.5 {{ $inStatus['class'] }}">
                        <i class="fa-solid {{ $inStatus['icon'] }} text-[10px]"></i>
                        {{ $inStatus['label'] }}
                    </span>
                </div>

                {{-- Jam Pulang --}}
                <div class="flex-1 p-4">
                    <p class="text-xs text-slate-400 font-medium mb-1">
                        <i class="fa-solid fa-right-from-bracket mr-1 text-blue-500 text-[10px]"></i>Jam Pulang
                    </p>
                    <p class="text-xl font-extrabold text-[#1E2A5E]">
                        {{ $clockOut ?? '– –' }}
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full mt-1.5 {{ $outStatus['class'] }}">
                        <i class="fa-solid {{ $outStatus['icon'] }} text-[10px]"></i>
                        {{ $outStatus['label'] }}
                    </span>
                </div>
            </div>

            {{-- ── Working duration + progress bar ── --}}
            <div class="px-4 pb-4 pt-1 border-t border-slate-50">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                        <span class="text-xs font-semibold text-slate-500">Total Kerja</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-700">{{ $workedStr }}</span>
                        <span class="text-xs text-slate-400"> / {{ $targetHours }}j</span>
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width: {{ $progress }}%; background: {{ $barColor }};"></div>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-[10px] text-slate-300">0j</span>
                    <span class="text-[10px] font-semibold" style="color: {{ $barColor }};">{{ $progress }}%</span>
                    <span class="text-[10px] text-slate-300">{{ $targetHours }}j</span>
                </div>

                {{-- Warning banner if clocked out early --}}
                @if($clockIn && $clockOut && $workedMins < $targetMins)
                    @php $shortBy = $targetMins - $workedMins; $sH = intdiv($shortBy,60); $sM = $shortBy%60; @endphp
                    <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-red-50 border border-red-100 rounded-xl">
                        <i class="fa-solid fa-circle-exclamation text-red-400 text-sm flex-shrink-0"></i>
                        <p class="text-xs text-red-600 font-medium">
                            Kurang <span class="font-bold">{{ $sH > 0 ? "{$sH} jam {$sM} menit" : "{$sM} menit" }}</span>
                            dari target {{ $targetHours }} jam kerja
                        </p>
                    </div>
                @elseif($clockIn && $clockOut && $workedMins >= $targetMins)
                    <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm flex-shrink-0"></i>
                        <p class="text-xs text-emerald-700 font-medium">Target {{ $targetHours }} jam kerja <span class="font-bold">tercapai!</span></p>
                    </div>
                @elseif(!$clockIn)
                    <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-100 rounded-xl">
                        <i class="fa-solid fa-fingerprint text-slate-400 text-sm flex-shrink-0"></i>
                        <p class="text-xs text-slate-500 font-medium">Scan QR untuk mulai absensi hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         RECENT ACTIVITY
    ════════════════════════════════════════ --}}
    <div class="px-4 mt-5 mb-28">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-slate-800">Aktivitas Terkini</h2>
            <a href="{{ route('employee.rekap') }}" class="text-xs text-blue-600 font-semibold">Lihat Semua →</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm divide-y divide-slate-50">
            @php
            $activities = [
                ['icon'=>'fa-right-to-bracket','color'=>'bg-emerald-100 text-emerald-600','time'=>'07:02','label'=>'Clock In','date'=>'Hari ini','status'=>'Hadir','statusColor'=>'text-emerald-600'],
                ['icon'=>'fa-right-from-bracket','color'=>'bg-blue-100 text-blue-600','time'=>'16:05','label'=>'Clock Out','date'=>'Kemarin','status'=>'Hadir','statusColor'=>'text-emerald-600'],
                ['icon'=>'fa-right-to-bracket','color'=>'bg-emerald-100 text-emerald-600','time'=>'07:15','label'=>'Clock In','date'=>'2 hari lalu','status'=>'Terlambat','statusColor'=>'text-amber-600'],
                ['icon'=>'fa-file-lines','color'=>'bg-amber-100 text-amber-600','time'=>'09:00','label'=>'Izin Diajukan','date'=>'3 hari lalu','status'=>'Pending','statusColor'=>'text-amber-600'],
            ];
            @endphp

            @foreach($activities as $act)
            <div class="flex items-center gap-3 px-4 py-3.5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $act['color'] }}">
                    <i class="fa-solid {{ $act['icon'] }} text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800">{{ $act['label'] }}</p>
                    <p class="text-xs text-slate-400">{{ $act['date'] }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-slate-700">{{ $act['time'] }}</p>
                    <p class="text-xs font-semibold {{ $act['statusColor'] }}">{{ $act['status'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════
         BOTTOM NAVIGATION BAR
    ════════════════════════════════════════ --}}
    @include('employee._bottom-nav', ['active' => 'home'])
</div>
@endsection
