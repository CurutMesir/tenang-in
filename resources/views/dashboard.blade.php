<x-app-layout>
    <div class="space-y-8 animate-fade-up">
        <!-- Sambutan -->
        <section class="tenang-card relative overflow-hidden p-7 sm:p-9">
            <div class="absolute -top-14 -right-14 w-44 h-44 bg-violet-200/50 rounded-full blur-2xl"></div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div>
                    <p class="text-sm text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-bold">
                        Hai, <span class="bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">{{ auth()->user()->name }}</span> 👋
                    </h1>
                    <p class="mt-2 text-slate-500">
                        @if ($todayJournal)
                            Kamu sudah menulis hari ini. Kerja bagus! 🌟
                        @else
                          Belum menulis hari ini? Yuk, luangkan waktu 5 menit ✍️
                        @endif
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @if ($todayJournal)
                        <a href="{{ route('journals.show', $todayJournal) }}" class="flex items-center gap-3 px-5 py-3 rounded-2xl {{ $todayJournal->moodData()['bg'] }} hover:brightness-105 transition">
                            <x-mood-emoji :mood="$todayJournal->mood" size="lg" />
                            <div>
                                <p class="text-xs opacity-70">Mood hari ini</p>
                                <p class="text-sm font-semibold {{ $todayJournal->moodData()['text'] }}">{{ $todayJournal->moodData()['label'] }}</p>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('journals.create') }}" class="tenang-btn">✍️ Tulis Jurnal</a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Statistik ringkas -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="tenang-card p-5 text-center">
                <p class="text-3xl mb-1">🔥</p>
                <p class="text-3xl font-extrabold bg-gradient-to-r from-orange-500 to-amber-400 bg-clip-text text-transparent">{{ $stats['streak'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Streak Menulis (hari)</p>
            </div>
            <div class="tenang-card p-5 text-center">
                <p class="text-3xl mb-1">📓</p>
                <p class="text-3xl font-extrabold bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Total Jurnal</p>
            </div>
            <div class="tenang-card p-5 text-center">
                <p class="text-3xl mb-1">🌤️</p>
                <p class="text-3xl font-extrabold bg-gradient-to-r from-sky-500 to-emerald-400 bg-clip-text text-transparent">{{ $avgMood ?: '—' }}</p>
                <p class="text-xs text-slate-400 mt-1">Rata-rata Mood Bulan Ini</p>
            </div>
            <div class="tenang-card p-5 text-center">
                <p class="text-3xl mb-1">🌱</p>
                <p class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">{{ $stats['gratitude_today'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Syukur Hari Ini</p>
            </div>
        </section>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Kutipan -->
            <section class="tenang-card p-7 relative overflow-hidden lg:col-span-1">
                <div class="absolute top-4 left-6 text-6xl text-violet-200 font-serif select-none">&ldquo;</div>
                <p class="relative mt-8 text-lg leading-relaxed text-slate-600 italic min-h-[5rem]">{{ $quote?->text ?? 'Tidak apa-apa merasa tidak apa-apa.' }}</p>
                <p class="mt-3 text-sm font-semibold text-violet-400">— {{ $quote?->author ?? 'Tenang.in' }}</p>
            </section>

            <!-- Jurnal terbaru -->
            <section class="tenang-card p-7 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg">Jurnal Terakhirmu</h2>
                    <a href="{{ route('journals.index') }}" class="text-sm text-violet-500 hover:text-violet-700 font-medium">Lihat semua →</a>
                </div>

                @if ($recentJournals->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-4xl mb-3">📖</p>
                        <p class="text-slate-400 text-sm">Belum ada jurnal. Halaman pertama ceritamu dimulai dari sini.</p>
                        <a href="{{ route('journals.create') }}" class="tenang-btn mt-4">Mulai Menulis</a>
                    </div>
                @else
                    <ul class="divide-y divide-violet-100/70">
                        @foreach ($recentJournals as $journal)
                            <li>
                                <a href="{{ route('journals.show', $journal) }}" class="flex items-center gap-4 py-3 group">
                                    <span class="w-11 h-11 rounded-2xl {{ $journal->moodData()['bg'] }} flex items-center justify-center text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
                                        {{ $journal->moodData()['emoji'] }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold truncate group-hover:text-violet-600 transition-colors">{{ $journal->title ?: 'Tanpa judul' }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ $journal->entry_date->translatedFormat('l, d M Y') }} · {{ Str::limit(strip_tags($journal->content), 60) }}</p>
                                    </div>
                                    <span class="text-violet-300 group-hover:text-violet-500 transition">›</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <!-- Aksi cepat -->
        <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('ai-chat.index') }}" class="tenang-card p-6 flex items-center gap-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-violet-500/10 to-indigo-400/10"></div>
                <span class="text-3xl relative">💬</span>
                <div class="relative">
                    <p class="font-bold">Curhat AI</p>
                    <p class="text-xs text-slate-400">Ngobrol dengan Tenang ☁️</p>
                </div>
            </a>
            <a href="{{ route('stats.index') }}" class="tenang-card p-6 flex items-center gap-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <span class="text-3xl">📊</span>
                <div>
                    <p class="font-bold">Statistik Mood</p>
                    <p class="text-xs text-slate-400">Lihat kalender perasaanmu</p>
                </div>
            </a>
            <a href="{{ route('gratitude.index') }}" class="tenang-card p-6 flex items-center gap-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <span class="text-3xl">🌱</span>
                <div>
                    <p class="font-bold">Gratitude List</p>
                    <p class="text-xs text-slate-400">Catat 3 syukur kecilmu</p>
                </div>
            </a>
            <a href="{{ route('pin.edit') }}" class="tenang-card p-6 flex items-center gap-4 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <span class="text-3xl">🔐</span>
                <div>
                    <p class="font-bold">Kunci PIN</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()->hasPin() ? 'PIN aktif — jurnal terlindungi' : 'Amankan jurnalmu dengan PIN' }}</p>
                </div>
            </a>
        </section>
    </div>
</x-app-layout>
