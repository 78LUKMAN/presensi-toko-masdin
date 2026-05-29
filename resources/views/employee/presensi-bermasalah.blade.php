@extends('layouts.employee')
@section('title', 'Selesaikan Presensi')

@section('content')
<div class="min-h-screen bg-[#F0F4F8] pb-24">
    <div class="relative px-5 pt-12 pb-6 text-white" style="background: linear-gradient(135deg, #1E2A5E 0%, #2D3F8F 60%, #3B82F6 100%);">
        <a href="{{ route('employee.dashboard') }}" class="absolute top-12 left-5 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition-colors">
            <i class="fa-solid fa-arrow-left text-white"></i>
        </a>
        <div class="mt-8">
            <h1 class="text-white text-2xl font-extrabold">Presensi Bermasalah</h1>
            <p class="text-blue-100 text-sm mt-1">Selesaikan presensi Anda yang belum lengkap</p>
        </div>
    </div>

    <div class="px-5 -mt-4 relative z-10">
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="mb-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-xs text-amber-600 font-bold uppercase tracking-wider mb-1">Informasi Presensi</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-slate-500">Tanggal</span>
                    <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between items-center mt-1">
                    <span class="text-sm text-slate-500">Jam Masuk</span>
                    <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}</span>
                </div>
            </div>

            <form action="{{ route('employee.presensi-bermasalah.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Jam Pulang <span class="text-red-500">*</span></label>
                    <input type="time" name="check_out_time" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]/50 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Alasan Lupa Absen <span class="text-red-500">*</span></label>
                    <textarea name="notes" rows="3" required placeholder="Tuliskan alasan Anda..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]/50 transition-all resize-none"></textarea>
                </div>

                <div class="p-3 bg-blue-50 rounded-xl border border-blue-100 mb-3">
                    <p class="text-xs text-blue-700">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Laporan Anda akan ditinjau oleh admin. Gaji harian baru akan muncul setelah admin memverifikasi laporan ini.
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-white shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all" style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
