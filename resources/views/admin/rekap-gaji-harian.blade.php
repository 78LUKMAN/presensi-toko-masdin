@extends('layouts.admin')
@section('title', 'Rekap Gaji Harian')
@section('page-title', 'Rekap Gaji Harian')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Rekap Gaji Harian</h1>
            <p class="text-sm text-slate-500">Rekap perhitungan gaji harian berdasarkan jam kerja pegawai.</p>
        </div>
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
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Pegawai</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Honor Hari Ini</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Gaji</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Edit Gaji --}}
<div id="modal-gaji" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm z-0" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-10 inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 id="modal-gaji-title" class="text-base font-bold text-slate-800">Edit Honor Hari Ini</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="form-gaji" class="p-6 space-y-4">
                <input type="hidden" id="edit-id">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Honor (Rp)</label>
                    <input type="text" id="edit-honor" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Catatan</label>
                    <textarea id="edit-notes" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
    const fmt = n => 'Rp ' + Math.round(Number(n || 0)).toLocaleString('id-ID');
    
    const formatNumberInput = (value) => {
        let clean = String(value).replace(/\D/g, '');
        if (!clean) return '';
        return Number(clean).toLocaleString('id-ID');
    };

    const statusBadge = (status) => {
        const map = {
            'auto': '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Otomatis</span>',
            'pending_manual': '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 animate-pulse">Perlu Input Admin</span>',
            'manual': '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Manual</span>',
        };
        return map[status] || '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">-</span>';
    };

    const inputHonor = document.getElementById('edit-honor');
    inputHonor.addEventListener('input', function(e) {
        let cursorPosition = this.selectionStart;
        const oldLength = this.value.length;
        
        const formatted = formatNumberInput(this.value);
        this.value = formatted;
        
        const newLength = this.value.length;
        cursorPosition = cursorPosition + (newLength - oldLength);
        this.setSelectionRange(cursorPosition, cursorPosition);
    });

    // Don't default to today — show all dates so the table isn't empty

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
            {data:'id_pegawai', name: 'id_pegawai'},
            {data:'nama', name: 'nama'},
            {data:'bagian', name: 'bagian'},
            {data:'waktu'},
            {data:'gaji', render: (data, type, row) => {
                if (row.salary_status === 'pending_manual') {
                    return `<span class="font-bold text-amber-600">${fmt(data)} <span class="text-xs">(belum final)</span></span>`;
                }
                return `<span class="font-bold text-slate-700">${fmt(data)}</span>`;
            }},
            {data:'salary_status', orderable: false, searchable: false, render: data => statusBadge(data)},
            {data:null,orderable:false,searchable:false,className:'text-center',
             render:(row)=>{
                // If salary exists, show edit button
                if (row.salary_id) {
                    const btnClass = row.salary_status === 'pending_manual'
                        ? 'p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 ring-2 ring-amber-200'
                        : 'p-1.5 rounded-lg text-blue-600 hover:bg-blue-50';
                    const title = row.salary_status === 'pending_manual' ? 'Input Honor (Wajib)' : 'Edit Honor';
                    return `<div class="flex justify-center gap-1">
                        <button onclick='editGaji(${JSON.stringify(row)})' class="${btnClass}" title="${title}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </div>`;
                }
                // No salary yet — show 'create' button if attendance exists but no salary
                if (row.employee_id) {
                    return `<div class="flex justify-center gap-1">
                        <button onclick='createGaji(${JSON.stringify(row)})' class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-xs font-semibold" title="Tambah Honor">
                            + Tambah
                        </button>
                    </div>`;
                }
                return `<span class="text-xs text-slate-400 italic">Belum Presensi Pulang</span>`;
             }
            },
        ],
        createdRow:row=>$(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600')
    });

    window.editGaji = (row) => {
        const hours = row.total_hours || 0;
        const defaultHonor = hours > 9
            ? Math.round(50000 + (hours - 9) * (50000 / 9 + 5000))
            : Math.round(hours * (50000 / 9));

        document.getElementById('edit-id').value = row.salary_id;
        const rawVal = Math.round((row.gaji > 0 || !hours) ? row.gaji : defaultHonor);
        document.getElementById('edit-honor').value = formatNumberInput(rawVal);
        document.getElementById('edit-notes').value = '';
        document.getElementById('form-gaji').dataset.mode = 'edit';
        document.getElementById('modal-gaji-title').textContent = row.salary_status === 'pending_manual'
            ? 'Input Honor (Jam Kerja < 9 Jam)'
            : 'Edit Honor Hari Ini';
        document.getElementById('modal-gaji').classList.remove('hidden');
    };

    window.createGaji = (row) => {
        const hours = row.total_hours || 0;
        const defaultHonor = hours > 9
            ? Math.round(50000 + (hours - 9) * (50000 / 9 + 5000))
            : Math.round(hours * (50000 / 9));

        document.getElementById('edit-id').value = '';
        const rawVal = defaultHonor > 0 ? Math.round(defaultHonor) : '';
        document.getElementById('edit-honor').value = rawVal ? formatNumberInput(rawVal) : '';
        document.getElementById('edit-notes').value = '';
        document.getElementById('form-gaji').dataset.mode = 'create';
        document.getElementById('form-gaji').dataset.employeeId = row.employee_id;
        document.getElementById('form-gaji').dataset.date = row.attendance_date;
        document.getElementById('form-gaji').dataset.totalHours = hours;
        document.getElementById('modal-gaji-title').textContent = 'Tambah Honor Pegawai';
        document.getElementById('modal-gaji').classList.remove('hidden');
    };

    window.closeModal = () => {
        document.getElementById('modal-gaji').classList.add('hidden');
    };

    document.getElementById('form-gaji').onsubmit = function(e) {
        e.preventDefault();
        const mode = this.dataset.mode;
        const honor = document.getElementById('edit-honor').value.replace(/\D/g, '');
        const notes = document.getElementById('edit-notes').value;

        if (mode === 'edit') {
            // Update existing salary
            const id = document.getElementById('edit-id').value;
            fetch(`{{ url('admin/rekap-gaji-harian') }}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ salary_amount: honor, notes: notes })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    closeModal();
                    table.draw(false);
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        } else {
            // Create new salary
            fetch(`{{ route('admin.rekap-gaji-harian.store') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_id: this.dataset.employeeId,
                    date: this.dataset.date,
                    total_hours: this.dataset.totalHours,
                    salary_amount: honor,
                    notes: notes
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    closeModal();
                    table.draw(false);
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        }
    };

    document.getElementById('gh-filter').onclick=()=>table.draw();
    document.getElementById('gh-reset').onclick=()=>{
        document.getElementById('gh-tgl').value = '';
        document.getElementById('gh-bagian').value='';
        table.draw();
    };
})();
</script>
@endpush
