@extends('layouts.admin')
@section('title', 'Daftar Karyawan')
@section('page-title', 'Daftar Karyawan')

@section('content')
<div class="space-y-4">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Daftar Karyawan</h1>
            <p class="text-sm text-slate-500">Kelola data seluruh karyawan perusahaan.</p>
        </div>
        <button id="btn-tambah-karyawan" type="button"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Karyawan
        </button>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bagian</label>
                <select id="ky-bagian" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bagian</option>
                    <option>Produksi</option><option>Gudang</option><option>Administrasi</option><option>Keamanan</option>
                </select>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tgl Masuk (dari)</label>
                <input type="date" id="ky-dari" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tgl Masuk (sampai)</label>
                <input type="date" id="ky-sampai" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex gap-2">
                <button id="ky-filter" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl">Terapkan</button>
                <button id="ky-reset" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl">Reset</button>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table id="table-karyawan" class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Karyawan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bagian</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Masuk</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ======================== MODAL CREATE ======================== --}}
<div id="modal-create-karyawan" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800">Tambah Karyawan</h3>
                <button type="button" data-hs-overlay="#modal-create-karyawan" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[75vh]">
                <div id="create-errors" class="hidden mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>
                <form id="form-create-karyawan" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">NOREG <span class="text-red-500">*</span></label>
                            <input type="text" name="noreg" placeholder="Nomor Registrasi" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Nama lengkap" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Departemen <span class="text-red-500">*</span></label>
                            <input type="text" name="department" placeholder="Nama departemen" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" name="join_date" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Akun Login (Opsional)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                <input type="email" name="email" placeholder="email@perusahaan.com" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                                <input type="password" name="password" placeholder="Min. 6 karakter" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50">
                <button type="button" data-hs-overlay="#modal-create-karyawan" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-save-karyawan" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl flex items-center gap-2">
                    <svg id="save-spinner" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ======================== MODAL EDIT ======================== --}}
<div id="modal-edit-karyawan" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800">Edit Karyawan</h3>
                <button type="button" data-hs-overlay="#modal-edit-karyawan" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[75vh]">
                <div id="edit-errors" class="hidden mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>
                <form id="form-edit-karyawan" class="space-y-4">
                    <input type="hidden" id="edit-id"/>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">NOREG <span class="text-red-500">*</span></label>
                            <input type="text" name="noreg" id="edit-noreg" placeholder="Nomor Registrasi" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="edit-name" placeholder="Nama lengkap" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Departemen <span class="text-red-500">*</span></label>
                            <input type="text" name="department" id="edit-department" placeholder="Nama departemen" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" name="join_date" id="edit-join-date" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Akun Login</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                <input type="email" name="email" id="edit-email" placeholder="email@perusahaan.com" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru</label>
                                <input type="password" name="password" id="edit-password" placeholder="Kosongkan jika tidak diubah" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50">
                <button type="button" data-hs-overlay="#modal-edit-karyawan" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-update-karyawan" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl flex items-center gap-2">
                    <svg id="update-spinner" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                    Perbarui
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ======================== MODAL DELETE ======================== --}}
<div id="modal-delete-karyawan" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Hapus Karyawan?</h3>
                <p class="text-sm text-slate-500 mb-1">Data karyawan <strong id="delete-karyawan-name" class="text-slate-700"></strong> akan dihapus permanen.</p>
                <p class="text-xs text-red-500">Akun login terkait juga akan ikut terhapus.</p>
            </div>
            <div class="flex justify-center gap-3 px-6 pb-6">
                <button type="button" data-hs-overlay="#modal-delete-karyawan" class="px-5 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-confirm-delete-karyawan" class="px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';
    const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
    const BASE_URL = '{{ url("admin/karyawan") }}';

    // Safe HSOverlay wrapper – Preline loads as ES module (deferred),
    // so we poll until it is ready before calling open/close.
    function overlay(action, selector) {
        if (typeof HSOverlay !== 'undefined') {
            HSOverlay[action](selector);
        } else {
            const timer = setInterval(() => {
                if (typeof HSOverlay !== 'undefined') {
                    clearInterval(timer);
                    HSOverlay[action](selector);
                }
            }, 30);
        }
    }

    // ── DataTable (client-side dummy data) ────────────────────────────────
    const dummyKaryawan = [
        {id:1, noreg:'KRY-001', name:'Budi Santoso',    department:'Produksi',     join_date:'2023-01-15', email:'budi@makdin.co.id'},
        {id:2, noreg:'KRY-002', name:'Siti Rahayu',     department:'Gudang',       join_date:'2023-02-01', email:'siti@makdin.co.id'},
        {id:3, noreg:'KRY-003', name:'Ahmad Fauzi',     department:'Administrasi', join_date:'2023-03-10', email:'ahmad@makdin.co.id'},
        {id:4, noreg:'KRY-004', name:'Dewi Lestari',    department:'Produksi',     join_date:'2023-04-05', email:'-'},
        {id:5, noreg:'KRY-005', name:'Eko Prasetyo',    department:'Keamanan',     join_date:'2023-05-20', email:'eko@makdin.co.id'},
        {id:6, noreg:'KRY-006', name:'Fitri Handayani', department:'Administrasi', join_date:'2023-06-01', email:'fitri@makdin.co.id'},
        {id:7, noreg:'KRY-007', name:'Gunawan Putra',   department:'Gudang',       join_date:'2023-07-15', email:'-'},
        {id:8, noreg:'KRY-008', name:'Hani Sulistyani', department:'Produksi',     join_date:'2023-08-01', email:'hani@makdin.co.id'},
        {id:9, noreg:'KRY-009', name:'Irfan Maulana',   department:'Keamanan',     join_date:'2024-01-10', email:'irfan@makdin.co.id'},
        {id:10,noreg:'KRY-010', name:'Juwita Sari',     department:'Administrasi', join_date:'2024-02-20', email:'juwita@makdin.co.id'},
    ];
    const table = $('#table-karyawan').DataTable({
        data: dummyKaryawan,
        language  : { url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json' },
        columns   : [
            { data: null, render: (_,__,___,m) => `<span class="text-slate-400 text-xs">${m.row+1}</span>` },
            { data: 'noreg', render: d => `<span class="font-mono text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg">${d}</span>` },
            { data: 'name', render: d => `<span class="font-medium text-slate-800">${d}</span>` },
            { data: 'department' },
            { data: 'join_date' },
            { data: 'email', render: d => d !== '-' ? `<span class="text-slate-600">${d}</span>` : `<span class="text-slate-300">–</span>` },
            { data: null, orderable: false, searchable: false, className: 'text-center',
              render: (_,__,row) => `
                <div class="flex items-center justify-center gap-1">
                  <button onclick="openEdit(${row.id})" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Edit">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button onclick="openDelete(${row.id}, '${(row.name+'').replace(/'/g,"\\\'")}')"
                          class="p-1.5 rounded-lg text-red-500 hover:bg-red-50" title="Hapus">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>` },
        ],
        createdRow: (row) => $(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
    });

    document.getElementById('ky-filter').onclick = () =>
        table.column(3).search(document.getElementById('ky-bagian').value).draw();
    document.getElementById('ky-reset').onclick = () => {
        ['ky-bagian','ky-dari','ky-sampai'].forEach(id => document.getElementById(id).value = '');
        table.search('').columns().search('').draw();
    };

    // ── Helpers ────────────────────────────────────────────────────────────
    const errEl    = id => document.getElementById(id);
    const setErr   = (id, html) => { errEl(id).innerHTML = html; errEl(id).classList.remove('hidden'); };
    const clearErr = id => { errEl(id).innerHTML = ''; errEl(id).classList.add('hidden'); };
    const setLoad  = (sId, bId, v) => { errEl(sId).classList.toggle('hidden', !v); errEl(bId).disabled = v; };
    const fmtErr   = e => Object.values(e).flat().map(m => `<p>• ${m}</p>`).join('');

    // ── Create ─────────────────────────────────────────────────────────────
    document.getElementById('btn-tambah-karyawan').onclick = () => {
        document.getElementById('form-create-karyawan').reset();
        clearErr('create-errors');
        overlay('open', '#modal-create-karyawan');
    };

    document.getElementById('btn-save-karyawan').onclick = () => {
        clearErr('create-errors');
        setLoad('save-spinner', 'btn-save-karyawan', true);
        const body = new FormData(document.getElementById('form-create-karyawan'));
        fetch(BASE_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body })
            .then(r => r.json().then(j => ({ ok: r.ok, j })))
            .then(({ ok, j }) => {
                if (!ok) { setErr('create-errors', fmtErr(j.errors ?? { e: [j.message] })); return; }
                overlay('close', '#modal-create-karyawan');
                table.ajax.reload(null, false);
            })
            .catch(() => setErr('create-errors', '<p>• Terjadi kesalahan jaringan.</p>'))
            .finally(() => setLoad('save-spinner', 'btn-save-karyawan', false));
    };

    // ── Edit ───────────────────────────────────────────────────────────────
    window.openEdit = id => {
        clearErr('edit-errors');
        fetch(`${BASE_URL}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(d => {
                document.getElementById('edit-id').value         = d.id;
                document.getElementById('edit-noreg').value      = d.noreg;
                document.getElementById('edit-name').value       = d.name;
                document.getElementById('edit-department').value = d.department;
                document.getElementById('edit-join-date').value  = d.join_date;
                document.getElementById('edit-email').value      = d.email ?? '';
                document.getElementById('edit-password').value   = '';
                overlay('open', '#modal-edit-karyawan');
            });
    };

    document.getElementById('btn-update-karyawan').onclick = () => {
        const id   = document.getElementById('edit-id').value;
        const body = new FormData(document.getElementById('form-edit-karyawan'));
        body.append('_method', 'PUT');
        clearErr('edit-errors');
        setLoad('update-spinner', 'btn-update-karyawan', true);
        fetch(`${BASE_URL}/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body })
            .then(r => r.json().then(j => ({ ok: r.ok, j })))
            .then(({ ok, j }) => {
                if (!ok) { setErr('edit-errors', fmtErr(j.errors ?? { e: [j.message] })); return; }
                overlay('close', '#modal-edit-karyawan');
                table.ajax.reload(null, false);
            })
            .catch(() => setErr('edit-errors', '<p>• Terjadi kesalahan jaringan.</p>'))
            .finally(() => setLoad('update-spinner', 'btn-update-karyawan', false));
    };

    // ── Delete ─────────────────────────────────────────────────────────────
    let delId = null;
    window.openDelete = (id, name) => {
        delId = id;
        document.getElementById('delete-karyawan-name').textContent = name;
        overlay('open', '#modal-delete-karyawan');
    };
    document.getElementById('btn-confirm-delete-karyawan').onclick = () => {
        if (!delId) return;
        fetch(`${BASE_URL}/${delId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(() => { overlay('close', '#modal-delete-karyawan'); table.ajax.reload(null, false); delId = null; });
    };
})();
</script>
@endpush
