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

        {{-- Filter --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap items-end gap-3">
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

                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>

            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');



    const table = $('#tbl-gh').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.rekap-gaji-harian") }}',
            data: function(d) {
                d.date = document.getElementById('gh-tgl').value;
                d.section = document.getElementById('gh-bagian').value;
            }
        },
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data:'id_karyawan'},
            {data:'nama'},
            {data:'bagian'},
            {data:'waktu'},

            {data:null,orderable:false,searchable:false,className:'text-center',
             render:()=>`<div class="flex justify-center gap-1">
               <button class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Detail"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
             </div>`},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600')
    });


    document.getElementById('gh-filter').onclick=()=>table.draw();
    document.getElementById('gh-reset').onclick=()=>{['gh-tgl','gh-bagian'].forEach(id=>document.getElementById(id).value='');table.draw();};
})();
</script>
@endpush
