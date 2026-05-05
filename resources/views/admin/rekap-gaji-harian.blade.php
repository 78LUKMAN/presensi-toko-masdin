@extends('layouts.admin')
@section('title', 'Rekap Gaji Harian')
@section('page-title', 'Rekap Gaji Harian')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Rekap Gaji Harian</h1>
            <p class="text-sm text-slate-500">Rekap perhitungan gaji harian berdasarkan jam kerja karyawan.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </button>
    </div>

    {{-- Summary + Filter --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        {{-- Total Card --}}
        <div class="lg:col-span-1 bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-indigo-200">
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-1">Total Gaji Harian</p>
            <p class="text-3xl font-bold" id="total-gaji-harian">Rp 0</p>
            <p class="text-xs text-indigo-200 mt-2">Berdasarkan filter aktif</p>
        </div>
        {{-- Filter --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                <input type="date" id="gh-tgl" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bagian</label>
                <select id="gh-bagian" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bagian</option>
                    <option>Produksi</option><option>Gudang</option><option>Administrasi</option><option>Keamanan</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button id="gh-filter" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl">Terapkan</button>
                <button id="gh-reset" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl">Reset</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="p-5 overflow-x-auto">
            <table id="tbl-gh" class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Karyawan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Gaji</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-200 bg-slate-50">
                        <td colspan="5" class="px-4 py-3 text-sm font-bold text-slate-700 text-right">Total Gaji Harian</td>
                        <td class="px-4 py-3 text-sm font-bold text-indigo-700" id="tfoot-total">–</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    const data=[
        {id:'KRY-001',nama:'Budi Santoso',bagian:'Produksi',waktu:'9j 3m',gaji:95000},
        {id:'KRY-002',nama:'Siti Rahayu',bagian:'Gudang',waktu:'8j 55m',gaji:89000},
        {id:'KRY-003',nama:'Ahmad Fauzi',bagian:'Administrasi',waktu:'9j 0m',gaji:90000},
        {id:'KRY-004',nama:'Dewi Lestari',bagian:'Produksi',waktu:'9j 0m',gaji:90000},
        {id:'KRY-005',nama:'Eko Prasetyo',bagian:'Keamanan',waktu:'9j 0m',gaji:85000},
        {id:'KRY-006',nama:'Fitri Handayani',bagian:'Administrasi',waktu:'9j 5m',gaji:91000},
        {id:'KRY-007',nama:'Gunawan Putra',bagian:'Gudang',waktu:'9j 0m',gaji:87000},
        {id:'KRY-008',nama:'Hani Sulistyani',bagian:'Produksi',waktu:'8j 55m',gaji:89000},
    ];

    const updateTotal = tbl => {
        let total = 0;
        tbl.rows({search:'applied'}).data().each(r => total += r.gaji);
        const f = fmt(total);
        document.getElementById('total-gaji-harian').textContent = f;
        document.getElementById('tfoot-total').textContent = f;
    };

    const table = $('#tbl-gh').DataTable({
        data,
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:null,render:(_,__,___,m)=>`<span class="text-slate-400 text-xs">${m.row+1}</span>`},
            {data:'id',render:d=>`<span class="font-mono text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg">${d}</span>`},
            {data:'nama',render:d=>`<span class="font-medium text-slate-800">${d}</span>`},
            {data:'bagian'},
            {data:'waktu'},
            {data:'gaji',render:d=>`<span class="font-semibold text-slate-800">${fmt(d)}</span>`},
            {data:null,orderable:false,searchable:false,className:'text-center',
             render:()=>`<div class="flex justify-center gap-1">
               <button class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Detail"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
             </div>`},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
        drawCallback:()=>updateTotal(table),
    });
    updateTotal(table);

    document.getElementById('gh-filter').onclick=()=>table.column(3).search(document.getElementById('gh-bagian').value).draw();
    document.getElementById('gh-reset').onclick=()=>{['gh-tgl','gh-bagian'].forEach(id=>document.getElementById(id).value='');table.search('').columns().search('').draw();};
})();
</script>
@endpush
