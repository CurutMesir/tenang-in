<x-app-layout>
    @php
        $daysInMonth = $current->daysInMonth;
        $startDow = $current->copy()->startOfMonth()->dayOfWeekIso; // 1 = Senin
        $today = \Illuminate\Support\Carbon::today();
        $total = $distribution->sum('total');
        $avg = collect($moodByDate)->values()->avg() ? round(collect($moodByDate)->values()->avg(), 1) : null;
    @endphp

    <div class="animate-fade-up space-y-6">
        <!-- Header + navigasi bulan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Statistik Mood 📊</h1>
                <p class="text-slate-400 text-sm mt-1">Peta perasaanmu sepanjang waktu.</p>
            </div>
            <div class="flex items-center gap-2 self-start">
                <a href="{{ route('stats.index', ['month' => $current->copy()->subMonth()->month, 'year' => $current->copy()->subMonth()->year]) }}" class="w-9 h-9 rounded-full bg-white/70 border border-violet-100 flex items-center justify-center hover:bg-violet-50 transition">←</a>
                <span class="px-5 py-2 rounded-full bg-white/70 border border-violet-100 text-sm font-bold min-w-[150px] text-center">{{ $current->translatedFormat('F Y') }}</span>
                <a href="{{ route('stats.index', ['month' => $current->copy()->addMonth()->month, 'year' => $current->copy()->addMonth()->year]) }}" class="w-9 h-9 rounded-full bg-white/70 border border-violet-100 flex items-center justify-center hover:bg-violet-50 transition">→</a>
            </div>
        </div>

        <!-- Ringkasan -->
        <section class="grid grid-cols-3 gap-4">
            <div class="tenang-card p-5 text-center">
                <p class="text-xs text-slate-400 mb-1">Jurnal bulan ini</p>
                <p class="text-3xl font-extrabold text-violet-500">{{ $total }}</p>
            </div>
            <div class="tenang-card p-5 text-center">
                <p class="text-xs text-slate-400 mb-1">Rata-rata mood</p>
                <p class="text-3xl font-extrabold text-emerald-500">{{ $avg ?: '—' }}</p>
            </div>
            <div class="tenang-card p-5 text-center flex flex-col justify-center">
                <p class="text-xs text-slate-400 mb-1">Mood tersering</p>
                <p class="text-3xl">{{ $mostFrequentMood ? (\App\Models\Journal::MOODS[$mostFrequentMood]['emoji']) : '—' }}</p>
            </div>
        </section>

        <div class="grid lg:grid-cols-5 gap-6">
            <!-- Kalender mood -->
            <section class="tenang-card p-6 lg:col-span-3">
                <h2 class="font-bold text-lg mb-4">Kalender Mood</h2>
                <div class="grid grid-cols-7 gap-1.5 sm:gap-2 text-center">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 pb-1">{{ $day }}</span>
                    @endforeach

                    @for ($i = 1; $i < $startDow; $i++)
                        <span></span>
                    @endfor

                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $date = $current->copy()->setDay($d);
                            $mood = $moodByDate[$date->toDateString()] ?? null;
                        @endphp
                        <div title="{{ $date->translatedFormat('l, d F Y') }}{{ $mood ? ' — ' . \App\Models\Journal::MOODS[$mood]['label'] : '' }}"
                             class="aspect-square rounded-xl sm:rounded-2xl flex items-center justify-center text-sm sm:text-base transition-all {{ $mood ? \App\Models\Journal::MOODS[$mood]['bg'] . ' hover:scale-110 cursor-default shadow-sm' : 'bg-white/50 text-slate-300' }} {{ $date->isSameDay($today) ? 'ring-2 ring-offset-1 ring-violet-400' : '' }}">
                            @if ($mood)
                                <span>{{ \App\Models\Journal::MOODS[$mood]['emoji'] }}</span>
                            @else
                                <span class="hidden sm:inline">{{ $d }}</span>
                                <span class="sm:hidden text-[9px]">{{ $d }}</span>
                            @endif
                        </div>
                    @endfor
                </div>

                <!-- Legend -->
                <div class="mt-5 pt-4 border-t border-violet-100/60 flex flex-wrap gap-x-4 gap-y-1">
                    @foreach (\App\Models\Journal::MOODS as $data)
                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-400">
                            <span class="w-4 h-4 rounded-md inline-block {{ $data['bg'] }}"></span> {{ $data['label'] }}
                        </span>
                    @endforeach
                </div>
            </section>

            <!-- Distribusi -->
            <section class="tenang-card p-6 lg:col-span-2">
                <h2 class="font-bold text-lg mb-4">Sebaran Mood</h2>
                @if ($total === 0)
                    <div class="py-8 text-center">
                        <p class="text-4xl mb-2">🫧</p>
                        <p class="text-sm text-slate-400">Belum ada data bulan ini.</p>
                    </div>
                @else
                    <div class="space-y-3.5 mt-2">
                        @foreach ($distribution as $row)
                            @php $data = \App\Models\Journal::MOODS[$row->mood]; $pct = round($row->total / $total * 100); @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold {{ $data['text'] }}">{{ $data['emoji'] }} {{ $data['label'] }}</span>
                                    <span class="text-slate-400">{{ $row->total }}× · {{ $pct }}%</span>
                                </div>
                                <div class="h-2.5 rounded-full bg-white/70 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $pct }}%; background-color: {{ $data['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        <!-- Tren 6 bulan -->
        <section class="tenang-card p-6">
            <h2 class="font-bold text-lg mb-6">Tren Mood 6 Bulan Terakhir</h2>
            <div class="flex items-end justify-around gap-2 sm:gap-4 h-48 px-2">
                @foreach ($monthlyTrend as $point)
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end max-w-[72px]">
                        <span class="text-[11px] font-bold {{ $point['value'] ? 'text-violet-600' : 'text-slate-300' }}">
                            {{ $point['value'] ?? '—' }}
                        </span>
                        <div class="w-full rounded-t-xl transition-all duration-700 relative group"
                             style="height: {{ $point['value'] ? ($point['value'] / 5) * 82 : 2 }}%; background: linear-gradient(to top, #c4b5fd, #818cf8);">
                            @if ($point['value'])
                                <span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap px-2 py-1 rounded-lg bg-slate-800 text-white text-[10px]">
                                    rata-rata {{ $point['value'] }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs font-semibold text-slate-400">{{ $point['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
