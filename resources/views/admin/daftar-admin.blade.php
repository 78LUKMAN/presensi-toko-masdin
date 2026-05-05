@extends('layouts.admin')
@section('title', 'Daftar Admin')
@section('page-title', 'Daftar Admin')

@section('content')
<div class="space-y-4">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Daftar Admin</h1>
            <p class="text-sm text-slate-500">Kelola akun pengguna dengan akses admin.</p>
        </div>
        <button id="btn-tambah-admin" type="button"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Admin
        </button>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-40">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Cari Nama / Email</label>
                <input type="text" id="da-search" placeholder="Ketik nama atau email..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dibuat (dari)</label>
                <input type="date" id="da-dari" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div class="flex gap-2">
                <button id="da-filter" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl">Terapkan</button>
                <button id="da-reset" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl">Reset</button>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table id="table-admin" class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat</th>
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
<div id="modal-create-admin" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800">Tambah Admin</h3>
                <button type="button" data-hs-overlay="#modal-create-admin" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <div id="ca-errors" class="hidden mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>
                <form id="form-create-admin" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Nama admin" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" placeholder="admin@perusahaan.com" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Min. 6 karakter" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50">
                <button type="button" data-hs-overlay="#modal-create-admin" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-save-admin" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl flex items-center gap-2">
                    <svg id="ca-spinner" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ======================== MODAL EDIT ======================== --}}
<div id="modal-edit-admin" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-800">Edit Admin</h3>
                <button type="button" data-hs-overlay="#modal-edit-admin" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <div id="ea-errors" class="hidden mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm"></div>
                <form id="form-edit-admin" class="space-y-4">
                    <input type="hidden" id="edit-admin-id"/>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="ea-name" placeholder="Nama admin" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="ea-email" placeholder="admin@perusahaan.com" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Ubah Password (Opsional)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru</label>
                                <input type="password" name="password" id="ea-password" placeholder="Kosongkan jika tidak diubah" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="ea-password-confirm" placeholder="Ulangi password baru" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50">
                <button type="button" data-hs-overlay="#modal-edit-admin" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-update-admin" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl flex items-center gap-2">
                    <svg id="ea-spinner" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                    Perbarui
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ======================== MODAL DELETE ======================== --}}
<div id="modal-delete-admin" class="hs-overlay hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-slate-200 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Hapus Admin?</h3>
                <p class="text-sm text-slate-500">Akun admin <strong id="delete-admin-name" class="text-slate-700"></strong> akan dihapus permanen.</p>
            </div>
            <div class="flex justify-center gap-3 px-6 pb-6">
                <button type="button" data-hs-overlay="#modal-delete-admin" class="px-5 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-confirm-delete-admin" class="px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl">Ya, Hapus</button>
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
    const BASE_URL = '{{ url("admin/daftar-admin") }}';

    // Safe HSOverlay wrapper
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

    // ── DataTable (client-side dummy data) ─────────────────────────────────
    const dummyAdmin = [
        {id:1, name:'Budi Admin',    email:'budi.admin@makdin.co.id', role:'Admin', created_at:'2025-01-10'},
        {id:2, name:'Sari Utami',    email:'sari@makdin.co.id',       role:'Admin', created_at:'2025-03-05'},
        {id:3, name:'Rizal Pratama', email:'rizal@makdin.co.id',      role:'Admin', created_at:'2025-06-20'},
    ];
    const table = $('#table-admin').DataTable({
        data: dummyAdmin,
        language  : { url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/id.json' },
        columns   : [
            { data: 'DT_RowIndex', orderable: false, searchable: false, width: '40px',
              render: (_,__,___,m) => `<span class="text-slate-400 text-xs">${m.row+1}</span>` },
            { data: 'name', render: d => `<span class="font-medium text-slate-800">${d}</span>` },
            { data: 'email' },
            { data: 'role', orderable: false, searchable: false,
              render: () => `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Admin</span>` },
            { data: 'created_at' },
            { data: 'id', orderable: false, searchable: false, className: 'text-center',
              render: (id, _, row) => `
                <div class="flex items-center justify-center gap-1">
                  <button onclick="openEditAdmin(${id})" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Edit">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button onclick="openDeleteAdmin(${id}, '${(row.name+'').replace(/'/g,"\\\'")}')" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50" title="Hapus">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>` },
        ],
        createdRow: (row) => $(row).find('td').addClass('px-4 py-3 border-b border-slate-50 text-sm text-slate-600'),
    });

    // ── Helpers ────────────────────────────────────────────────────────────
    const el       = id => document.getElementById(id);
    const setErr   = (id, html) => { el(id).innerHTML = html; el(id).classList.remove('hidden'); };
    const clearErr = id => { el(id).innerHTML = ''; el(id).classList.add('hidden'); };
    const setLoad  = (sId, bId, v) => { el(sId).classList.toggle('hidden', !v); el(bId).disabled = v; };
    const fmtErr   = e => Object.values(e).flat().map(m => `<p>• ${m}</p>`).join('');

    // ── Create ─────────────────────────────────────────────────────────────
    el('btn-tambah-admin').onclick = () => {
        el('form-create-admin').reset();
        clearErr('ca-errors');
        overlay('open', '#modal-create-admin');
    };

    el('btn-save-admin').onclick = () => {
        clearErr('ca-errors');
        setLoad('ca-spinner', 'btn-save-admin', true);
        const body = new FormData(el('form-create-admin'));
        fetch(BASE_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body })
            .then(r => r.json().then(j => ({ ok: r.ok, j })))
            .then(({ ok, j }) => {
                if (!ok) { setErr('ca-errors', fmtErr(j.errors ?? { e: [j.message] })); return; }
                overlay('close', '#modal-create-admin');
                table.ajax.reload(null, false);
            })
            .catch(() => setErr('ca-errors', '<p>• Terjadi kesalahan jaringan.</p>'))
            .finally(() => setLoad('ca-spinner', 'btn-save-admin', false));
    };

    // ── Edit ───────────────────────────────────────────────────────────────
    window.openEditAdmin = id => {
        clearErr('ea-errors');
        fetch(`${BASE_URL}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(d => {
                el('edit-admin-id').value         = d.id;
                el('ea-name').value               = d.name;
                el('ea-email').value              = d.email;
                el('ea-password').value           = '';
                el('ea-password-confirm').value   = '';
                overlay('open', '#modal-edit-admin');
            });
    };

    el('btn-update-admin').onclick = () => {
        const id   = el('edit-admin-id').value;
        const body = new FormData(el('form-edit-admin'));
        body.append('_method', 'PUT');
        clearErr('ea-errors');
        setLoad('ea-spinner', 'btn-update-admin', true);
        fetch(`${BASE_URL}/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body })
            .then(r => r.json().then(j => ({ ok: r.ok, j })))
            .then(({ ok, j }) => {
                if (!ok) { setErr('ea-errors', fmtErr(j.errors ?? { e: [j.message] })); return; }
                overlay('close', '#modal-edit-admin');
                table.ajax.reload(null, false);
            })
            .catch(() => setErr('ea-errors', '<p>• Terjadi kesalahan jaringan.</p>'))
            .finally(() => setLoad('ea-spinner', 'btn-update-admin', false));
    };

    // ── Delete ─────────────────────────────────────────────────────────────
    let delId = null;
    window.openDeleteAdmin = (id, name) => {
        delId = id;
        el('delete-admin-name').textContent = name;
        overlay('open', '#modal-delete-admin');
    };
    el('btn-confirm-delete-admin').onclick = () => {
        if (!delId) return;
        fetch(`${BASE_URL}/${delId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(() => { HSOverlay.close('#modal-delete-admin'); table.ajax.reload(null, false); delId = null; });
    };
})();
</script>
@endpush
