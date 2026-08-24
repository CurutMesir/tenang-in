<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tenang.in') }} — Ruang Tenangmu</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|caveat:500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-700 min-h-screen bg-gradient-to-br from-violet-100 via-purple-50 to-sky-100">
        <!-- Dekorasi awan -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-10 -left-16 w-72 h-72 bg-violet-200/40 rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-1/3 -right-20 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-pink-200/30 rounded-full blur-3xl animate-float"></div>
        </div>

        <x-flash />

        <nav class="sticky top-0 z-40 backdrop-blur-md bg-white/60 border-b border-white/50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <span class="text-2xl">☁️</span>
                        <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">Tenang.in</span>
                    </a>

                    <div class="hidden md:flex items-center gap-1">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                        <x-nav-link :href="route('ai-chat.index')" :active="request()->routeIs('ai-chat.*')">
                            <span class="inline-flex items-center gap-1">{{ __('Curhat AI') }} <span class="px-1.5 py-0.5 text-[9px] rounded-full bg-gradient-to-r from-violet-500 to-indigo-400 text-white font-bold">NEW</span></span>
                        </x-nav-link>
                        <x-nav-link :href="route('journals.index')" :active="request()->routeIs('journals.*')">{{ __('Jurnal') }}</x-nav-link>
                        <x-nav-link :href="route('stats.index')" :active="request()->routeIs('stats.*')">{{ __('Statistik') }}</x-nav-link>
                        <x-nav-link :href="route('gratitude.index')" :active="request()->routeIs('gratitude.*')">{{ __('Syukur') }}</x-nav-link>
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/70 border border-violet-100 hover:border-violet-300 transition text-sm font-medium">
                                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-400 to-indigo-400 text-white flex items-center justify-center text-xs font-bold uppercase">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                    <span class="hidden sm:inline max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('journals.create')">✍️ {{ __('Tulis Jurnal') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('pin.edit')">🔐 {{ __('Kunci PIN') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endauth

                        {{-- Mobile menu toggle --}}
                        <button x-data x-on:click="$dispatch('toggle-mobile-nav')" class="md:hidden p-2 rounded-lg hover:bg-violet-100/70" aria-label="Menu">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile nav --}}
            <div x-data="{ open: false }" x-on:toggle-mobile-nav.window="open = !open" x-show="open" x-cloak class="md:hidden border-t border-violet-100/60 bg-white/80 px-4 py-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ai-chat.index')" :active="request()->routeIs('ai-chat.*')">{{ __('Curhat AI') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('journals.index')" :active="request()->routeIs('journals.*')">{{ __('Jurnal') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('stats.index')" :active="request()->routeIs('stats.*')">{{ __('Statistik') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('gratitude.index')" :active="request()->routeIs('gratitude.*')">{{ __('Syukur') }}</x-responsive-nav-link>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            {{ $slot }}
        </main>

        <footer class="max-w-6xl mx-auto px-4 pb-8 text-center text-xs text-violet-400">
            ☁️ Tenang.in — ruang kecil untuk hatimu. Dibuat dengan Laravel 12 & Tailwind CSS.
        </footer>
    </body>
</html>
