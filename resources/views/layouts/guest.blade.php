<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tenang.in') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|caveat:500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-700 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-violet-100 via-purple-50 to-sky-100 relative overflow-hidden">
            <!-- Dekorasi -->
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-violet-200/50 rounded-full blur-3xl animate-float pointer-events-none" aria-hidden="true"></div>
            <div class="absolute -bottom-24 -right-24 w-[26rem] h-[26rem] bg-sky-200/50 rounded-full blur-3xl animate-float-slow pointer-events-none" aria-hidden="true"></div>

            <a href="/" class="flex items-center gap-2 mb-6 group relative z-10">
                <span class="text-4xl group-hover:-translate-y-1 transition-transform">☁️</span>
                <span class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">Tenang.in</span>
            </a>

            <div class="w-full sm:max-w-md mt-4 px-6 py-7 bg-white/75 backdrop-blur-md border border-white/60 shadow-[0_8px_30px_rgb(167,139,250,0.15)] overflow-hidden sm:rounded-3xl relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
