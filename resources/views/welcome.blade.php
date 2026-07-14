<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Masuk - MasDin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
            animation: float 10s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .hover-card-effect {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .hover-card-effect:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
        }
        
        .hover-card-effect:hover .icon-ring {
            transform: scale(1.1);
            border-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen relative overflow-x-hidden flex items-center justify-center selection:bg-blue-500 selection:text-white">

    <!-- Background Blobs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="blob bg-blue-600 w-96 h-96 rounded-full top-[-10%] left-[-10%]"></div>
        <div class="blob bg-indigo-500 w-[500px] h-[500px] rounded-full bottom-[-20%] right-[-10%]" style="animation-delay: -5s;"></div>
        <div class="blob bg-emerald-500 w-64 h-64 rounded-full top-[20%] right-[20%] opacity-40"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-6 py-12 flex flex-col items-center">
        
        <!-- Header -->
        <div class="text-center mb-16 animate-[fadeInUp_1s_ease-out]">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 mb-6 shadow-2xl">
                <i class="fa-solid fa-layer-group text-3xl text-blue-400"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
                MasDin Portal System
            </h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto font-medium">
                Pilih portal akses Anda untuk masuk ke dalam sistem presensi dan manajemen pegawai.
            </p>
        </div>

        <!-- Portal Selection Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl mx-auto">
            
            <!-- Employee Portal Card -->
            <a href="{{ route('employee.login') }}" class="glass-card hover-card-effect rounded-3xl p-8 md:p-10 flex flex-col group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                
                <div class="icon-ring w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mb-8 shadow-lg shadow-blue-500/30 border-2 border-transparent transition-all duration-300 relative z-10">
                    <i class="fa-solid fa-id-badge text-3xl text-white"></i>
                </div>
                
                <div class="relative z-10 flex-1">
                    <h2 class="text-2xl font-bold mb-3 text-white group-hover:text-blue-300 transition-colors">Portal Pegawai</h2>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Akses khusus pegawai untuk melakukan presensi harian dengan QR Code, mengecek riwayat, dan mengajukan perizinan secara mandiri.
                    </p>
                </div>
                
                <div class="relative z-10 flex items-center text-sm font-bold text-blue-400 group-hover:text-blue-300 mt-auto">
                    Masuk sebagai Pegawai
                    <i class="fa-solid fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- Admin Portal Card -->
            <a href="{{ route('admin.login') }}" class="glass-card hover-card-effect rounded-3xl p-8 md:p-10 flex flex-col group relative overflow-hidden" style="animation-delay: 0.1s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                
                <div class="icon-ring w-20 h-20 rounded-2xl bg-gradient-to-br from-[#1E2A5E] to-slate-800 flex items-center justify-center mb-8 shadow-lg shadow-black/40 border-2 border-transparent transition-all duration-300 relative z-10">
                    <i class="fa-solid fa-user-shield text-3xl text-white"></i>
                </div>
                
                <div class="relative z-10 flex-1">
                    <h2 class="text-2xl font-bold mb-3 text-white group-hover:text-emerald-300 transition-colors">Portal Admin</h2>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Akses khusus manajemen untuk mengelola data master pegawai, persetujuan izin, dan memonitor rekapitulasi penggajian.
                    </p>
                </div>
                
                <div class="relative z-10 flex items-center text-sm font-bold text-emerald-400 group-hover:text-emerald-300 mt-auto">
                    Masuk sebagai Admin
                    <i class="fa-solid fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

        </div>

        <!-- Footer -->
        <div class="mt-20 text-center text-slate-500 text-sm font-medium">
            <p>&copy; {{ date('Y') }} Toko Sembako MasDin. All rights reserved.</p>
        </div>

    </div>

</body>
</html>
