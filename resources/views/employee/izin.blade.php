{{-- resources/views/employee/izin.blade.php --}}
@extends('layouts.employee')
@section('title', 'Izin & Cuti')

@section('content')
<div class="min-h-screen bg-[#F0F4F8]"
     x-data='{
        tab: "form",
        type: "",
        startDate: "",
        endDate: "",
        reason: "",
        fileName: "",
        submitting: false,
        showConfirm: false,
        handleFile(e) {
            this.fileName = e.target.files[0]?.name ?? "";
        },
        async submit() {
            if (!this.type || !this.startDate || !this.reason) return;
            this.submitting = true;
            
            const formData = new FormData();
            formData.append("type", this.type);
            formData.append("start_date", this.startDate);
            formData.append("end_date", this.endDate || this.startDate);
            formData.append("reason", this.reason);
            const fileInput = document.getElementById("attachment");
            if (fileInput.files.length > 0) {
                formData.append("attachment", fileInput.files[0]);
            }
            formData.append("_token", "{{ csrf_token() }}");

            try {
                const res = await fetch("{{ route("employee.izin.store") }}", {
                    method: "POST",
                    body: formData
                });
                const data = await res.json();
                this.submitting = false;
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || "Terjadi kesalahan.");
                }
            } catch (err) {
                this.submitting = false;
                alert("Terjadi kesalahan sistem.");
            }
        }
     }'>

    {{-- Session Flash Messages (Modal Overlay) --}}
    @if(session('success'))
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 px-6" x-data="{ show: true }" x-show="show" x-transition>
        <div class="bg-white rounded-3xl p-8 w-full max-w-[280px] text-center shadow-2xl">
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-check text-emerald-500 text-4xl"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800 mb-1">Berhasil!</h3>
            <p class="text-sm text-slate-500 mb-6">{{ session('success') }}</p>
            <button @click="show = false"
                    class="w-full py-3.5 bg-[#1E2A5E] hover:bg-[#2D3F8F] text-white rounded-full font-bold text-sm active:scale-95 transition-all shadow-md shadow-[#1E2A5E]/20">
                OK
            </button>
        </div>
    </div>
    @endif

    {{-- ── Header ── --}}
    <div class="px-5 pt-12 pb-5" style="background: linear-gradient(135deg, #1E2A5E 0%, #2D3F8F 60%, #3B82F6 100%);">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('employee.dashboard') }}" class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-chevron-left text-white text-sm"></i>
            </a>
            <h1 class="text-white font-bold text-lg">Izin & Cuti</h1>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-2 bg-white/10 rounded-xl p-1">
            <button @click="tab = 'form'"
                    :class="tab === 'form' ? 'bg-white text-[#1E2A5E] shadow-sm' : 'text-white/70'"
                    class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i>Ajukan
            </button>
            <button @click="tab = 'history'"
                    :class="tab === 'history' ? 'bg-white text-[#1E2A5E] shadow-sm' : 'text-white/70'"
                    class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all">
                <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>Riwayat
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         TAB: REQUEST FORM
    ════════════════════════════════════════ --}}
    <div x-show="tab === 'form'" x-transition.opacity class="px-4 py-5 mb-28 space-y-4">

        {{-- Type selector --}}
        <div class="bg-white rounded-2xl shadow-sm p-4 space-y-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Jenis Pengajuan</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['Izin' => 'fa-file-lines', 'Sakit' => 'fa-kit-medical', 'Cuti' => 'fa-umbrella-beach'] as $label => $icon)
                <button @click="type = '{{ $label }}'"
                        :class="type === '{{ $label }}' ? 'border-[#1E2A5E] bg-[#1E2A5E]/5 text-[#1E2A5E]' : 'border-slate-200 text-slate-500'"
                        class="flex flex-col items-center gap-1.5 py-3 rounded-xl border-2 text-xs font-semibold transition-all active:scale-95">
                    <i class="fa-solid {{ $icon }} text-lg"></i>
                    {{ $label }}
                </button>
                @endforeach
            </div>
            <p x-show="!type" class="text-xs text-red-400 mt-1">
                <i class="fa-solid fa-asterisk text-[8px] mr-1"></i>Pilih jenis pengajuan
            </p>
        </div>

        {{-- Date range --}}
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Tanggal</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-slate-400 mb-1.5 font-medium">Mulai</p>
                    <input type="date" x-model="startDate"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]"/>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1.5 font-medium">Selesai</p>
                    <input type="date" x-model="endDate"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]"/>
                </div>
            </div>
            {{-- Duration display --}}
            <div x-show="startDate && endDate" class="mt-2.5 flex items-center gap-2 px-3 py-2 bg-blue-50 rounded-xl">
                <i class="fa-solid fa-calendar-days text-blue-500 text-xs"></i>
                <span class="text-xs text-blue-700 font-semibold" x-text="`Durasi pengajuan`"></span>
            </div>
        </div>

        {{-- Reason textarea --}}
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan / Alasan</label>
            <textarea x-model="reason" rows="4" placeholder="Jelaskan alasan pengajuan Anda..."
                      class="w-full px-3.5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-300 resize-none focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]"></textarea>
            <p class="text-right text-xs text-slate-300 mt-1" x-text="reason.length + '/300'"></p>
        </div>

        {{-- File attachment --}}
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Lampiran (Opsional)</label>
            <label class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 border-dashed border-slate-200 cursor-pointer hover:border-[#1E2A5E]/40 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-[#1E2A5E]/10 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-paperclip text-[#1E2A5E] text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700" x-text="fileName || 'Unggah Dokumen'"></p>
                    <p class="text-xs text-slate-400">PDF, JPG, PNG maks. 5MB</p>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 text-sm"></i>
                <input type="file" id="attachment" class="hidden" @change="handleFile($event)" accept=".pdf,.jpg,.jpeg,.png"/>
            </label>
        </div>

        {{-- Submit button --}}
        <button @click="submit()"
                :disabled="!type || !startDate || !reason || submitting"
                :class="(!type || !startDate || !reason) ? 'opacity-40 cursor-not-allowed' : 'active:scale-95 shadow-lg shadow-blue-900/20'"
                class="w-full py-4 rounded-full font-bold text-white text-base transition-all"
                style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
            <span x-show="!submitting"><i class="fa-solid fa-paper-plane mr-2"></i>Kirim Pengajuan</span>
            <span x-show="submitting"><i class="fa-solid fa-spinner animate-spin mr-2"></i>Mengirim...</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════
         TAB: HISTORY
    ════════════════════════════════════════ --}}
    <div x-show="tab === 'history'" x-transition.opacity class="px-4 py-5 mb-28 space-y-3">
        @if($histories->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-slate-400 text-sm">
                Belum ada riwayat pengajuan.
            </div>
        @endif

        @foreach($histories as $h)
        @php
            $icon = 'fa-file-lines';
            $color = 'bg-blue-100 text-blue-600';
            if($h->status === 'Sakit') { $icon = 'fa-kit-medical'; $color = 'bg-red-100 text-red-600'; }
            elseif($h->status === 'Cuti') { $icon = 'fa-umbrella-beach'; $color = 'bg-green-100 text-green-600'; }

            $statusColor = 'bg-amber-100 text-amber-700';
            $statusIcon = 'fa-clock';
            if($h->approval_status === 'Done') { $statusColor = 'bg-emerald-100 text-emerald-700'; $statusIcon = 'fa-circle-check'; }
            elseif($h->approval_status === 'Decline') { $statusColor = 'bg-red-100 text-red-700'; $statusIcon = 'fa-circle-xmark'; }
        @endphp
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $color }}">
                    <i class="fa-solid {{ $icon }} text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-slate-800">{{ $h->status }}</p>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $statusColor }}">
                            <i class="fa-solid {{ $statusIcon }} mr-1 text-[10px]"></i>{{ $h->approval_status }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ \Carbon\Carbon::parse($h->date)->format('d M Y') }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1.5 truncate">{{ $h->notes }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @include('employee._bottom-nav', ['active' => 'izin'])
</div>
@endsection
