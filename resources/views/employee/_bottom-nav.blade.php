{{-- resources/views/employee/_bottom-nav.blade.php --}}
{{-- Usage: @include('employee._bottom-nav', ['active' => 'home']) --}}
<nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-slate-100 shadow-lg z-50 bottom-nav">
    <div class="grid grid-cols-4 h-16">

        {{-- Home --}}
        <a href="{{ route('employee.dashboard') }}"
           class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ ($active ?? '') === 'home' ? 'text-[#1E2A5E]' : 'text-slate-400' }}">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="text-[10px] font-semibold">Home</span>
            @if(($active ?? '') === 'home')
                <div class="absolute bottom-0 h-0.5 w-10 rounded-full bg-[#1E2A5E]" style="margin-bottom: max(0px, env(safe-area-inset-bottom));"></div>
            @endif
        </a>

        {{-- QR Scan (center, raised) --}}
        <a href="{{ route('employee.scan') }}"
           class="flex flex-col items-center justify-center relative {{ ($active ?? '') === 'scan' ? 'text-[#1E2A5E]' : 'text-slate-400' }}">
            <div class="absolute -top-6 w-14 h-14 rounded-full flex items-center justify-center shadow-xl"
                 style="background: linear-gradient(135deg, #1E2A5E, #3B82F6);">
                <i class="fa-solid fa-qrcode text-white text-xl"></i>
            </div>
            <span class="text-[10px] font-semibold mt-5" style="color: {{ ($active ?? '') === 'scan' ? '#1E2A5E' : '#94a3b8' }}">Scan</span>
        </a>

        {{-- History --}}
        <a href="{{ route('employee.history') }}"
           class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ ($active ?? '') === 'history' ? 'text-[#1E2A5E]' : 'text-slate-400' }}">
            <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            <span class="text-[10px] font-semibold">Riwayat</span>
            @if(($active ?? '') === 'history')
                <div class="absolute bottom-0 h-0.5 w-10 rounded-full bg-[#1E2A5E]" style="margin-bottom: max(0px, env(safe-area-inset-bottom));"></div>
            @endif
        </a>

        {{-- Profile --}}
        <a href="{{ route('employee.profile') }}"
           class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ ($active ?? '') === 'profile' ? 'text-[#1E2A5E]' : 'text-slate-400' }}">
            <i class="fa-solid fa-user text-xl"></i>
            <span class="text-[10px] font-semibold">Profil</span>
            @if(($active ?? '') === 'profile')
                <div class="absolute bottom-0 h-0.5 w-10 rounded-full bg-[#1E2A5E]" style="margin-bottom: max(0px, env(safe-area-inset-bottom));"></div>
            @endif
        </a>
    </div>
</nav>
