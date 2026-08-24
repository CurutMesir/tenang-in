<x-app-layout>
    <div class="animate-fade-up space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Jurnalku 📓</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $journals->total() }} catatan dalam perjalananmu</p>
            </div>
            <a href="{{ route('journals.create') }}" class="tenang-btn self-start">+ Tulis Jurnal Baru</a>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('journals.index') }}" class="tenang-card p-4 flex flex-col sm:flex-row gap-3">
            <div class="flex-1 flex flex-wrap items-center gap-2">
                @foreach (\App\Models\Journal::MOODS as $value => $data)
                    <label class="cursor-pointer">
                        <input type="radio" name="mood" value="{{ $value }}" class="peer sr-only" @checked(request('mood') == $value) onchange="this.form.submit()">
                        <span title="{{ $data['label'] }}"
                              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-violet-100 bg-white/60 text-sm hover:bg-violet-50 peer-checked:{{ $data['bg'] }} peer-checked:ring-2 ring-violet-300 transition">
                            <span>{{ $data['emoji'] }}</span>
                            <span class="hidden lg:inline text-xs font-medium {{ $data['text'] }}">{{ $data['label'] }}</span>
                            @if (isset($moodCounts[$value]))
                                <span class="text-[10px] px-1.5 rounded-full bg-white/70 text-slate-500">{{ $moodCounts[$value] }}</span>
                            @endif
                        </span>
                    </label>
                @endforeach
                @if (request('mood'))
                    <a href="{{ route('journals.index') }}" class="text-xs text-slate-400 underline hover:text-slate-600">reset</a>
                @endif
            </div>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jurnal…" 
                       class="tenang-input w-full sm:w-56 pl-9 pr-3 py-2 text-sm rounded-full">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-violet-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
            </div>
        </form>

        <!-- Grid jurnal -->
        @if ($journals->isEmpty())
            <div class="tenang-card p-14 text-center">
                <p class="text-5xl mb-4">🌤️</p>
                <p class="font-semibold text-lg mb-1">{{ request()->anyFilled(['mood', 'search']) ? 'Tidak ada yang cocok' : 'Belum ada jurnal' }}</p>
                <p class="text-sm text-slate-400 mb-6">{{ request()->anyFilled(['mood', 'search']) ? 'Coba filter lain, ya.' : 'Ceritamu menanti halaman pertamanya.' }}</p>
                <a href="{{ route('journals.create') }}" class="tenang-btn">✍️ Tulis Sekarang</a>
            </div>
        @else
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($journals as $journal)
                    <a href="{{ route('journals.show', $journal) }}" class="tenang-card p-6 flex flex-col group hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <span class="w-11 h-11 rounded-2xl {{ $journal->moodData()['bg'] }} flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                {{ $journal->moodData()['emoji'] }}
                            </span>
                            <span class="text-xs text-slate-400">{{ $journal->entry_date->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="font-bold truncate group-hover:text-violet-600 transition-colors">{{ $journal->title ?: 'Tanpa judul' }}</h3>
                        <p class="mt-1 text-sm text-slate-500 leading-relaxed line-clamp-3 flex-1">{{ Str::limit($journal->content, 120) }}</p>
                        <div class="mt-4 pt-3 border-t border-violet-100/70 flex items-center justify-between">
                            <span class="text-xs font-medium {{ $journal->moodData()['text'] }}">{{ $journal->moodData()['label'] }}</span>
                            @if ($journal->reflectionPrompt)
                                <span title="Ditulis dengan prompt refleksi" class="text-xs">💭</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pt-2">{{ $journals->links() }}</div>
        @endif
    </div>
</x-app-layout>
