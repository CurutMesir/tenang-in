<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tenang.in — Ruang Tenang untuk Hatimu</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|caveat:500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-700 min-h-screen bg-gradient-to-br from-violet-100 via-purple-50 to-sky-100 overflow-x-hidden">
        <!-- Dekorasi langit -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-16 -left-20 w-96 h-96 bg-violet-200/50 rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-1/4 -right-24 w-[28rem] h-[28rem] bg-sky-200/50 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute bottom-10 left-1/3 w-80 h-80 bg-pink-200/40 rounded-full blur-3xl animate-float"></div>
        </div>

        <!-- Navbar -->
        <nav class="relative z-40 backdrop-blur-md bg-white/50 border-b border-white/60">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl">☁️</span>
                    <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">Tenang.in</span>
                </a>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="tenang-btn">Buka Jurnal →</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-semibold text-violet-600 hover:text-violet-800 px-4 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="tenang-btn">Mulai Gratis</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <header class="max-w-6xl mx-auto px-4 sm:px-6 pt-16 pb-24 md:pt-24 md:pb-32 grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-up">
                <p class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/70 border border-violet-200 text-xs font-semibold text-violet-500 mb-6">
                    🌸 Ruang curhat privat untuk remaja Indonesia
                </p>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight tracking-tight">
                    Cerita kecil hari ini,
                    <span class="block bg-gradient-to-r from-violet-600 via-indigo-500 to-sky-500 bg-clip-text text-transparent">hati jadi lebih ringan.</span>
                </h1>
                <p class="mt-6 text-lg text-slate-500 max-w-md leading-relaxed">
                    Tulis apa pun yang kamu rasakan, lacak suasana hatimu, dan temukan kembali ketenangan — semua tersimpan aman dengan kunci PIN pribadi.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}" class="tenang-btn text-base px-7 py-3">✍️ Mulai Menulis Sekarang</a>
                    <a href="#fitur" class="tenang-btn-secondary text-base px-7 py-3">Lihat Fitur</a>
                </div>
                <div class="mt-8 flex items-center gap-3 text-sm text-slate-400">
                    <div class="flex -space-x-2">
                        <span class="w-8 h-8 rounded-full bg-violet-300 border-2 border-white flex items-center justify-center text-xs">🙂</span>
                        <span class="w-8 h-8 rounded-full bg-pink-300 border-2 border-white flex items-center justify-center text-xs">😄</span>
                        <span class="w-8 h-8 rounded-full bg-sky-300 border-2 border-white flex items-center justify-center text-xs">😌</span>
                    </div>
                    <span>100% gratis &amp; privat. Tidak ada yang membaca tulisanmu.</span>
                </div>
            </div>

            <!-- Ilustrasi kartu mood -->
            <div class="relative hidden md:block animate-fade-up" style="animation-delay:.15s">
                <div class="animate-float">
                    <div class="tenang-card p-8 max-w-sm ml-auto rotate-2 hover:rotate-0 transition-transform duration-500">
                        <div class="flex items-center justify-between mb-5">
                            <p class="text-sm font-semibold text-violet-400">Bagaimana perasaanmu?</p>
                            <span class="text-xs text-slate-400">{{ now()->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex items-end justify-between gap-1 mb-6">
                            @foreach (['😞', '🙁', '😐', '🙂', '😄'] as $i => $emoji)
                                <button class="flex-1 aspect-square flex items-center justify-center rounded-2xl {{ $i === 4 ? 'bg-emerald-100 scale-110 shadow-lg shadow-emerald-200' : 'bg-slate-50 opacity-50' }} text-3xl transition-all duration-300">
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                        <p class="font-hand text-2xl text-slate-600 leading-snug">"Hari ini aku belajar memaafkan diriku sendiri…"</p>
                        <div class="mt-5 flex items-center gap-2">
                            <span class="h-2 flex-1 rounded-full bg-gradient-to-r from-violet-400 to-emerald-300"></span>
                            <span class="text-xs font-semibold text-emerald-500">Streak 7 hari 🔥</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-6 -left-4 animate-float-slow">
                    <div class="tenang-card px-5 py-4 -rotate-3">
                        <p class="text-xs text-slate-400">Syukur hari ini</p>
                        <p class="text-sm font-semibold text-violet-500">🌱 Keluarga, kopi, senja</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Fitur -->
        <section id="fitur" class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
            <div class="text-center mb-14">
                <h2 class="text-3xl sm:text-4xl font-bold">Semua yang kamu butuhkan untuk <span class="text-violet-500">me time</span> digital</h2>
                <p class="mt-3 text-slate-500">Sederhana, hangat, dan sepenuhnya milikmu.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
                    ['✍️', 'Jurnal Harian', 'Tulis cerita, keluh kesah, atau hal baik sekecil apa pun. Bebas, tanpa judgment.'],
                    ['🎨', 'Mood Tracker', 'Pilih emoji perasaanmu setiap hari dan lihat pola emosimu lewat kalender warna.'],
                    ['🔐', 'Kunci PIN', 'Tulisan pribadimu terlindungi PIN 6 digit. Aman meski HP-mu dipinjam teman.'],
                    ['💭', 'Prompt Refleksi', 'Bingung mau nulis apa? Ada pertanyaan refleksi acak untuk memandu.'],
                    ['🌱', 'Gratitude List', 'Catat tiga syukur kecil tiap hari — riset bilang ini bikin lebih bahagia.'],
                    ['📈', 'Statistik Mood', 'Grafik rapi per bulan: rata-rata mood, tren 6 bulan, dan streak menulis.'],
                ] as [$icon, $title, $desc])
                    <div class="tenang-card p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-100 to-sky-100 flex items-center justify-center text-2xl mb-4">{{ $icon }}</div>
                        <h3 class="font-bold text-lg mb-1">{{ $title }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- CTA -->
        <section class="max-w-4xl mx-auto px-4 sm:px-6 pb-24">
            <div class="tenang-card relative overflow-hidden p-10 md:p-14 text-center">
                <div class="absolute -top-16 -right-16 w-48 h-48 bg-violet-200/60 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-sky-200/60 rounded-full blur-2xl"></div>
                <h2 class="relative text-3xl md:text-4xl font-bold mb-3">Hari kamu sudah cukup berat.</h2>
                <p class="relative text-slate-500 mb-8 max-w-md mx-auto">Luangkan 5 menit malam ini untuk dirimu sendiri. Mulai dari satu kalimat saja.</p>
                <a href="{{ route('register') }}" class="relative tenang-btn text-base px-8 py-3.5">Mulai Perjalanan Tenang 🌙</a>
            </div>
        </section>

        <footer class="border-t border-white/60 py-8 text-center text-xs text-violet-400 bg-white/30 backdrop-blur">
            ☁️ Tenang.in — ruang kecil untuk hatimu. Dibuat dengan Laravel 12 &amp; Tailwind CSS.
        </footer>
    </body>
</html>
