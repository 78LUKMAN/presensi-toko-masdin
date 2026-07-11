{{-- resources/views/employee/profile.blade.php --}}
@extends('layouts.employee')
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-[#F0F4F8]"
     x-data='{
        page: "profile",
        showPass: [false, false, false],
        oldPass: "", newPass: "", confirmPass: "",
        strength: 0,
        checkStrength(v) {
            let s = 0;
            if (v.length >= 8) s++;
            if (/[A-Z]/.test(v)) s++;
            if (/[0-9]/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            this.strength = s;
        },
        strengthLabel() {
            const l = ["", "Lemah", "Cukup", "Baik", "Kuat"];
            const c = ["", "text-red-500", "text-amber-500", "text-blue-500", "text-emerald-600"];
            return { label: l[this.strength], color: c[this.strength] };
        }
     }'>

    {{-- ════════════════════════
         PROFILE PAGE
    ════════════════════════ --}}
    <div x-show="page === 'profile'" x-transition.opacity>

        {{-- ── Header / Avatar section ── --}}
        <div class="relative px-5 pt-12 pb-16 text-center" style="background: linear-gradient(135deg, #1E2A5E 0%, #3B82F6 100%);">
            <div class="absolute top-0 right-0 w-40 h-40 rounded-full opacity-10 bg-white" style="transform:translate(30%,-30%)"></div>

            {{-- Avatar --}}
            <div class="relative inline-block mb-3">
                <div class="w-24 h-24 rounded-full bg-white/20 border-4 border-white/40 overflow-hidden flex items-center justify-center shadow-xl mx-auto">
                    <i class="fa-solid fa-user text-white text-4xl"></i>
                </div>
                <button class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-white flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-pen text-[#1E2A5E] text-xs"></i>
                </button>
            </div>
            <h2 class="text-white text-xl font-extrabold">{{ auth()->user()->name ?? 'Budi Santoso' }}</h2>
            <p class="text-blue-200 text-sm mt-0.5">{{ auth()->user()->email ?? 'karyawan@masdin.co.id' }}</p>
            <div class="flex items-center justify-center gap-2 mt-2">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">
                    {{ auth()->user()->noreg ?? 'KRY-001' }}
                </span>
                <span class="bg-emerald-500/30 text-emerald-100 text-xs font-bold px-3 py-1 rounded-full">
                    <i class="fa-solid fa-circle-check text-[10px] mr-1"></i>Aktif
                </span>
            </div>
        </div>

        {{-- ── Stats row ── --}}
        <div class="px-4 -mt-8 mb-4 relative z-10">
            @php
                $hadirCount = \App\Models\DailyAttendance::where('employee_id', $employee->id)->whereIn('status', ['Hadir', 'Hadir (Manual)', 'Pulang Cepat'])->count();
                $izinCount = \App\Models\DailyAttendance::where('employee_id', $employee->id)->whereIn('status', ['Izin', 'Sakit', 'Cuti'])->where('approval_status', 'Done')->count();
                $alpaCount = \App\Models\DailyAttendance::where('employee_id', $employee->id)->where('status', 'Tidak Hadir')->count();
            @endphp
            <div class="bg-white rounded-2xl shadow-md p-4 grid grid-cols-3 divide-x divide-slate-100">
                <div class="text-center px-2">
                    <p class="text-2xl font-extrabold text-[#1E2A5E]">{{ $hadirCount }}</p>
                    <p class="text-xs text-slate-400 font-medium">Hadir</p>
                </div>
                <div class="text-center px-2">
                    <p class="text-2xl font-extrabold text-[#1E2A5E]">{{ $izinCount }}</p>
                    <p class="text-xs text-slate-400 font-medium">Izin</p>
                </div>
                <div class="text-center px-2">
                    <p class="text-2xl font-extrabold text-[#1E2A5E]">{{ $alpaCount }}</p>
                    <p class="text-xs text-slate-400 font-medium">Alpa</p>
                </div>
            </div>
        </div>

        {{-- ── Menu items ── --}}
        <div class="px-4 space-y-3 mb-28">

            {{-- Personal Info --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Akun</p>
                </div>

                @php
                $infos = [
                    ['label' => 'Nama Lengkap', 'value' => auth()->user()->name, 'icon' => 'fa-user'],
                    ['label' => 'NIK / ID', 'value' => $employee->noreg ?? '-', 'icon' => 'fa-id-card'],
                    ['label' => 'Bagian', 'value' => $employee->section ?? '-', 'icon' => 'fa-building'],
                    ['label' => 'Email', 'value' => auth()->user()->email, 'icon' => 'fa-envelope'],
                    ['label' => 'Bergabung', 'value' => $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M Y') : '-', 'icon' => 'fa-calendar'],
                ];
                @endphp

                @foreach($infos as $info)
                <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-50 last:border-0">
                    <div class="w-8 h-8 rounded-lg bg-[#1E2A5E]/8 flex items-center justify-center flex-shrink-0"
                         style="background:rgba(30,42,94,0.08)">
                        <i class="fa-solid {{ $info['icon'] }} text-[#1E2A5E] text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-400 font-medium">{{ $info['label'] }}</p>
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $info['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Settings menu --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengaturan</p>
                </div>

                {{-- Change Password --}}
                <button @click="page = 'password'"
                        class="w-full flex items-center gap-3 px-4 py-4 hover:bg-slate-50 active:bg-slate-100 transition-colors">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-lock text-amber-600 text-sm"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-semibold text-slate-700">Ganti Password</span>
                    <i class="fa-solid fa-chevron-right text-slate-300 text-xs"></i>
                </button>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-4 bg-white rounded-2xl shadow-sm hover:bg-red-50 active:scale-95 transition-all">
                    <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-right-from-bracket text-red-500 text-sm"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-bold text-red-500">Keluar</span>
                    <i class="fa-solid fa-chevron-right text-red-300 text-xs"></i>
                </button>
            </form>

            <p class="text-center text-xs text-slate-300 py-2">v1.0.0 · © {{ date('Y') }} MasDin</p>
        </div>
    </div>

    {{-- ════════════════════════
         CHANGE PASSWORD PAGE
    ════════════════════════ --}}
    <div x-show="page === 'password'" x-transition.opacity>

        {{-- Header --}}
        <div class="px-5 pt-12 pb-6" style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
            <div class="flex items-center gap-3">
                <button @click="page = 'profile'" class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center">
                    <i class="fa-solid fa-chevron-left text-white text-sm"></i>
                </button>
                <h1 class="text-white font-bold text-lg">Ganti Password</h1>
            </div>
        </div>

        @if($errors->any())
            <div class="px-4 mb-4">
                <div class="bg-red-50 text-red-600 text-xs px-4 py-3 rounded-xl border border-red-100">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="px-4 mb-4">
                <div class="bg-emerald-50 text-emerald-600 text-xs px-4 py-3 rounded-xl border border-emerald-100">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <form action="{{ route('employee.profile.password') }}" method="POST" class="px-4 py-5 space-y-4 mb-28">
            @csrf
            {{-- Old password --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Password Lama</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-lock w-4 text-sm"></i>
                    </span>
                    <input :type="showPass[0] ? 'text' : 'password'" x-model="oldPass" name="current_password" required
                           placeholder="Password saat ini"
                           class="w-full pl-10 pr-12 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]"/>
                    <button type="button" @click="showPass[0] = !showPass[0]"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                        <i :class="showPass[0] ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- New passwords --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Password Baru</label>

                {{-- New password --}}
                <div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-key w-4 text-sm"></i>
                        </span>
                        <input :type="showPass[1] ? 'text' : 'password'" x-model="newPass" name="new_password" required
                               @input="checkStrength($event.target.value)"
                               placeholder="Min. 8 karakter"
                               class="w-full pl-10 pr-12 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]"/>
                        <button type="button" @click="showPass[1] = !showPass[1]"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                            <i :class="showPass[1] ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div x-show="newPass.length > 0" class="mt-2.5 space-y-1">
                        <div class="flex gap-1.5">
                            <template x-for="i in 4" :key="i">
                                <div class="h-1.5 flex-1 rounded-full transition-all"
                                     :class="i <= strength
                                        ? (strength <= 1 ? 'bg-red-400' : strength === 2 ? 'bg-amber-400' : strength === 3 ? 'bg-blue-500' : 'bg-emerald-500')
                                        : 'bg-slate-100'"></div>
                            </template>
                        </div>
                        <p class="text-xs font-semibold" :class="strengthLabel().color" x-text="strengthLabel().label"></p>
                    </div>
                </div>

                {{-- Confirm password --}}
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-shield-halved w-4 text-sm"></i>
                    </span>
                    <input :type="showPass[2] ? 'text' : 'password'" x-model="confirmPass" name="new_password_confirmation" required
                           placeholder="Ulangi password baru"
                           class="w-full pl-10 pr-12 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1E2A5E]/30 focus:border-[#1E2A5E]"
                           :class="confirmPass && newPass !== confirmPass ? 'border-red-400 bg-red-50' : confirmPass && newPass === confirmPass ? 'border-emerald-400' : ''"/>
                    <button type="button" @click="showPass[2] = !showPass[2]"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                        <i :class="showPass[2] ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                    </button>
                    {{-- Match indicator --}}
                    <div class="absolute right-10 top-1/2 -translate-y-1/2" x-show="confirmPass">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm" x-show="newPass === confirmPass && confirmPass"></i>
                        <i class="fa-solid fa-circle-xmark text-red-400 text-sm" x-show="newPass !== confirmPass && confirmPass"></i>
                    </div>
                </div>
                <p x-show="confirmPass && newPass !== confirmPass" class="text-xs text-red-500">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>Password tidak cocok
                </p>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    :disabled="!oldPass || !newPass || newPass !== confirmPass || strength < 2"
                    :class="(!oldPass || !newPass || newPass !== confirmPass || strength < 2) ? 'opacity-40 cursor-not-allowed' : 'active:scale-95 shadow-lg shadow-blue-900/20'"
                    class="w-full py-4 rounded-full font-bold text-white text-base transition-all"
                    style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
                <i class="fa-solid fa-shield-check mr-2"></i>Update Password
            </button>
        </form>
    </div>

    @include('employee._bottom-nav', ['active' => 'profile'])
</div>
@endsection
