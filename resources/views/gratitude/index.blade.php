<x-app-layout>
    <div class="animate-fade-up max-w-2xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Gratitude List 🌱</h1>
                <p class="text-slate-400 text-sm mt-1">Hal-hal kecil yang layak disyukuri.</p>
            </div>
            <div class="flex items-center gap-2 self-start">
                <a href="{{ route('gratitude.index', ['month' => $month->copy()->subMonth()->month, 'year' => $month->copy()->subMonth()->year]) }}" class="w-9 h-9 rounded-full bg-white/70 border border-violet-100 flex items-center justify-center hover:bg-violet-50 transition">←</a>
                <span class="px-5 py-2 rounded-full bg-white/70 border border-violet-100 text-sm font-bold min-w-[140px] text-center">{{ $month->translatedFormat('F Y') }}</span>
                <a href="{{ route('gratitude.index', ['month' => $month->copy()->addMonth()->month, 'year' => $month->copy()->addMonth()->year]) }}" class="w-9 h-9 rounded-full bg-white/70 border border-violet-100 flex items-center justify-center hover:bg-violet-50 transition">→</a>
            </div>
        </div>

        <!-- Form tambah -->
        <form method="POST" action="{{ route('gratitude.store') }}" class="tenang-card p-5">
            @csrf
            <label class="block text-sm font-bold text-slate-600 mb-2">Hari ini aku bersyukur untuk…</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="content" maxlength="255" required placeholder="contoh: senja yang indah & keluarga di rumah"
                       class="tenang-input flex-1 px-4 py-2.5 text-sm">
                <button type="submit" class="tenang-btn whitespace-nowrap">🌱 Tambah</button>
            </div>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
        </form>

        <!-- Daftar syukur per tanggal -->
        @if ($items->isEmpty())
            <div class="tenang-card p-12 text-center">
                <p class="text-4xl mb-3">🪴</p>
                <p class="font-semibold mb-1">Belum ada catatan syukur bulan ini</p>
                <p class="text-sm text-slate-400">Mulai dari hal terkecil — secangkir teh hangat pun berarti.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach ($items as $date => $dayItems)
                    <section class="tenang-card p-5">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-violet-400 mb-3">{{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d F Y') }}</h2>
                        <ul class="space-y-2">
                            @foreach ($dayItems as $item)
                                <li class="group flex items-center gap-3 px-4 py-3 rounded-2xl bg-gradient-to-r from-emerald-50/80 to-sky-50/60 border border-emerald-100/70">
                                    <span class="text-lg">🌿</span>
                                    <p class="flex-1 text-sm text-slate-600">{{ $item->content }}</p>
                                    <form method="POST" action="{{ route('gratitude.destroy', $item) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="opacity-0 group-hover:opacity-100 transition text-rose-300 hover:text-rose-500 text-lg leading-none" aria-label="Hapus">&times;</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
