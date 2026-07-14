<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'Absensi Pegawai') – MasDin</title>

    {{-- Vite assets (Tailwind v4 + compiled CSS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&display=swap" rel="stylesheet"/>

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #F0F4F8; }
        .mobile-wrap { max-width: 480px; margin: 0 auto; min-height: 100svh; background: #fff; position: relative; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25); }
        .speech-bubble::after { content:''; position:absolute; bottom:-10px; left:50%; transform:translateX(-50%); width:0; height:0; border-left:12px solid transparent; border-right:12px solid transparent; border-top:12px solid #fff; }
        @keyframes scan-line { 0%,100%{top:8px} 50%{top:calc(100%-12px)} }
        .scan-line { animation: scan-line 2s ease-in-out infinite; }
        @keyframes corner-pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .corner-pulse { animation: corner-pulse 1.5s ease-in-out infinite; }
        /* Safe area bottom padding for phone notches */
        .pb-safe { padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        .bottom-nav { padding-bottom: max(0.75rem, env(safe-area-inset-bottom)); }
    </style>
    @stack('head')
</head>
<body class="antialiased">
    <div class="mobile-wrap">
        @yield('content')
    </div>
    @include('partials.time-simulator')
    @stack('scripts')
</body>
</html>
