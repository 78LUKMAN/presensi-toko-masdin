{{-- resources/views/employee/history.blade.php --}}
@extends('layouts.employee')
@section('title', 'Riwayat')

@section('content')
@php
    $absensiMapped = $histories->filter(fn($h) => !in_array($h->status, ['Izin', 'Sakit', 'Cuti']))->map(function($h) {
        return [
            'id' => $h->id,
            'date' => \Carbon\Carbon::parse($h->date)->format('Y-m-d'),
            'day' => \Carbon\Carbon::parse($h->date)->translatedFormat('l'),
            'clockIn' => $h->check_in_time ? \Carbon\Carbon::parse($h->check_in_time)->format('H:i') : null,
            'clockOut' => $h->check_out_time ? \Carbon\Carbon::parse($h->check_out_time)->format('H:i') : null,
            'duration' => $h->total_hours ? $h->total_hours . 'j' : '-',
            'status' => $h->status ?? 'Alpa',
            'lateMin' => 0
        ];
    })->values();

    $pengajuanMapped = $histories->filter(fn($h) => in_array($h->status, ['Izin', 'Sakit', 'Cuti']))->map(function($h) {
        return [
            'id' => $h->id,
            'type' => $h->status,
            'startDate' => \Carbon\Carbon::parse($h->date)->format('Y-m-d'),
            'endDate' => \Carbon\Carbon::parse($h->date)->format('Y-m-d'),
            'days' => 1,
            'reason' => $h->notes ?? '-',
            'status' => $h->approval_status === 'Done' ? 'Disetujui' : ($h->approval_status === 'Decline' ? 'Ditolak' : 'Menunggu')
        ];
    })->values();
@endphp
{{-- Pass PHP data to Alpine via a script block before the component --}}
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('historyApp', () => ({
            tab: 'absensi',
            filterType: 'all',
            dateFrom: '',
            dateTo: '',
            showFilter: false,
            absensiData: @json($absensiMapped),
            pengajuanData: @json($pengajuanMapped),

            get filteredAbsensi() {
                return this.absensiData.filter(r => {
                    const typeOk = this.filterType === 'all' || r.status.toLowerCase().includes(this.filterType);
                    const fromOk = !this.dateFrom || r.date >= this.dateFrom;
                    const toOk   = !this.dateTo   || r.date <= this.dateTo;
                    return typeOk && fromOk && toOk;
                });
            },

            get filteredPengajuan() {
                return this.pengajuanData.filter(r => {
                    const typeOk = this.filterType === 'all' || r.type.toLowerCase() === this.filterType;
                    const fromOk = !this.dateFrom || r.startDate >= this.dateFrom;
                    const toOk   = !this.dateTo   || r.startDate <= this.dateTo;
                    return typeOk && fromOk && toOk;
                });
            },

            statusBadge(status) {
                const map = {
                    'Hadir':        { bg:'bg-emerald-100', text:'text-emerald-700', icon:'fa-circle-check' },
                    'Pulang Cepat': { bg:'bg-orange-100',  text:'text-orange-700',  icon:'fa-circle-exclamation' },
                    'Alpa':         { bg:'bg-red-100',     text:'text-red-700',     icon:'fa-circle-xmark' },
                    'Libur':        { bg:'bg-slate-100',   text:'text-slate-500',   icon:'fa-umbrella-beach' },
                    'Disetujui':    { bg:'bg-emerald-100', text:'text-emerald-700', icon:'fa-circle-check' },
                    'Menunggu':     { bg:'bg-amber-100',   text:'text-amber-700',   icon:'fa-clock' },
                    'Ditolak':      { bg:'bg-red-100',     text:'text-red-700',     icon:'fa-circle-xmark' },
                };
                return map[status] || { bg:'bg-slate-100', text:'text-slate-500', icon:'fa-circle' };
            },

            resetFilter() {
                this.filterType = 'all';
                this.dateFrom   = '';
                this.dateTo     = '';
                this.showFilter = false;
            }
        }));
    });
</script>
@endpush

<div class="min-h-screen bg-[#F0F4F8] pb-24" x-data="historyApp">

    {{-- ── Header ── --}}
    <div class="relative px-5 pt-12 pb-5 text-white"
         style="background: linear-gradient(135deg, #1E2A5E 0%, #2D3F8F 60%, #3B82F6 100%);">
        <div class="absolute top-0 right-0 w-40 h-40 rounded-full opacity-10 bg-white" style="transform:translate(30%,-30%)"></div>

        <div class="relative flex items-center justify-between mb-4">
            <div>
                <p class="text-blue-200 text-xs font-medium tracking-widest uppercase">Riwayat</p>
                <h1 class="text-white text-xl font-extrabold">Aktivitas Saya</h1>
            </div>
            {{-- Filter toggle button --}}
            <button @click="showFilter = !showFilter"
                    class="relative w-10 h-10 rounded-full bg-white/15 flex items-center justify-center border border-white/20">
                <i class="fa-solid fa-sliders text-white text-base"></i>
                <span x-show="filterType !== 'all' || dateFrom || dateTo"
                      class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-amber-400 border-2 border-[#1E2A5E]"></span>
            </button>
        </div>

    </div>

    {{-- ── Filter Panel (collapsible) ── --}}
    <div x-show="showFilter"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mx-4 mt-3 bg-white rounded-2xl shadow-md p-4 space-y-3">

        <div class="flex items-center justify-between mb-1">
            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Filter</p>
            <button @click="resetFilter()" class="text-xs text-blue-600 font-semibold">Reset</button>
        </div>

        {{-- Type filter (absensi tab) --}}
        <div x-show="tab === 'absensi'">
            <p class="text-xs text-slate-400 font-medium mb-2">Status Absensi</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="opt in [['all','Semua'],['hadir','Hadir'],['pulang cepat','Pulang Cepat'],['alpa','Alpa']]" :key="opt[0]">
                    <button @click="filterType = opt[0]"
                            :class="filterType === opt[0] ? 'bg-[#1E2A5E] text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all"
                            x-text="opt[1]"></button>
                </template>
            </div>
        </div>

        {{-- Type filter (pengajuan tab) --}}
        <div x-show="tab === 'pengajuan'">
            <p class="text-xs text-slate-400 font-medium mb-2">Jenis Pengajuan</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="opt in [['all','Semua'],['izin','Izin'],['sakit','Sakit'],['cuti','Cuti']]" :key="opt[0]">
                    <button @click="filterType = opt[0]"
                            :class="filterType === opt[0] ? 'bg-[#1E2A5E] text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all"
                            x-text="opt[1]"></button>
                </template>
            </div>
        </div>

        {{-- Date range --}}
        <div class="grid grid-cols-2 gap-2">
            <div>
                <p class="text-xs text-slate-400 font-medium mb-1">Dari Tanggal</p>
                <input type="date" x-model="dateFrom"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30"/>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium mb-1">Sampai Tanggal</p>
                <input type="date" x-model="dateTo"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30"/>
            </div>
        </div>

        <button @click="showFilter = false"
                class="w-full py-2.5 rounded-xl font-bold text-white text-sm transition-all active:scale-95"
                style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
            <i class="fa-solid fa-check mr-1.5"></i>Terapkan Filter
        </button>
    </div>

    {{-- ── Tab switcher ── --}}
    <div class="px-4 mt-4">
        <div class="bg-white rounded-2xl shadow-sm p-1 flex gap-1">
            <button @click="tab = 'absensi'; filterType = 'all'"
                    :class="tab === 'absensi' ? 'text-white shadow-sm' : 'text-slate-500'"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold transition-all"
                    :style="tab === 'absensi' ? 'background: linear-gradient(135deg,#1E2A5E,#3B82F6)' : ''">
                <i class="fa-solid fa-calendar-check mr-1.5"></i>Absensi
            </button>
            <button @click="tab = 'pengajuan'; filterType = 'all'"
                    :class="tab === 'pengajuan' ? 'text-white shadow-sm' : 'text-slate-500'"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold transition-all"
                    :style="tab === 'pengajuan' ? 'background: linear-gradient(135deg,#1E2A5E,#3B82F6)' : ''">
                <i class="fa-solid fa-file-lines mr-1.5"></i>Pengajuan
            </button>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         TAB: ABSENSI
    ════════════════════════════════════════ --}}
    <div x-show="tab === 'absensi'" x-transition.opacity class="px-4 mt-4 space-y-3">

        {{-- Active filter chips --}}
        <div x-show="filterType !== 'all' || dateFrom || dateTo" class="flex flex-wrap gap-2">
            <template x-if="filterType !== 'all'">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-[#1E2A5E]/10 text-[#1E2A5E] px-3 py-1 rounded-full">
                    <i class="fa-solid fa-tag text-[10px]"></i>
                    <span x-text="filterType"></span>
                    <button @click="filterType = 'all'" class="ml-1 hover:text-red-500">✕</button>
                </span>
            </template>
            <template x-if="dateFrom">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-calendar text-[10px]"></i>
                    <span x-text="'Dari ' + dateFrom"></span>
                    <button @click="dateFrom = ''" class="ml-1 hover:text-red-500">✕</button>
                </span>
            </template>
        </div>

        {{-- Result count --}}
        <p class="text-xs text-slate-400 font-medium" x-text="filteredAbsensi.length + ' data ditemukan'"></p>

        {{-- Cards --}}
        <template x-for="row in filteredAbsensi" :key="row.id">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-4 pt-3.5 pb-2">
                    <div>
                        <p class="text-sm font-extrabold text-slate-800" x-text="row.day + ', ' + row.date"></p>
                    </div>
                </div>

                <div class="flex divide-x divide-slate-100 border-t border-slate-50">
                    <div class="flex-1 px-4 py-2.5">
                        <p class="text-[10px] text-slate-400 font-medium mb-0.5">
                            <i class="fa-solid fa-right-to-bracket text-emerald-500 mr-1"></i>Masuk
                        </p>
                        <p class="text-base font-extrabold text-[#1E2A5E]" x-text="row.clockIn ?? '– –'"></p>
                    </div>
                    <div class="flex-1 px-4 py-2.5">
                        <p class="text-[10px] text-slate-400 font-medium mb-0.5">
                            <i class="fa-solid fa-right-from-bracket text-blue-500 mr-1"></i>Pulang
                        </p>
                        <p class="text-base font-extrabold text-[#1E2A5E]" x-text="row.clockOut ?? '– –'"></p>
                    </div>
                    <div class="flex-1 px-4 py-2.5">
                        <p class="text-[10px] text-slate-400 font-medium mb-0.5">
                            <i class="fa-regular fa-clock text-slate-400 mr-1"></i>Durasi
                        </p>
                        <p class="text-base font-extrabold text-[#1E2A5E]" x-text="row.duration"></p>
                    </div>
                </div>


            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="filteredAbsensi.length === 0">
            <div class="py-16 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-calendar-xmark text-slate-300 text-4xl"></i>
                </div>
                <p class="text-sm font-bold text-slate-500">Tidak ada data absensi</p>
                <p class="text-xs text-slate-400 mt-1">Coba ubah filter yang diterapkan</p>
                <button @click="resetFilter()" class="mt-4 px-5 py-2 rounded-full text-xs font-bold text-white" style="background:#1E2A5E">
                    Reset Filter
                </button>
            </div>
        </template>
    </div>

    {{-- ════════════════════════════════════════
         TAB: PENGAJUAN
    ════════════════════════════════════════ --}}
    <div x-show="tab === 'pengajuan'" x-transition.opacity class="px-4 mt-4 space-y-3">

        <p class="text-xs text-slate-400 font-medium" x-text="filteredPengajuan.length + ' pengajuan ditemukan'"></p>

        <template x-for="row in filteredPengajuan" :key="row.id">
            <div class="bg-white rounded-2xl shadow-sm p-4">
                <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                         :class="row.type === 'Izin' ? 'bg-blue-100' : row.type === 'Sakit' ? 'bg-red-100' : 'bg-emerald-100'">
                        <i :class="'fa-solid text-lg ' + (row.type === 'Izin' ? 'fa-file-lines text-blue-600' : row.type === 'Sakit' ? 'fa-kit-medical text-red-600' : 'fa-umbrella-beach text-emerald-600')"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-extrabold text-slate-800" x-text="row.type"></p>
                            <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0"
                                  :class="statusBadge(row.status).bg + ' ' + statusBadge(row.status).text">
                                <i :class="'fa-solid ' + statusBadge(row.status).icon + ' text-[10px]'"></i>
                                <span x-text="row.status"></span>
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">
                            <i class="fa-regular fa-calendar mr-1"></i>
                            <span x-text="row.startDate === row.endDate ? row.startDate : row.startDate + ' – ' + row.endDate"></span>
                            <span class="ml-1 font-semibold text-slate-500" x-text="'(' + row.days + ' hari)'"></span>
                        </p>
                        <p class="text-xs text-slate-500 mt-1.5 italic truncate" x-text="'\"' + row.reason + '\"'"></p>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="filteredPengajuan.length === 0">
            <div class="py-16 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-file-circle-xmark text-slate-300 text-4xl"></i>
                </div>
                <p class="text-sm font-bold text-slate-500">Tidak ada pengajuan</p>
                <p class="text-xs text-slate-400 mt-1">Coba ubah filter atau ajukan baru</p>
                <a href="{{ route('employee.izin') }}" class="inline-block mt-4 px-5 py-2 rounded-full text-xs font-bold text-white" style="background:#1E2A5E">
                    <i class="fa-solid fa-plus mr-1"></i>Ajukan Izin
                </a>
            </div>
        </template>
    </div>

    @include('employee._bottom-nav', ['active' => 'history'])
</div>
@endsection
