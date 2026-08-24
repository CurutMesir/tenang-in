<div class="fixed top-20 right-4 z-50 space-y-2 w-[calc(100%-2rem)] max-w-sm">
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-start gap-3 p-4 rounded-2xl bg-emerald-50/95 border border-emerald-200 shadow-lg backdrop-blur animate-fade-up">
            <span class="text-lg">🌸</span>
            <p class="text-sm text-emerald-700 flex-1">{{ session('success') }}</p>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-start gap-3 p-4 rounded-2xl bg-sky-50/95 border border-sky-200 shadow-lg backdrop-blur animate-fade-up">
            <span class="text-lg">☁️</span>
            <p class="text-sm text-sky-700 flex-1">{{ session('info') }}</p>
            <button @click="show = false" class="text-sky-400 hover:text-sky-600">&times;</button>
        </div>
    @endif
</div>
