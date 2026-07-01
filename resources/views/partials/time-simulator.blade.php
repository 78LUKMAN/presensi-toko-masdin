@if((config('app.debug') || env('APP_ENV') === 'local') && auth()->check() && auth()->user()->role === 'admin')
<div class="fixed bottom-4 right-4 z-[9999]" x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 bg-slate-900 text-white text-xs font-semibold rounded-full shadow-lg border border-slate-700 hover:bg-slate-800 transition-all">
        <i class="fa-solid fa-clock"></i>
        <span>Simulasi Waktu</span>
        <span class="inline-block w-2 h-2 rounded-full {{ \Illuminate\Support\Facades\Cache::has('simulated_time') ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></span>
    </button>

    <!-- Dropdown / Card -->
    <div x-show="open" @click.outside="open = false" x-transition class="absolute bottom-12 right-0 w-72 p-4 bg-white rounded-2xl shadow-xl border border-slate-200 text-slate-800">
        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Simulasi Waktu Sistem</h4>
        
        @if(\Illuminate\Support\Facades\Cache::has('simulated_time'))
            <div class="mb-3 p-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                Waktu simulasi aktif:<br>
                <strong>{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</strong>
            </div>
        @else
            <div class="mb-3 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-slate-500">
                Menggunakan waktu server asli:<br>
                <strong>{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</strong>
            </div>
        @endif

        <form action="{{ route('simulate-time') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Set Tanggal & Waktu</label>
                <input type="datetime-local" name="simulated_time" value="{{ \Illuminate\Support\Facades\Cache::has('simulated_time') ? \Carbon\Carbon::parse(\Illuminate\Support\Facades\Cache::get('simulated_time'))->format('Y-m-d\TH:i') : '' }}" class="w-full text-xs p-2 border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors">
                    Terapkan
                </button>
                @if(\Illuminate\Support\Facades\Cache::has('simulated_time'))
                    <button type="submit" name="simulated_time" value="clear" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold rounded-lg transition-colors">
                        Reset
                    </button>
                @endif
            </div>
        </form>

        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap gap-1">
            <button @click="const d = new Date(); d.setDate(d.getDate() + 1); $el.closest('div').querySelector('input').value = d.toISOString().slice(0,16);" class="text-[10px] px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded">
                +1 Hari
            </button>
            <button @click="const d = new Date(); d.setHours(d.getHours() + 9); $el.closest('div').querySelector('input').value = d.toISOString().slice(0,16);" class="text-[10px] px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded">
                +9 Jam
            </button>
            <button @click="const d = new Date(); d.setHours(8,0,0,0); $el.closest('div').querySelector('input').value = d.toISOString().slice(0,16);" class="text-[10px] px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded">
                08:00 Hari Ini
            </button>
            <button @click="const d = new Date(); d.setHours(17,0,0,0); $el.closest('div').querySelector('input').value = d.toISOString().slice(0,16);" class="text-[10px] px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded">
                17:00 Hari Ini
            </button>
        </div>
    </div>
</div>
@endif
