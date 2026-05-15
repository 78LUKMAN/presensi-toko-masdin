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
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
                <input type="date" id="filter-tanggal" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"/>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Cari Nama Karyawan</label>
                <input type="text" id="filter-nama" placeholder="Ketik nama karyawan..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"/>
            </div>
            <div class="flex gap-2">
                <button id="btn-filter" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">Terapkan Filter</button>
                <button id="btn-reset" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition-colors">Reset</button>
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
    // Default to today
    document.getElementById('filter-tanggal').value = new Date().toISOString().split('T')[0];

    const table = $('#table-rekap-presensi').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.rekap-presensi.data") }}',
            data: function(d) {
                d.date = document.getElementById('filter-tanggal').value;
                d.name = document.getElementById('filter-nama').value;
            }
        },
        language: { url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json' },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'id_karyawan', render: d => `<span class="font-mono text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg">${d}</span>` },
            { data: 'nama', render: d => `<span class="font-medium text-slate-800">${d}</span>` },
            { data: 'bagian', render: d => `<span class="text-slate-600">${d}</span>` },
            { data: 'masuk', render: d => `<span class="font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg text-xs">${d}</span>` },
            { data: 'pulang', render: d => `<span class="font-mono text-orange-700 bg-orange-50 px-2 py-0.5 rounded-lg text-xs">${d}</span>` },
            { data: 'total_hours' },
            { data: null, orderable: false, searchable: false, className: 'text-center',
              render: (_, __, row) => `
                <div class="flex items-center justify-center gap-1">
                  <button class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Detail">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                </div>` },
        ],
        createdRow: row => $(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
    });

    // Filter logic
    document.getElementById('btn-filter').onclick = () => {
        table.draw();
    };
    document.getElementById('btn-reset').onclick = () => {
        document.getElementById('filter-tanggal').value = new Date().toISOString().split('T')[0];
        document.getElementById('filter-nama').value = '';
        table.search('').columns().search('').draw();
    };
})();
</script>
@endpush
