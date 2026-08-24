<x-app-layout>
    <div class="min-h-[70vh] flex items-center justify-center animate-fade-up">
        <div class="tenang-card w-full max-w-sm p-8 sm:p-10 text-center relative overflow-hidden" x-data="{ pin: '' }">
            <div class="absolute -top-14 -left-14 w-40 h-40 bg-violet-200/50 rounded-full blur-2xl"></div>

            <div class="relative">
                <p class="text-5xl mb-3">🔐</p>
                <h1 class="text-xl font-bold">Jurnalmu Terkunci</h1>
                <p class="text-sm text-slate-400 mt-1 mb-7">Masukkan PIN 6 digit untuk membuka ruang pribadimu.</p>

                <form method="POST" action="{{ route('pin.unlock') }}" id="pin-form">
                    @csrf

                    <!-- Indikator titik -->
                    <div class="flex justify-center gap-3 mb-2" @click="$refs.pinInput.focus()">
                        @for ($i = 0; $i < 6; $i++)
                            <span class="w-3.5 h-3.5 rounded-full border-2 border-violet-300 transition-all duration-150"
                                  :class="pin.length > {{ $i }} ? 'bg-violet-500 border-violet-500 scale-110' : 'bg-transparent'"></span>
                        @endfor
                    </div>
                    <input type="hidden" name="pin" :value="pin">

                    <x-input-error :messages="$errors->get('pin')" class="mb-4 !justify-center" />

                    <!-- Keypad -->
                    <div class="grid grid-cols-3 gap-2.5 mt-5 max-w-[260px] mx-auto">
                        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $num)
                            <button type="button" @click="pin += '{{ $num }}'; if (pin.length === 6) $nextTick(() => $refs.submitBtn.click())"
                                    class="aspect-square rounded-2xl bg-white/80 border border-violet-100 text-lg font-bold text-violet-600 hover:bg-violet-50 hover:border-violet-300 active:scale-95 transition-all shadow-sm">
                                {{ $num }}
                            </button>
                        @endforeach
                        <button type="button" @click="pin = ''"
                                class="aspect-square rounded-2xl bg-white/50 text-xs font-bold text-slate-400 hover:text-slate-600 transition">Hapus</button>
                        <button type="button" @click="pin += '0'; if (pin.length === 6) $nextTick(() => $refs.submitBtn.click())"
                                class="aspect-square rounded-2xl bg-white/80 border border-violet-100 text-lg font-bold text-violet-600 hover:bg-violet-50 hover:border-violet-300 active:scale-95 transition-all shadow-sm">0</button>
                        <button type="button" @click="pin = pin.slice(0, -1)"
                                class="aspect-square rounded-2xl bg-white/50 text-lg font-bold text-slate-400 hover:text-slate-600 active:scale-95 transition">⌫</button>
                    </div>

                    <input type="submit" x-ref="submitBtn" class="hidden">
                </form>

                <a href="{{ route('dashboard') }}" class="inline-block mt-6 text-xs text-slate-400 hover:text-violet-500 underline underline-offset-2">Kembali ke beranda</a>
            </div>
        </div>
    </div>
</x-app-layout>
