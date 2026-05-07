{{-- resources/views/employee/scan.blade.php --}}
@extends('layouts.employee')
@section('title', 'Scan Absensi')

@section('content')
<div class="relative min-h-screen flex flex-col"
     x-data="{
        showSuccess: false,
        showFail: false,
        simulateScan(result) {
            if (result === 'ok') { this.showSuccess = true; }
            else { this.showFail = true; }
        }
     }">

    {{-- ══════════════════════════════════════
         DARK CAMERA OVERLAY
    ════════════════════════════════════════ --}}
    <div class="absolute inset-0 bg-black/85 z-0"></div>

    {{-- Top bar --}}
    <div class="relative z-10 flex items-center justify-between px-5 pt-12 pb-4">
        <a href="{{ route('employee.dashboard') }}" class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center">
            <i class="fa-solid fa-chevron-left text-white"></i>
        </a>
        <h1 class="text-white font-bold text-base">Scan Absensi</h1>
        <button class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center">
            <i class="fa-solid fa-lightbulb text-white"></i>
        </button>
    </div>

    {{-- Instruction text --}}
    <div class="relative z-10 text-center px-6 pt-2 pb-5">
        <p class="text-white/70 text-sm">Arahkan kamera ke QR Code absensi</p>
    </div>

    {{-- ── QR SCAN FRAME ── --}}
    <div class="relative z-10 flex items-center justify-center flex-1">
        <div class="relative w-64 h-64">

            {{-- Dark vignette sides --}}
            {{-- Scan area border --}}
            <div class="absolute inset-0 rounded-2xl border-2 border-white/20"></div>

            {{-- Animated scan line --}}
            <div class="absolute left-2 right-2 h-0.5 rounded-full scan-line"
                 style="background: linear-gradient(90deg, transparent, #38BDF8, #818CF8, #38BDF8, transparent);"></div>

            {{-- Corner brackets (cyan) --}}
            {{-- Top-left --}}
            <div class="corner-pulse absolute top-0 left-0 w-8 h-8">
                <div class="absolute top-0 left-0 w-8 h-1 rounded-tl-md bg-cyan-400"></div>
                <div class="absolute top-0 left-0 w-1 h-8 rounded-tl-md bg-cyan-400"></div>
            </div>
            {{-- Top-right --}}
            <div class="corner-pulse absolute top-0 right-0 w-8 h-8">
                <div class="absolute top-0 right-0 w-8 h-1 rounded-tr-md bg-cyan-400"></div>
                <div class="absolute top-0 right-0 w-1 h-8 rounded-tr-md bg-cyan-400"></div>
            </div>
            {{-- Bottom-left --}}
            <div class="corner-pulse absolute bottom-0 left-0 w-8 h-8">
                <div class="absolute bottom-0 left-0 w-8 h-1 rounded-bl-md bg-cyan-400"></div>
                <div class="absolute bottom-0 left-0 w-1 h-8 rounded-bl-md bg-cyan-400"></div>
            </div>
            {{-- Bottom-right --}}
            <div class="corner-pulse absolute bottom-0 right-0 w-8 h-8">
                <div class="absolute bottom-0 right-0 w-8 h-1 rounded-br-md bg-cyan-400"></div>
                <div class="absolute bottom-0 right-0 w-1 h-8 rounded-br-md bg-cyan-400"></div>
            </div>

            {{-- Center QR icon (placeholder) --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-qrcode text-white/10 text-6xl"></i>
            </div>
        </div>
    </div>

    {{-- Info + demo buttons --}}
    <div class="relative z-10 px-6 pb-10 text-center space-y-4">
        <p class="text-white/60 text-xs">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Pastikan QR Code berada dalam kotak pemindai
        </p>

        {{-- Demo simulation buttons --}}
        <div class="flex gap-3">
            <button @click="simulateScan('ok')"
                    class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-white/10 hover:bg-white/20 active:scale-95 transition-all border border-white/20">
                <i class="fa-solid fa-check mr-1 text-emerald-400"></i> Simulasi Berhasil
            </button>
            <button @click="simulateScan('fail')"
                    class="flex-1 py-3 rounded-xl font-semibold text-sm text-white bg-white/10 hover:bg-white/20 active:scale-95 transition-all border border-white/20">
                <i class="fa-solid fa-x mr-1 text-red-400"></i> Simulasi Gagal
            </button>
        </div>

        <a href="{{ route('employee.dashboard') }}"
           class="block w-full py-3.5 rounded-full text-sm font-semibold text-white/80 border border-white/20 hover:bg-white/10 active:scale-95 transition-all">
            Batal
        </a>
    </div>

    {{-- ════════════════════════════════════════
         MODAL: SUCCESS
    ════════════════════════════════════════ --}}
    <div x-show="showSuccess" x-transition.opacity
         class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 px-6">
        <div x-show="showSuccess"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-3xl p-8 w-full max-w-xs text-center shadow-2xl">

            {{-- Success animation ring --}}
            <div class="relative w-24 h-24 mx-auto mb-5">
                <div class="absolute inset-0 rounded-full bg-emerald-100 animate-ping opacity-30"></div>
                <div class="relative w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-5xl"></i>
                </div>
            </div>

            <h2 class="text-xl font-extrabold text-slate-800 mb-1">Presensi Berhasil!</h2>
            <p class="text-sm text-slate-400 mb-1">Clock In tercatat</p>
            <p class="text-2xl font-bold text-[#1E2A5E] mb-6">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>

            <div class="bg-slate-50 rounded-xl p-3 mb-6 text-left space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Nama</span>
                    <span class="font-semibold text-slate-700">{{ auth()->user()->name ?? 'Budi Santoso' }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Tanggal</span>
                    <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Status</span>
                    <span class="font-semibold text-emerald-600">Tepat Waktu</span>
                </div>
            </div>

            <button @click="showSuccess = false; window.location='{{ route('employee.dashboard') }}'"
                    class="w-full py-4 rounded-full font-bold text-white text-sm active:scale-95 transition-all"
                    style="background: linear-gradient(135deg, #10B981, #059669);">
                <i class="fa-solid fa-house mr-2"></i>OK, Kembali ke Home
            </button>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         MODAL: FAIL
    ════════════════════════════════════════ --}}
    <div x-show="showFail" x-transition.opacity
         class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 px-6">
        <div x-show="showFail"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-3xl p-8 w-full max-w-xs text-center shadow-2xl">

            {{-- Fail animation ring --}}
            <div class="relative w-24 h-24 mx-auto mb-5">
                <div class="absolute inset-0 rounded-full bg-red-100 animate-ping opacity-30"></div>
                <div class="relative w-24 h-24 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-500 text-5xl"></i>
                </div>
            </div>

            <p class="text-xs font-bold tracking-widest text-red-400 uppercase mb-1">SORRY</p>
            <h2 class="text-xl font-extrabold text-slate-800 mb-2">Presensi Gagal</h2>
            <p class="text-sm text-slate-400 mb-6">QR Code tidak valid atau sudah kedaluwarsa.<br>Coba lagi dengan QR yang baru.</p>

            <div class="space-y-3">
                <button @click="showFail = false"
                        class="w-full py-4 rounded-full font-bold text-white text-sm active:scale-95 transition-all"
                        style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                    <i class="fa-solid fa-rotate-right mr-2"></i>Try Again
                </button>
                <a href="{{ route('employee.dashboard') }}"
                   class="block w-full py-3.5 rounded-full font-semibold text-slate-500 text-sm border border-slate-200 active:scale-95 transition-all">
                    Kembali ke Home
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
