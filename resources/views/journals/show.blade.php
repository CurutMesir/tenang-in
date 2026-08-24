<x-app-layout>
    <div class="animate-fade-up max-w-2xl mx-auto space-y-5">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <a href="{{ route('journals.index') }}" class="text-sm text-violet-500 hover:text-violet-700 font-medium">← Kembali ke jurnal</a>
            <span class="text-xs text-slate-400">{{ $journal->entry_date->translatedFormat('l, d F Y') }}</span>
        </div>

        <!-- Kartu utama -->
        <article class="tenang-card p-8 sm:p-10 relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-40 h-40 {{ $journal->moodData()['bg'] }}/60 rounded-full blur-2xl"></div>

            <div class="relative flex items-center gap-4 mb-6">
                <span class="w-14 h-14 rounded-3xl {{ $journal->moodData()['bg'] }} flex items-center justify-center text-3xl shadow-inner">
                    {{ $journal->moodData()['emoji'] }}
                </span>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold leading-snug">{{ $journal->title ?: 'Tanpa judul' }}</h1>
                    <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-semibold {{ $journal->moodData()['bg'] }} {{ $journal->moodData()['text'] }}">
                        {{ $journal->moodData()['label'] }}
                    </span>
                </div>
            </div>

            @if ($journal->reflectionPrompt)
                <div class="mb-5 px-4 py-3 rounded-2xl bg-gradient-to-r from-violet-50 to-sky-50 border border-violet-100">
                    <p class="text-xs font-bold text-violet-400 mb-0.5">💭 Prompt refleksi</p>
                    <p class="text-sm italic text-slate-500">“{{ $journal->reflectionPrompt->text }}”</p>
                </div>
            @endif

            <div class="font-hand text-[1.7rem] leading-snug text-slate-700 whitespace-pre-wrap break-words">{{ $journal->content }}</div>
        </article>

        <!-- Aksi -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('journals.create') }}" class="tenang-btn-secondary">✍️ Tulis lagi</a>
            <div class="flex items-center gap-3">
                <a href="{{ route('journals.edit', $journal) }}" class="tenang-btn-secondary">Edit</a>
                <form method="POST" action="{{ route('journals.destroy', $journal) }}"
                      x-data onsubmit="return confirm('Hapus jurnal ini? Tindakan ini tidak bisa dibatasi.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 rounded-full text-sm font-semibold text-rose-500 hover:bg-rose-50 transition">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
