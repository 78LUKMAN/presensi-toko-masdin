<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Panel Admin – Sistem Presensi Karyawan Makdin">
    <title>@yield('title', 'Admin') – Makdin Presensi</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- jQuery + DataTables core JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
</head>
<body class="h-full font-sans antialiased">

    <!-- ========== SIDEBAR ========== -->
    <div id="hs-sidebar-basic-usage"
         class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform w-64 hidden fixed inset-y-0 start-0 z-[60] bg-gradient-to-b from-slate-900 to-slate-800 border-e border-slate-700 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0"
         role="dialog" tabindex="-1" aria-label="Sidebar">

        <!-- Logo / Brand -->
        <div class="px-6 mb-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold text-white tracking-tight">Makdin</span>
                    <p class="text-xs text-slate-400 -mt-0.5">Sistem Presensi</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="px-3 space-y-1">
            <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu Utama</p>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Daftar Karyawan --}}
            <a href="{{ route('admin.karyawan') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.karyawan') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Daftar Karyawan
            </a>

            <p class="px-3 pt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Presensi</p>

            {{-- Rekap Presensi --}}
            <a href="{{ route('admin.rekap-presensi') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.rekap-presensi') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Rekap Presensi
            </a>

            {{-- Presensi Bermasalah --}}
            <a href="{{ route('admin.presensi-bermasalah') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.presensi-bermasalah') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Presensi Bermasalah
            </a>

            <p class="px-3 pt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Penggajian</p>

            {{-- Rekap Gaji Harian --}}
            <a href="{{ route('admin.rekap-gaji-harian') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.rekap-gaji-harian') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Rekap Gaji Harian
            </a>

            {{-- Rekap Gaji Bulanan --}}
            <a href="{{ route('admin.rekap-gaji-bulanan') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.rekap-gaji-bulanan') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Rekap Gaji Bulanan
            </a>

            <p class="px-3 pt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sistem</p>

            {{-- Daftar Admin --}}
            <a href="{{ route('admin.daftar-admin') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.daftar-admin') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Daftar Admin
            </a>

            {{-- Pengaturan --}}
            <a href="{{ route('admin.pengaturan') }}"
               class="flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.pengaturan') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Pengaturan
            </a>

            <!-- Divider -->
            <div class="my-4 border-t border-slate-700"></div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-150">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </nav>

        <!-- User Info at Bottom -->
        <div class="absolute bottom-0 left-0 right-0 px-4 py-4 border-t border-slate-700 bg-slate-900/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== END SIDEBAR ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <div class="lg:ps-64 min-h-screen flex flex-col">

        <!-- Top Bar -->
        <header class="sticky top-0 z-40 flex items-center bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 sm:px-6 py-3 gap-3">
            <!-- Mobile menu toggle -->
            <button type="button"
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
                    data-hs-overlay="#hs-sidebar-basic-usage"
                    aria-controls="hs-sidebar-basic-usage"
                    aria-label="Toggle navigation">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page Title -->
            <div class="flex-1">
                <h2 class="text-base font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                <p class="text-xs text-slate-500">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <!-- Right side actions -->
            <div class="flex items-center gap-2">
                <!-- Notification Bell (placeholder) -->
                <button class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>

                <!-- Avatar dropdown -->
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-topbar" type="button"
                            class="hs-dropdown-toggle flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-lg rounded-xl border border-slate-200 mt-2 divide-y divide-slate-100 z-50"
                         aria-labelledby="hs-dropdown-topbar">
                        <div class="px-4 py-3">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <div class="p-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6">
            @if (session('success'))
                <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="px-6 py-4 border-t border-slate-200 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Makdin &middot; Sistem Presensi Karyawan
        </footer>
    </div>
    <!-- ========== END MAIN CONTENT ========== -->

    @stack('scripts')
</body>
</html>
