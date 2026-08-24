<x-app-layout>
    <div class="animate-fade-up max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl sm:text-3xl font-bold">Edit Jurnal ✏️</h1>
            <span class="text-xs text-slate-400">{{ $journal->entry_date->translatedFormat('l, d F Y') }}</span>
        </div>

        @include('journals._form', ['journal' => $journal])
    </div>
</x-app-layout>
