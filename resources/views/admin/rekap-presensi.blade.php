@extends('layouts.admin')
@section('title', 'Rekap Presensi')
@section('page-title', 'Rekap Presensi')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Rekap Presensi</h1>
            <p class="text-sm text-slate-500">Data presensi harian seluruh karyawan.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" id="filter-dari" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" id="filter-sampai" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bagian</label>
                <select id="filter-bagian" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Semua Bagian</option>
                    <option>Produksi</option>
                    <option>Gudang</option>
                    <option>Administrasi</option>
                    <option>Keamanan</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button id="btn-filter" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">Terapkan</button>
                <button id="btn-reset" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition-colors">Reset</button>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table id="table-rekap-presensi" class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Karyawan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Waktu</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const dummyData = [
        { id:'KRY-001', nama:'Budi Santoso',   bagian:'Produksi',     masuk:'07:02', pulang:'16:05', total_waktu:'9j 3m',  total:'Rp 95.000' },
        { id:'KRY-002', nama:'Siti Rahayu',    bagian:'Gudang',       masuk:'07:15', pulang:'16:10', total_waktu:'8j 55m', total:'Rp 89.000' },
        { id:'KRY-003', nama:'Ahmad Fauzi',    bagian:'Administrasi', masuk:'08:00', pulang:'17:00', total_waktu:'9j 0m',  total:'Rp 90.000' },
        { id:'KRY-004', nama:'Dewi Lestari',   bagian:'Produksi',     masuk:'06:58', pulang:'15:58', total_waktu:'9j 0m',  total:'Rp 90.000' },
        { id:'KRY-005', nama:'Eko Prasetyo',   bagian:'Keamanan',     masuk:'07:30', pulang:'16:30', total_waktu:'9j 0m',  total:'Rp 85.000' },
        { id:'KRY-006', nama:'Fitri Handayani',bagian:'Administrasi', masuk:'08:05', pulang:'17:10', total_waktu:'9j 5m',  total:'Rp 91.000' },
        { id:'KRY-007', nama:'Gunawan Putra',  bagian:'Gudang',       masuk:'07:00', pulang:'16:00', total_waktu:'9j 0m',  total:'Rp 87.000' },
        { id:'KRY-008', nama:'Hani Sulistyani',bagian:'Produksi',     masuk:'07:10', pulang:'16:05', total_waktu:'8j 55m', total:'Rp 89.000' },
    ];

    const table = $('#table-rekap-presensi').DataTable({
        data: dummyData,
        language: { url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json' },
        columns: [
            { data: null, render: (_, __, ___, meta) => `<span class="text-slate-400 text-xs">${meta.row + 1}</span>` },
            { data: 'id', render: d => `<span class="font-mono text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg">${d}</span>` },
            { data: 'nama', render: d => `<span class="font-medium text-slate-800">${d}</span>` },
            { data: 'bagian', render: d => `<span class="text-slate-600">${d}</span>` },
            { data: 'masuk', render: d => `<span class="font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg text-xs">${d}</span>` },
            { data: 'pulang', render: d => `<span class="font-mono text-orange-700 bg-orange-50 px-2 py-0.5 rounded-lg text-xs">${d}</span>` },
            { data: 'total_waktu' },
            { data: 'total', render: d => `<span class="font-semibold text-slate-800">${d}</span>` },
            { data: null, orderable: false, searchable: false, className: 'text-center',
              render: (_, __, row) => `
                <div class="flex items-center justify-center gap-1">
                  <button class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Detail">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                  <button class="p-1.5 rounded-lg text-red-500 hover:bg-red-50" title="Hapus">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>` },
        ],
        createdRow: row => $(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
    });

    // Filter bagian
    document.getElementById('btn-filter').onclick = () => {
        const bagian = document.getElementById('filter-bagian').value;
        table.column(3).search(bagian).draw();
    };
    document.getElementById('btn-reset').onclick = () => {
        document.getElementById('filter-dari').value = '';
        document.getElementById('filter-sampai').value = '';
        document.getElementById('filter-bagian').value = '';
        table.search('').columns().search('').draw();
    };
})();
</script>
@endpush
