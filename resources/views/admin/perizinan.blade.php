@extends('layouts.admin')
@section('title', 'Perizinan & Cuti')
@section('page-title', 'Perizinan & Cuti')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Daftar Pengajuan Izin & Cuti</h1>
            <p class="text-sm text-slate-500">Tinjau dan kelola pengajuan izin, sakit, atau cuti dari karyawan.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
            <i class="fa-solid fa-file-lines"></i> Pengajuan
        </span>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                <input type="date" id="p-tgl" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"/>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Cari Nama</label>
                <input type="text" id="p-nama" placeholder="Ketik nama karyawan..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"/>
            </div>
            <div class="flex gap-2">
                <button id="p-filter" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">Cari</button>
                <button id="p-reset" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition-colors">Reset</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-5 overflow-x-auto">
            <table id="tbl-perizinan" class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Approval --}}
<div id="modal-permit" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto bg-black/40">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Detail Pengajuan</h3>
                <button type="button" data-hs-overlay="#modal-permit" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                {{-- Detail Box --}}
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Nama</p>
                            <p class="text-sm font-semibold text-slate-700" id="modal-nama">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Tanggal</p>
                            <p class="text-sm font-semibold text-slate-700" id="modal-tanggal">-</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Jenis</p>
                        <span id="modal-jenis" class="inline-block mt-0.5 px-2 py-0.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">-</span>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Keterangan / Alasan</p>
                        <p class="text-sm text-slate-600 mt-1 italic" id="modal-ket">-</p>
                    </div>
                    <div id="modal-attachment-container" class="hidden">
                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Lampiran</p>
                        <a href="#" id="modal-attachment-link" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 transition-colors">
                            <i class="fa-solid fa-paperclip"></i> Lihat Lampiran
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2" id="modal-actions">
                    <button type="button" id="btn-reject" class="w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 text-sm font-bold rounded-xl transition-all">Tolak</button>
                    <button type="button" id="btn-approve" class="w-full px-4 py-2.5 bg-blue-600 border border-blue-600 text-white hover:bg-blue-700 text-sm font-bold rounded-xl transition-all shadow-md shadow-blue-200">Setujui</button>
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
    
    const badgeMap = {
        'Pending': 'bg-amber-100 text-amber-700',
        'Done': 'bg-emerald-100 text-emerald-700',
        'Decline': 'bg-red-100 text-red-700',
        'Disetujui': 'bg-emerald-100 text-emerald-700',
        'Ditolak': 'bg-red-100 text-red-700'
    };
    
    const statusTranslate = {
        'Done': 'Disetujui',
        'Decline': 'Ditolak',
        'Pending': 'Pending'
    };

    const typeColor = {
        'Izin': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Sakit': 'bg-red-50 text-red-700 border border-red-200',
        'Cuti': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    };

    const table=$('#tbl-perizinan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.perizinan.data") }}',
            data: function(d) {
                d.date = document.getElementById('p-tgl').value;
                d.name = document.getElementById('p-nama').value;
            }
        },
        language:{url:'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json'},
        columns:[
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'date'},
            {data:'nama',render:d=>`<span class="font-bold text-slate-800">${d}</span>`},
            {data:'tipe',render:d=>`<span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ${typeColor[d] || 'bg-slate-100'}">${d}</span>`},
            {data:'ket',render:d=>`<span class="truncate max-w-[200px] inline-block">${d}</span>`},
            {data:'status',render:d=>{
                const label = statusTranslate[d] || d;
                const cls = badgeMap[label] || badgeMap[d] || 'bg-slate-100 text-slate-700';
                return `<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold ${cls}">${label}</span>`;
            }},
            {data:null,orderable:false,searchable:false,className:'text-center',
             render:(_,__,row)=>`<div class="flex justify-center gap-1">
               <button onclick='bukaDetail(${JSON.stringify(row)})' class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200 text-xs font-bold transition-colors">Lihat Detail</button>
             </div>`},
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600 align-middle'),
    });

    window.bukaDetail=(row)=>{
        document.getElementById('modal-id').value = row.id;
        document.getElementById('modal-nama').textContent = row.nama;
        document.getElementById('modal-tanggal').textContent = row.date;
        document.getElementById('modal-jenis').textContent = row.tipe;
        document.getElementById('modal-ket').textContent = row.ket;
        
        const attachContainer = document.getElementById('modal-attachment-container');
        const attachLink = document.getElementById('modal-attachment-link');
        
        if (row.attachment) {
            attachContainer.classList.remove('hidden');
            attachLink.href = '/storage/' + row.attachment;
        } else {
            attachContainer.classList.add('hidden');
        }

        const actionDiv = document.getElementById('modal-actions');
        if (row.status === 'Pending') {
            actionDiv.classList.remove('hidden');
        } else {
            actionDiv.classList.add('hidden');
        }

        overlay('open','#modal-permit');
    };
    
    const sendAction = (id, action) => {
        fetch(`/admin/perizinan/${id}/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(res => {
            overlay('close','#modal-permit');
            table.draw();
        })
        .catch(e => alert('Terjadi kesalahan.'));
    };

    document.getElementById('btn-approve').onclick = () => {
        if(confirm('Setujui pengajuan ini?')) sendAction(document.getElementById('modal-id').value, 'approve');
    };
    
    document.getElementById('btn-reject').onclick = () => {
        if(confirm('Tolak pengajuan ini?')) sendAction(document.getElementById('modal-id').value, 'reject');
    };

    document.getElementById('p-filter').onclick=()=>table.draw();
    document.getElementById('p-reset').onclick=()=>{['p-tgl','p-nama'].forEach(id=>document.getElementById(id).value='');table.draw();};
})();
</script>
@endpush
