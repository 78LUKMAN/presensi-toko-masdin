@extends('layouts.admin')
@section('title', 'Presensi Bermasalah')
@section('page-title', 'Presensi Bermasalah')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Presensi Bermasalah</h1>
            <p class="text-sm text-slate-500">Daftar presensi yang memerlukan tinjauan atau persetujuan.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> 3 Pending
        </span>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                <input type="date" id="pb-tgl" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bagian</label>
                <select id="pb-bagian" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bagian</option>
                    <option>Produksi</option><option>Gudang</option><option>Administrasi</option><option>Keamanan</option>
                </select>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                <select id="pb-status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option>Pending</option><option>Done</option><option>Decline</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button id="pb-filter" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl">Terapkan</button>
                <button id="pb-reset" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl">Reset</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="p-5 overflow-x-auto">
            <table id="tbl-pb" class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Approval</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Approval --}}
<div id="modal-approval" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800">Tindak Lanjut Presensi</h3>
                <button type="button" data-hs-overlay="#modal-approval" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Keterangan Masalah</p>
                    <p class="text-sm text-amber-800" id="modal-ket">–</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan Keputusan</label>
                    <textarea rows="3" placeholder="Tulis catatan..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="btn-approve" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl">✓ Setujui (Done)</button>
                    <button type="button" id="btn-decline" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-xl">✗ Tolak (Decline)</button>
                </div>
            </div>
            <input type="hidden" id="modal-id">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    function overlay(a,s){if(typeof HSOverlay!=='undefined'){HSOverlay[a](s);}else{const t=setInterval(()=>{if(typeof HSOverlay!=='undefined'){clearInterval(t);HSOverlay[a](s);}},30);}}
    const badge=s=>{const c={Pending:'bg-amber-100 text-amber-700',Done:'bg-emerald-100 text-emerald-700',Decline:'bg-red-100 text-red-700'};return`<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${c[s]}">${s}</span>`;};
    const table=$('#tbl-pb').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.presensi-bermasalah.data") }}',
            data: function(d) {
                d.date = document.getElementById('pb-tgl').value;
                d.section = document.getElementById('pb-bagian').value;
                d.status = document.getElementById('pb-status').value;
            }
        },
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'date'},
            {data:'nama',render:d=>`<span class="font-medium text-slate-800">${d}</span>`},
            {data:'bagian'},
            {data:'ket'},
            {data:'status',render:d=>badge(d)},
            {data:null,orderable:false,searchable:false,className:'text-center',
             render:(_,__,row)=>`<div class="flex justify-center gap-1">
               <button onclick="bukaApv(${row.id}, '${row.ket.replace(/'/g, "\\'")}')" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Tindak Lanjut"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
             </div>`},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
    });
    window.bukaApv=(id, ket)=>{
        document.getElementById('modal-id').value=id;
        document.getElementById('modal-ket').textContent=ket;
        overlay('open','#modal-approval');
    };
    
    const sendApproval = (status) => {
        const id = document.getElementById('modal-id').value;
        const notes = document.querySelector('textarea').value;
        
        fetch(`/admin/presensi-bermasalah/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ approval_status: status, notes: notes })
        })
        .then(r => r.json())
        .then(res => {
            overlay('close','#modal-approval');
            table.draw();
        })
        .catch(e => alert('Terjadi kesalahan.'));
    };

    document.getElementById('btn-approve').onclick = () => sendApproval('Done');
    document.getElementById('btn-decline').onclick = () => sendApproval('Decline');

    document.getElementById('pb-filter').onclick=()=>table.draw();
    document.getElementById('pb-reset').onclick=()=>{['pb-tgl','pb-bagian','pb-status'].forEach(id=>document.getElementById(id).value='');table.draw();};
})();
</script>
@endpush
