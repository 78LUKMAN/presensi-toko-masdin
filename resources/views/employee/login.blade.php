{{-- resources/views/employee/login.blade.php --}}
@extends('layouts.employee')
@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex flex-col"
        style="background: linear-gradient(160deg, #1E2A5E 0%, #2D3F8F 50%, #3B82F6 100%);" x-data="{ showPass: false }">

        {{-- ── Top decorative circles ── --}}
        <div class="absolute top-0 right-0 w-40 h-40 rounded-full opacity-10"
            style="background:#fff; transform:translate(30%,-30%);"></div>
        <div class="absolute top-16 left-0 w-24 h-24 rounded-full opacity-10"
            style="background:#fff; transform:translateX(-40%);"></div>

        {{-- ── Greeting section ── --}}
        <div class="flex-1 flex flex-col items-center justify-center px-6 pt-16 pb-8">
            {{-- App logo / icon --}}
            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center mb-6 shadow-lg">
                <i class="fa-solid fa-fingerprint text-white text-3xl"></i>
            </div>

            <p class="text-white/70 text-sm font-medium tracking-widest uppercase mb-1">Sistem Presensi</p>
            <h1 class="text-white text-3xl font-extrabold tracking-tight mb-2">MasDin</h1>

            {{-- Speech bubble --}}
            <div class="relative mt-6">
                <div class="speech-bubble relative bg-white rounded-2xl px-7 py-3 shadow-xl">
                    <span class="text-[#1E2A5E] text-2xl font-extrabold tracking-wide">HALO! 👋</span>
                </div>
            </div>
        </div>

        {{-- ── White card (slides up from bottom) ── --}}
        <div class="bg-white rounded-t-[2rem] px-6 pt-8 pb-safe shadow-2xl"
            style="box-shadow: 0 -8px 32px rgba(30,42,94,0.15);">

            <h2 class="text-[#1E2A5E] text-xl font-bold mb-1">Selamat Datang</h2>
            <p class="text-slate-400 text-sm mb-7">Masuk ke akun pegawai Anda</p>

            @if(session('error'))
                <div
                    class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('employee.login.submit') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email /
                        Username</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-regular fa-envelope w-4"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="email@perusahaan.com"
                            class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E] transition-all @error('email') border-red-400 @enderror" />
                    </div>
                    @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Kata Sandi --}}
                <div>
                    <label
                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-lock w-4"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" name="password" required placeholder="••••••••"
                            class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E] transition-all @error('password') border-red-400 @enderror" />
                        <button type="button" @click="showPass = !showPass"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="w-4"></i>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Tombol Masuk --}}
                <button type="submit"
                    class="w-full py-4 rounded-full font-bold text-white text-base shadow-lg shadow-blue-900/30 transition-all active:scale-95"
                    style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>
                    Masuk
                </button>

                {{-- Forgot password --}}
                <div class="text-center pt-1">
                    <a href="{{ route('employee.forgot-password') }}"
                        class="text-sm text-slate-400 hover:text-[#1E2A5E] transition-colors">
                        Lupa password?
                    </a>
                </div>
            </form>

            <div class="mt-8 pb-4 text-center">
                <p class="text-xs text-slate-300">© {{ date('Y') }} MasDin · Hak cipta dilindungi</p>
            </div>
        </div>
    </div>
@endsection