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
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-end gap-3 shadow-sm">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Bulan & Tahun</label>
            <input type="month" id="gb-bulan" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"/>
        </div>
        <div class="flex gap-2">
            <button id="gb-filter" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">Terapkan Filter</button>
            <button id="gb-reset" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition-colors">Reset</button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="p-5 overflow-x-auto">
            <table id="tbl-gb" class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Karyawan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Total Hari</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Total Jam</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Total Gaji Bulanan</th>
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
    const fmt=n=>'Rp '+n.toLocaleString('id-ID');
    
    // Set default value to current month
    document.getElementById('gb-bulan').value = new Date().toISOString().slice(0, 7);

    const table=$('#tbl-gb').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.rekap-gaji-bulanan") }}',
            data: function(d) {
                let m = document.getElementById('gb-bulan').value;
                if(m) {
                    let p = m.split('-');
                    d.year = p[0];
                    d.month = p[1];
                }
            }
        },
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data:'nama', render: d => `<span class="font-medium text-slate-800">${d}</span>`},
            {data:'bagian'},
            {data:'total_days', className: 'text-center'},
            {data:'total_hours', className: 'text-center'},
            {data:'total_salary', className: 'text-right font-bold text-blue-600'},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600')
    });

    document.getElementById('gb-filter').onclick=()=>table.draw();
    document.getElementById('gb-reset').onclick=()=>{
        document.getElementById('gb-bulan').value = new Date().toISOString().slice(0, 7);
        table.draw();
    };
})();
</script>
@endpush
