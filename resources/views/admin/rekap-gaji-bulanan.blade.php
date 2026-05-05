@extends('layouts.admin')
@section('title', 'Rekap Gaji Bulanan')
@section('page-title', 'Rekap Gaji Bulanan')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Rekap Gaji Bulanan</h1>
            <p class="text-sm text-slate-500">Rekap total penggajian bulanan seluruh karyawan.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </button>
    </div>

    {{-- Summary Cards + Filter --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <div class="lg:col-span-1 space-y-3">
            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-purple-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-purple-200 mb-1">Total Gaji Bulanan</p>
                <p class="text-2xl font-bold" id="total-gb">Rp 0</p>
                <p class="text-xs text-purple-200 mt-2">Filter aktif bulan ini</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Hari Kerja</span>
                    <span class="font-semibold text-slate-700" id="stat-kerja">–</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Hari Libur</span>
                    <span class="font-semibold text-slate-700" id="stat-libur">–</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Total Karyawan</span>
                    <span class="font-semibold text-slate-700" id="stat-karyawan">–</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bulan</label>
                <input type="month" id="gb-bulan" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tipe Hari</label>
                <select id="gb-tipe" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tipe</option>
                    <option>Hari Kerja</option><option>Hari Libur</option><option>Lembur</option>
                </select>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Karyawan</label>
                <select id="gb-karyawan" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Karyawan</option>
                    <option>Budi Santoso</option><option>Siti Rahayu</option><option>Ahmad Fauzi</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button id="gb-filter" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl">Terapkan</button>
                <button id="gb-reset" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl">Reset</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="p-5 overflow-x-auto">
            <table id="tbl-gb" class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe Hari</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Karyawan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Rerata Gaji</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Gaji</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-200 bg-slate-50">
                        <td colspan="6" class="px-4 py-3 text-sm font-bold text-slate-700 text-right">Total Gaji Bulanan</td>
                        <td class="px-4 py-3 text-sm font-bold text-purple-700" id="tfoot-gb">–</td>
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
    const fmt=n=>'Rp '+n.toLocaleString('id-ID');
    const tipeBadge=t=>{const c={'Hari Kerja':'bg-blue-100 text-blue-700','Hari Libur':'bg-slate-100 text-slate-600','Lembur':'bg-orange-100 text-orange-700'};return`<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${c[t]||'bg-slate-100 text-slate-600'}">${t}</span>`;};
    const data=[
        {tgl:'2026-05-01',tipe:'Hari Kerja',ket:'Hari Normal',karyawan:'Budi Santoso',rerata:90000,total:720000},
        {tgl:'2026-05-02',tipe:'Hari Kerja',ket:'Hari Normal',karyawan:'Siti Rahayu',rerata:88000,total:704000},
        {tgl:'2026-05-03',tipe:'Hari Libur',ket:'Libur Nasional',karyawan:'Ahmad Fauzi',rerata:0,total:0},
        {tgl:'2026-05-04',tipe:'Hari Kerja',ket:'Hari Normal',karyawan:'Dewi Lestari',rerata:90000,total:720000},
        {tgl:'2026-05-05',tipe:'Lembur',ket:'Lembur Minggu',karyawan:'Eko Prasetyo',rerata:120000,total:960000},
        {tgl:'2026-05-06',tipe:'Hari Kerja',ket:'Hari Normal',karyawan:'Fitri Handayani',rerata:91000,total:728000},
        {tgl:'2026-05-07',tipe:'Hari Kerja',ket:'Hari Normal',karyawan:'Gunawan Putra',rerata:87000,total:696000},
        {tgl:'2026-05-08',tipe:'Lembur',ket:'Lembur Proyek',karyawan:'Budi Santoso',rerata:125000,total:1000000},
    ];

    const updateStats = tbl => {
        let total=0, kerja=0, libur=0, karyawanSet=new Set();
        tbl.rows({search:'applied'}).data().each(r=>{total+=r.total;if(r.tipe==='Hari Kerja')kerja++;if(r.tipe==='Hari Libur')libur++;karyawanSet.add(r.karyawan);});
        const f=fmt(total);
        document.getElementById('total-gb').textContent=f;
        document.getElementById('tfoot-gb').textContent=f;
        document.getElementById('stat-kerja').textContent=kerja+' hari';
        document.getElementById('stat-libur').textContent=libur+' hari';
        document.getElementById('stat-karyawan').textContent=karyawanSet.size+' orang';
    };

    const table=$('#tbl-gb').DataTable({
        data,
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:null,render:(_,__,___,m)=>`<span class="text-slate-400 text-xs">${m.row+1}</span>`},
            {data:'tgl'},
            {data:'tipe',render:d=>tipeBadge(d)},
            {data:'ket'},
            {data:'karyawan',render:d=>`<span class="font-medium text-slate-800">${d}</span>`},
            {data:'rerata',render:d=>`<span class="text-slate-700">${fmt(d)}</span>`},
            {data:'total',render:d=>`<span class="font-semibold text-slate-800">${fmt(d)}</span>`},
            {data:null,orderable:false,searchable:false,className:'text-center',
             render:()=>`<div class="flex justify-center gap-1">
               <button class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
             </div>`},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
        drawCallback:()=>updateStats(table),
    });
    updateStats(table);

    document.getElementById('gb-filter').onclick=()=>table.column(2).search(document.getElementById('gb-tipe').value).column(4).search(document.getElementById('gb-karyawan').value).draw();
    document.getElementById('gb-reset').onclick=()=>{['gb-bulan','gb-tipe','gb-karyawan'].forEach(id=>document.getElementById(id).value='');table.search('').columns().search('').draw();};
})();
</script>
@endpush
