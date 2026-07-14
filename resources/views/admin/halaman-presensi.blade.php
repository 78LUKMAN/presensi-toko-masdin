@extends('layouts.admin')
@section('title', 'Halaman Presensi')
@section('page-title', 'Halaman Presensi')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Halaman Presensi</h1>
            <p class="text-sm text-slate-500" id="live-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-sm font-semibold text-emerald-700" id="live-clock">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
        </div>
    </div>

    {{-- Main 2-column layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

        {{-- ── LEFT: Employee Attendance List ────────────────────────────── --}}
        <div class="xl:col-span-3 bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col">

            {{-- Card header --}}
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Daftar Kehadiran</p>
                        <p class="text-xs text-slate-500">Diurutkan berdasarkan jam masuk</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Summary badges --}}
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span id="badge-hadir">0</span> Hadir
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span id="badge-belum">0</span> Belum Pulang
                    </span>
                </div>
            </div>

            {{-- Search bar --}}
            <div class="px-5 py-3 border-b border-slate-50 bg-slate-50/60">
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="search-pegawai" placeholder="Cari ID atau nama pegawai..."
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="sticky top-0 z-10 bg-white shadow-sm">
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody id="presensi-tbody">
                        {{-- Filled by JS --}}
                    </tbody>
                </table>
                {{-- Empty state --}}
                <div id="empty-state" class="hidden py-16 text-center">
                    <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-slate-400">Tidak ada pegawai ditemukan</p>
                </div>
            </div>

            {{-- Footer count --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500">Total: <span id="total-count" class="font-semibold text-slate-700">0</span> pegawai</p>
                <p class="text-xs text-slate-400">Diperbarui otomatis setiap 30 detik</p>
            </div>
        </div>

        {{-- ── RIGHT: QR Code Panel ───────────────────────────────────────── --}}
        <div class="xl:col-span-2 flex flex-col gap-4">

            {{-- QR Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex-1">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">QR Code Presensi</p>
                        <p class="text-xs text-slate-500">Scan untuk melakukan presensi</p>
                    </div>
                </div>

                <div class="p-6 flex flex-col items-center">
                    {{-- QR Code container --}}
                    <div class="relative" id="qr-wrapper">
                        <div class="w-64 h-64 rounded-2xl bg-white border-2 border-slate-200 shadow-inner flex items-center justify-center overflow-hidden p-3" id="qr-container">
                            {{-- QR image loaded by JS --}}
                        </div>
                        {{-- Refresh overlay --}}
                        <div id="qr-overlay" class="hidden absolute inset-0 rounded-2xl bg-white/80 backdrop-blur-sm flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-indigo-500 mx-auto animate-spin mb-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <p class="text-xs font-semibold text-indigo-700">Memperbarui...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Timer ring --}}
                    <div class="mt-5 flex flex-col items-center gap-2">
                        <div class="relative w-16 h-16">
                            <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                                <circle cx="32" cy="32" r="28" fill="none" stroke="#e2e8f0" stroke-width="5"/>
                                <circle id="timer-ring" cx="32" cy="32" r="28" fill="none"
                                        stroke="#6366f1" stroke-width="5" stroke-linecap="round"
                                        stroke-dasharray="175.9" stroke-dashoffset="0"
                                        style="transition: stroke-dashoffset 1s linear;"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-lg font-bold text-slate-700" id="timer-text">30</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500">detik hingga refresh</p>
                    </div>

                    {{-- Manual refresh --}}
                    <button id="btn-refresh-qr" onclick="refreshQr()"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh Sekarang
                    </button>
                </div>
            </div>

            {{-- Attendance URL Info --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">URL Presensi Pegawai</p>
                <div class="flex items-center gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span class="text-xs font-mono text-slate-600 flex-1 truncate" id="presensi-url">{{ url('/presensi') }}</span>
                    <button onclick="copyUrl()" class="text-blue-600 hover:text-blue-800 flex-shrink-0" title="Salin URL">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-2 text-xs text-slate-400">QR Code mengarah ke URL ini. Pegawai scan untuk presensi masuk / pulang.</p>
            </div>

            {{-- Stats mini --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center">
                    <p class="text-2xl font-bold text-slate-800" id="stat-total">0</p>
                    <p class="text-xs text-slate-500 mt-0.5">Total</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-700" id="stat-hadir">0</p>
                    <p class="text-xs text-emerald-600 mt-0.5">Hadir</p>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4 text-center">
                    <p class="text-2xl font-bold text-slate-600" id="stat-pulang">0</p>
                    <p class="text-xs text-slate-500 mt-0.5">Pulang</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- QR Code library (no jQuery needed) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function () {
    'use strict';

    let employees = [];
    
    function loadData() {
        fetch('{{ route("admin.halaman-presensi.data") }}')
            .then(r => r.json())
            .then(data => {
                employees = data;
                renderTable(document.getElementById('search-pegawai').value);
            })
            .catch(e => console.error('Error fetching attendance data:', e));
    }

    // ── Live clock ───────────────────────────────────────────────────────
    function tickClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        document.getElementById('live-clock').textContent = `${h}:${m}:${s}`;
    }
    setInterval(tickClock, 1000);
    tickClock();

    // ── Render table ─────────────────────────────────────────────────────
    function statusBadge(masuk, pulang) {
        if (!masuk) return `<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">Belum Presensi</span>`;
        if (!pulang) return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Di Tempat</span>`;
        return `<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Pulang</span>`;
    }

    function renderTable(filter) {
        const q = (filter || '').toLowerCase();
        const tbody = document.getElementById('presensi-tbody');
        const empty = document.getElementById('empty-state');

        const filtered = employees.filter(e =>
            (e.id && e.id.toLowerCase().includes(q)) || (e.nama && e.nama.toLowerCase().includes(q))
        );

        // Update stats
        const hadirCount = employees.filter(e => e.masuk).length;
        const belumCount = employees.filter(e => e.masuk && !e.pulang).length;
        const pulangCount = employees.filter(e => e.pulang).length;

        document.getElementById('badge-hadir').textContent = hadirCount;
        document.getElementById('badge-belum').textContent = belumCount;
        document.getElementById('total-count').textContent = employees.length;
        document.getElementById('stat-total').textContent  = employees.length;
        document.getElementById('stat-hadir').textContent  = hadirCount;
        document.getElementById('stat-pulang').textContent = pulangCount;

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        tbody.innerHTML = filtered.map((e, i) => {
            const masukCell = e.masuk
                ? `<span class="font-mono text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg">${e.masuk}</span>`
                : `<span class="text-slate-300 text-xs">–</span>`;
            const pulangCell = e.pulang
                ? `<span class="font-mono text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg">${e.pulang}</span>`
                : `<span class="text-slate-300 text-xs">–</span>`;
            const rowBg = i % 2 === 0 ? '' : 'bg-slate-50/40';
            return `
            <tr class="border-b border-slate-50 hover:bg-blue-50/30 transition-colors ${rowBg}">
                <td class="px-5 py-3">
                    <span class="font-mono text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg">${e.id}</span>
                </td>
                <td class="px-5 py-3 font-medium text-slate-800">${e.nama}</td>
                <td class="px-5 py-3">${masukCell}</td>
                <td class="px-5 py-3">${pulangCell}</td>
                <td class="px-5 py-3">${statusBadge(e.masuk, e.pulang)}</td>
            </tr>`;
        }).join('');
    }

    // Initial render
    loadData();

    // Search
    document.getElementById('search-pegawai').addEventListener('input', function () {
        renderTable(this.value);
    });

    // ── QR Code generator ────────────────────────────────────────────────
    const PRESENSI_URL = document.getElementById('presensi-url').textContent.trim();
    const REFRESH_SEC = 30;

    let qrCodeInstance = null;

    function generateQr() {
        const container = document.getElementById('qr-container');
        
        fetch('{{ route("admin.generate-token") }}')
            .then(r => r.json())
            .then(data => {
                if (!qrCodeInstance) {
                    container.innerHTML = '';
                    qrCodeInstance = new QRCode(container, {
                        text: data.token,
                        width: 230,
                        height: 230,
                        colorDark : "#1e293b",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                } else {
                    qrCodeInstance.clear();
                    qrCodeInstance.makeCode(data.token);
                }
            })
            .catch(e => console.error('Error generating token:', e));
    }

    window.refreshQr = function () {
        const overlay = document.getElementById('qr-overlay');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        setTimeout(() => {
            generateQr();
            loadData(); // also refresh data when QR refreshes
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            resetTimer();
        }, 600);
    };

    // ── Countdown timer ──────────────────────────────────────────────────
    const CIRCUMFERENCE = 2 * Math.PI * 28; // r=28 → ≈175.9
    let secondsLeft = REFRESH_SEC;
    let timerInterval;

    function resetTimer() {
        secondsLeft = REFRESH_SEC;
    }

    function tickTimer() {
        secondsLeft--;
        document.getElementById('timer-text').textContent = secondsLeft;

        const offset = CIRCUMFERENCE * (1 - secondsLeft / REFRESH_SEC);
        document.getElementById('timer-ring').style.strokeDashoffset = offset;

        // Color transition: green → amber → red
        const ring = document.getElementById('timer-ring');
        if (secondsLeft > 20)      ring.setAttribute('stroke', '#6366f1'); // indigo
        else if (secondsLeft > 10) ring.setAttribute('stroke', '#f59e0b'); // amber
        else                       ring.setAttribute('stroke', '#ef4444'); // red

        if (secondsLeft <= 0) {
            window.refreshQr();
        }
    }

    // Initial QR load
    generateQr();
    timerInterval = setInterval(tickTimer, 1000);

    // ── Copy URL ─────────────────────────────────────────────────────────
    window.copyUrl = function () {
        navigator.clipboard.writeText(PRESENSI_URL).then(() => {
            const btn = document.querySelector('[onclick="copyUrl()"]');
            btn.innerHTML = `<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => {
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>`;
            }, 2000);
        });
    };

})();
</script>
@endpush
