@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Greeting --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Selamat Datang, <span class="text-blue-600">Admin</span> 👋</h1>
            <p class="text-sm text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} &mdash; Berikut ringkasan data hari ini.</p>
        </div>
    </div>

    {{-- ── Row 1: Stats Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Jumlah Pegawai --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $totalEmployees }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Jumlah Pegawai</p>
        </div>

        {{-- Jumlah Admin --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $totalAdmins }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Jumlah Admin</p>
        </div>

        {{-- Hadir Hari Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $presentToday }}<span class="text-lg text-slate-400 font-normal">/{{ $totalEmployees }}</span></p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Hadir Hari Ini</p>
        </div>

        {{-- Gaji Bulan Ini --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-blue-200">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>
            <p class="text-2xl font-bold">Rp {{ number_format($totalSalaryThisMonth, 0, ',', '.') }}</p>
            <p class="text-xs text-blue-200 mt-1 font-medium">Total Gaji Bulan Ini</p>
        </div>
    </div>

    {{-- ── Row 2: Secondary Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $problematicCount }}</p>
                <p class="text-xs font-medium text-slate-600">Presensi Bermasalah</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $presensitToday }}</p>
                <p class="text-xs font-medium text-slate-600">Tidak Hadir Hari Ini</p>
            </div>
        </div>
    </div>

    {{-- ── Chart Keaktifan Kehadiran ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Diagram Keaktifan Kehadiran Pegawai</h3>
                <p class="text-xs text-slate-500" id="chart-subtitle">Tren presensi dalam 7 hari terakhir</p>
            </div>
            <select id="chart-filter" class="text-sm border border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 px-3 py-1.5 bg-slate-50 text-slate-700 outline-none">
                <option value="7">7 Hari Terakhir</option>
                <option value="14">14 Hari Terakhir</option>
                <option value="21">21 Hari Terakhir</option>
                <option value="30">1 Bulan Terakhir</option>
            </select>
        </div>
        <div class="relative h-[300px] w-full">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    {{-- ── Row 3: Table + Quick Info ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Rekap Presensi Hari Ini (table) --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Presensi Hari Ini</h3>
                    <p class="text-xs text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </div>
                <a href="{{ route('admin.rekap-presensi') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table id="tbl-dashboard" class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pegawai</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- Side panel --}}
        <div class="space-y-4">
            {{-- Shortcut links --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-2">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Akses Cepat</p>
                <a href="{{ route('admin.pegawai') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-sm text-slate-700 font-medium">Tambah Pegawai</span>
                </a>
                <a href="{{ route('admin.rekap-gaji-harian') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm text-slate-700 font-medium">Rekap Gaji Harian</span>
                </a>
                <a href="{{ route('admin.rekap-gaji-bulanan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm text-slate-700 font-medium">Rekap Gaji Bulanan</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
    const stBadge = s => {
        const c = { Hadir:'bg-emerald-100 text-emerald-700', Terlambat:'bg-amber-100 text-amber-700', 'Tidak Hadir':'bg-red-100 text-red-600', Lembur:'bg-purple-100 text-purple-700' };
        return `<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${c[s]||'bg-slate-100 text-slate-600'}">${s}</span>`;
    };
    $('#tbl-dashboard').DataTable({
        ajax: '{{ route("admin.dashboard.data") }}',
        pageLength: 5,
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:'nama', render:d=>`<span class="font-medium text-slate-800">${d}</span>`},
            {data:'bagian'},
            {data:'masuk', render:d=>d!=='–'?`<span class="font-mono text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg">${d}</span>`:`<span class="text-slate-300">–</span>`},
            {data:'status', render:d=>stBadge(d)},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
    });

    // Chart Initialization
    let attendanceChart;
    const loadChart = (days = 7) => {
        fetch(`{{ route('admin.dashboard.chart') }}?days=${days}`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                if (attendanceChart) attendanceChart.destroy();
                
                attendanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            });
    };

    loadChart(7);

    document.getElementById('chart-filter').addEventListener('change', (e) => {
        loadChart(e.target.value);
        let text = e.target.value == '30' ? '1 bulan terakhir' : `${e.target.value} hari terakhir`;
        document.getElementById('chart-subtitle').textContent = `Tren kehadiran dalam ${text}`;
    });
})();
</script>
@endpush
