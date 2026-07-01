{{-- resources/views/employee/scan.blade.php --}}
@extends('layouts.employee')
@section('title', 'Scan Absensi')

@section('content')
    <!-- Include HTML5-QRCode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="relative min-h-screen flex flex-col" x-data="scannerComponent" x-init="startScanner()">

        {{-- ══════════════════════════════════════
        DARK CAMERA OVERLAY
        ════════════════════════════════════════ --}}
        <div class="absolute inset-0 bg-black/85 z-0"></div>

        {{-- Top bar --}}
        <div class="relative z-10 flex items-center justify-between px-5 pt-12 pb-4">
            <a href="{{ route('employee.dashboard') }}"
                class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-chevron-left text-white"></i>
            </a>
            <h1 class="text-white font-bold text-base">Scan Absensi</h1>
            <button class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-lightbulb text-white"></i>
            </button>
        </div>

        {{-- Instruction text --}}
        <div class="relative z-10 text-center px-6 pt-2 pb-5">
            <p class="text-white/70 text-sm">Arahkan kamera ke QR Code absensi</p>
        </div>

        {{-- ── QR SCAN FRAME ── --}}
        <div class="relative z-10 flex items-center justify-center flex-1 w-full px-4">
            <div class="relative w-full max-w-sm aspect-[4/3]">
                {{-- Camera container --}}
                <div id="reader"
                    class="absolute inset-0 rounded-2xl overflow-hidden [&_video]:object-cover [&_video]:w-full [&_video]:h-full shadow-lg border border-white/20">
                </div>
            </div>
        </div>

        {{-- Info + demo buttons --}}
        <div class="relative z-10 px-6 pb-10 text-center space-y-4">
            <p class="text-white/60 text-xs">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Pastikan QR Code berada dalam kotak pemindai
            </p>



            <a href="{{ route('employee.dashboard') }}"
                class="block w-full py-3.5 rounded-full text-sm font-semibold text-white/80 border border-white/20 hover:bg-white/10 active:scale-95 transition-all">
                Batal
            </a>
        </div>

        {{-- ════════════════════════════════════════
        MODAL: SUCCESS
        ════════════════════════════════════════ --}}
        <div x-show="showSuccess" x-transition.opacity
            class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 px-6">
            <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl p-8 w-full max-w-xs text-center shadow-2xl">

                {{-- Success animation ring --}}
                <div class="relative w-24 h-24 mx-auto mb-5">
                    <div class="absolute inset-0 rounded-full bg-emerald-100 animate-ping opacity-30"></div>
                    <div class="relative w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-5xl"></i>
                    </div>
                </div>

                <h2 class="text-xl font-extrabold text-slate-800 mb-1" x-text="scanMessage || 'Presensi Berhasil!'"></h2>
                <p class="text-sm text-slate-400 mb-1">Tercatat</p>
                <p class="text-2xl font-bold text-[#1E2A5E] mb-6"><span x-text="scanTime"></span> WIB</p>

                <div class="bg-slate-50 rounded-xl p-3 mb-6 text-left space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Nama</span>
                        <span class="font-semibold text-slate-700">{{ auth()->user()->name ?? 'Budi Santoso' }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Tanggal</span>
                        <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Tipe</span>
                        <span class="font-semibold text-emerald-600" x-text="scanType"></span>
                    </div>
                </div>

                <button @click="showSuccess = false; window.location='{{ route('employee.dashboard') }}'"
                    class="w-full py-4 rounded-full font-bold text-white text-sm active:scale-95 transition-all"
                    style="background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fa-solid fa-house mr-2"></i>OK, Kembali ke Beranda
                </button>
            </div>
        </div>

        {{-- ════════════════════════════════════════
        MODAL: FAIL
        ════════════════════════════════════════ --}}
        <div x-show="showFail" x-transition.opacity
            class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 px-6">
            <div x-show="showFail" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl p-8 w-full max-w-xs text-center shadow-2xl">

                {{-- Fail animation ring --}}
                <div class="relative w-24 h-24 mx-auto mb-5">
                    <div class="absolute inset-0 rounded-full bg-red-100 animate-ping opacity-30"></div>
                    <div class="relative w-24 h-24 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fa-solid fa-circle-xmark text-red-500 text-5xl"></i>
                    </div>
                </div>

                <p class="text-xs font-bold tracking-widest text-red-400 uppercase mb-1">MAAF</p>
                <h2 class="text-xl font-extrabold text-slate-800 mb-2">Presensi Gagal</h2>
                <p class="text-sm text-slate-400 mb-6">QR Code tidak valid atau sudah kedaluwarsa.<br>Coba lagi dengan QR
                    yang baru.</p>

                <div class="space-y-3">
                    <button @click="showFail = false; startScanner();"
                        class="w-full py-4 rounded-full font-bold text-white text-sm active:scale-95 transition-all"
                        style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                        <i class="fa-solid fa-rotate-right mr-2"></i>Coba Lagi
                    </button>
                    <a href="{{ route('employee.dashboard') }}"
                        class="block w-full py-3.5 rounded-full font-semibold text-slate-500 text-sm border border-slate-200 active:scale-95 transition-all">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let html5QrCodeInstance = null;

        document.addEventListener('alpine:init', () => {
            Alpine.data('scannerComponent', () => ({
                showSuccess: false,
                showFail: false,
                scanMessage: '',
                scanTime: '',
                scanType: '',

                async processScan(result) {
                    try {
                        const res = await fetch('{{ route("employee.scan.process") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ token: result })
                        });

                        const data = await res.json();

                        if (data.success) {
                            this.scanMessage = data.message;
                            this.scanTime = data.time;
                            this.scanType = data.type;
                            this.showSuccess = true;
                        } else {
                            this.showFail = true;
                        }
                    } catch (err) {
                        console.error(err);
                        this.showFail = true;
                    }
                },

                startScanner() {
                    const readerElement = document.getElementById("reader");
                    if (!readerElement) {
                        console.error("Scanner element #reader not found in DOM");
                        return;
                    }

                    if (html5QrCodeInstance && html5QrCodeInstance.isScanning) {
                        return;
                    }

                    if (!html5QrCodeInstance) {
                        html5QrCodeInstance = new Html5Qrcode("reader");
                    }
                    const config = { fps: 60, qrbox: { width: 250, height: 250 } };

                    html5QrCodeInstance.start({ facingMode: "environment" }, config, (decodedText, decodedResult) => {
                        html5QrCodeInstance.stop().then(() => {
                            this.processScan(decodedText);
                        }).catch(err => {
                            console.error("Error stopping scanner:", err);
                            this.processScan(decodedText);
                        });
                    }, (errorMessage) => {
                        // parse error, ignore it.
                    }).catch((err) => {
                        console.error("Camera access or init error:", err);
                    });
                }
            }));
        });
    </script>
@endpush