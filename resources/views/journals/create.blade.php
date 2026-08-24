<x-app-layout>
    <div class="animate-fade-up max-w-2xl mx-auto space-y-6">
        @if ($alreadyWritten)
            <div class="tenang-card p-4 flex items-center gap-3 border-amber-200 bg-amber-50/80">
                <span class="text-xl">💡</span>
                <p class="text-sm text-amber-600">Kamu sudah menulis jurnal hari ini. Tetap boleh menulis lagi — setiap perasaan berharga!</p>
            </div>
        @endif

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Jurnal Baru ✍️</h1>
            <p class="text-slate-400 text-sm mt-1">{{ now()->translatedFormat('l, d F Y') }} — ruang ini hanya milikmu.</p>
        </div>

        @include('journals._form', ['prompts' => $prompts, 'alreadyWritten' => $alreadyWritten])
    </div>
</x-app-layout>
