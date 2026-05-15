{{-- resources/views/admin/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Admin Login – MasDin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0F172A 0%, #1E2A5E 40%, #2D3F8F 70%, #3B82F6 100%); }
        .glass-card { background: rgba(255,255,255,0.97); backdrop-filter: blur(20px); }
        .dot-pattern { background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 24px 24px; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float-slow { animation: float 6s ease-in-out infinite; }
        .float-med  { animation: float 4s ease-in-out infinite 1s; }
        input:-webkit-autofill { -webkit-box-shadow: 0 0 0 30px #f8fafc inset !important; }
    </style>
</head>
<body class="min-h-screen dot-pattern antialiased" x-data="{ showPass: false, loading: false }">

    {{-- ── Decorative blobs ── --}}
    <div class="fixed top-0 left-0 w-96 h-96 rounded-full opacity-20 pointer-events-none float-slow"
         style="background:radial-gradient(circle,#3B82F6,transparent 70%); transform:translate(-30%,-30%)"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 rounded-full opacity-15 pointer-events-none float-med"
         style="background:radial-gradient(circle,#6366f1,transparent 70%); transform:translate(30%,30%)"></div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-5xl flex rounded-3xl shadow-2xl overflow-hidden" style="min-height:580px">

            {{-- ════════════════════
                 LEFT PANEL (branding)
            ════════════════════ --}}
            <div class="hidden lg:flex flex-col justify-between w-1/2 p-12 relative overflow-hidden"
                 style="background: linear-gradient(145deg, #1E2A5E 0%, #2D3F8F 50%, #3B82F6 100%);">

                {{-- Decorative circles --}}
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5" style="transform:translate(30%,-30%)"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full bg-white/5" style="transform:translate(-30%,30%)"></div>

                {{-- Top: Logo --}}
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mb-5 shadow-lg">
                        <i class="fa-solid fa-fingerprint text-white text-3xl"></i>
                    </div>
                    <h1 class="text-white text-3xl font-extrabold tracking-tight mb-1">MAKDIN</h1>
                    <p class="text-blue-200 text-sm font-medium">Sistem Manajemen Kehadiran</p>
                </div>

                {{-- Middle: Feature list --}}
                <div class="relative space-y-5">
                    <p class="text-white/50 text-xs font-bold uppercase tracking-widest mb-4">Fitur Admin Panel</p>
                    @foreach([
                        ['fa-users-gear',      'Manajemen Karyawan', 'Kelola data & akses karyawan'],
                        ['fa-calendar-check',  'Monitoring Absensi', 'Pantau kehadiran real-time'],
                        ['fa-circle-dollar-to-slot', 'Rekap Penggajian', 'Laporan gaji harian & bulanan'],
                        ['fa-chart-line',      'Laporan & Statistik', 'Analitik kehadiran mendalam'],
                    ] as [$icon, $title, $sub])
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $icon }} text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold">{{ $title }}</p>
                            <p class="text-blue-200/70 text-xs">{{ $sub }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Bottom: Version --}}
                <div class="relative">
                    <p class="text-white/30 text-xs">© {{ date('Y') }} MasDin · v1.0.0</p>
                </div>
            </div>

            {{-- ════════════════════
                 RIGHT PANEL (form)
            ════════════════════ --}}
            <div class="glass-card flex-1 flex flex-col justify-center px-8 sm:px-12 py-10">

                {{-- Mobile logo (hidden on lg) --}}
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#1E2A5E,#3B82F6)">
                        <i class="fa-solid fa-fingerprint text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[#1E2A5E] font-extrabold text-lg leading-tight">MAKDIN</p>
                        <p class="text-slate-400 text-xs">Admin Panel</p>
                    </div>
                </div>

                <div class="mb-8">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-50 px-3 py-1.5 rounded-full mb-3">
                        <i class="fa-solid fa-shield-halved text-blue-500"></i> Area Terproteksi
                    </span>
                    <h2 class="text-[#1E2A5E] text-2xl sm:text-3xl font-extrabold leading-tight">Selamat Datang<br>Kembali 👋</h2>
                    <p class="text-slate-400 text-sm mt-2">Masuk ke panel admin untuk mengelola sistem absensi.</p>
                </div>

                {{-- Alerts --}}
                @if(session('error'))
                    <div class="mb-5 flex items-start gap-3 px-4 py-3.5 rounded-xl bg-red-50 border border-red-200">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-5 flex items-start gap-3 px-4 py-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" @submit="loading = true" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Admin</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">
                                <i class="fa-regular fa-envelope text-base"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="admin@makdin.co.id"
                                   class="w-full pl-11 pr-4 py-3.5 rounded-xl border text-sm text-slate-800 bg-slate-50 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/25 focus:border-[#1E2A5E] focus:bg-white transition-all
                                          @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror"/>
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                            <a href="{{ route('admin.forgot-password') }}"
                               class="text-xs font-semibold text-blue-600 hover:text-[#1E2A5E] transition-colors">
                                Lupa Password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">
                                <i class="fa-solid fa-lock text-base"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'" name="password" required
                                   placeholder="••••••••"
                                   class="w-full pl-11 pr-12 py-3.5 rounded-xl border text-sm text-slate-800 bg-slate-50 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/25 focus:border-[#1E2A5E] focus:bg-white transition-all
                                          @error('password') border-red-400 bg-red-50 @else border-slate-200 @enderror"/>
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                                <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-base"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center gap-3">
                        <div class="relative flex items-center" x-data="{ checked: false }">
                            <input type="checkbox" name="remember" id="remember" x-model="checked"
                                   class="sr-only peer"/>
                            <div @click="checked = !checked"
                                 class="w-5 h-5 rounded-md border-2 cursor-pointer flex items-center justify-center transition-all"
                                 :class="checked ? 'bg-[#1E2A5E] border-[#1E2A5E]' : 'bg-white border-slate-300'">
                                <i class="fa-solid fa-check text-white text-[10px]" x-show="checked"></i>
                            </div>
                        </div>
                        <label for="remember" class="text-sm text-slate-600 font-medium cursor-pointer select-none">
                            Ingat saya selama 30 hari
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" :disabled="loading"
                            class="w-full py-4 rounded-xl font-bold text-white text-base shadow-lg transition-all active:scale-[0.98] disabled:opacity-70"
                            style="background: linear-gradient(135deg, #1E2A5E 0%, #2D3F8F 50%, #3B82F6 100%); box-shadow: 0 8px 24px rgba(30,42,94,0.3);">
                        <span x-show="!loading" class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Panel Admin
                        </span>
                        <span x-show="loading" class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner animate-spin"></i> Memverifikasi...
                        </span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <span class="text-xs text-slate-300 font-medium">atau</span>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>

                {{-- Employee login link --}}
                <a href="{{ route('employee.login') }}"
                   class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 hover:border-slate-300 transition-all active:scale-[0.98]">
                    <i class="fa-solid fa-mobile-screen text-slate-400"></i>
                    Login sebagai Karyawan
                    <i class="fa-solid fa-chevron-right text-slate-300 text-xs"></i>
                </a>

                <p class="text-center text-xs text-slate-300 mt-6">
                    © {{ date('Y') }} MasDin · Sistem Absensi Karyawan
                </p>
            </div>
        </div>
    </div>
</body>
</html>
