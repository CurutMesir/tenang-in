@props(['journal' => null, 'prompts' => null, 'alreadyWritten' => false])

@php
    $oldMood = old('mood', $journal?->mood);
@endphp

<form method="POST" action="{{ $journal ? route('journals.update', $journal) : route('journals.store') }}" x-data="{ mood: @js($oldMood), showPrompt: {{ $journal ? 'false' : 'true' }} }" class="space-y-6">
    @csrf
    @if ($journal)
        @method('PUT')
    @endif

    <!-- Mood picker -->
    <div class="tenang-card p-6 sm:p-7">
        <label class="block text-sm font-bold text-slate-600 mb-1">Bagaimana perasaanmu hari ini?</label>
        <p class="text-xs text-slate-400 mb-4">Tidak ada jawaban yang salah di sini.</p>
        <div class="grid grid-cols-5 gap-2 sm:gap-3">
            @foreach (\App\Models\Journal::MOODS as $value => $data)
                <button type="button"
                        @click="mood = {{ $value }}"
                        :class="mood == {{ $value }} ? '{{ $data['bg'] }} scale-110 shadow-lg ring-2 ring-offset-2 ring-violet-300' : 'bg-white/60 opacity-60 hover:opacity-100 hover:scale-105'"
                        class="aspect-square rounded-2xl flex flex-col items-center justify-center gap-1 transition-all duration-200">
                    <span class="text-2xl sm:text-3xl">{{ $data['emoji'] }}</span>
                    <span class="hidden sm:block text-[10px] font-semibold {{ $data['text'] }}">{{ $data['label'] }}</span>
                </button>
            @endforeach
        </div>
        <input type="hidden" name="mood" :value="mood">
        <x-input-error :messages="$errors->get('mood')" class="mt-2" />
    </div>

    <!-- Prompt refleksi -->
    @if ($prompts && $prompts->count())
        <div class="tenang-card p-6" x-show="showPrompt" x-cloak>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-bold text-slate-600">💭 Butuh pancingan? Coba jawab ini:</p>
                <button type="button" @click="showPrompt = false" class="text-slate-300 hover:text-slate-500 text-lg leading-none">&times;</button>
            </div>
            <div class="flex flex-col gap-2">
                @foreach ($prompts as $prompt)
                    <button type="button"
                            onclick="const ta = document.getElementById('content'); if (!ta.value.trim()) { ta.value = this.dataset.prompt + '\n\n'; }; ta.focus();"
                            data-prompt="{{ $prompt->text }}"
                            class="text-left px-4 py-3 rounded-xl bg-gradient-to-r from-violet-50 to-sky-50 border border-violet-100 text-sm text-slate-600 hover:border-violet-300 hover:bg-violet-50 transition">
                        “{{ $prompt->text }}”
                    </button>
                @endforeach
            </div>
        </div>

        @if ($journal && $journal->reflectionPrompt)
            <input type="hidden" name="reflection_prompt_id" value="{{ $journal->reflection_prompt_id }}">
        @elseif (!$journal)
            <div id="prompt-holder"></div>
        @endif
    @endif

    <!-- Isi jurnal -->
    <div class="tenang-card p-6 sm:p-7 space-y-5">
        <div>
            <x-input-label for="title" :value="__('Judul (opsional)')" />
            <x-text-input id="title" name="title" type="text" class="tenang-input mt-1 block w-full" maxlength="150"
                          :value="old('title', $journal?->title)" placeholder="Contoh: Hari yang melelahkan tapi indah" />
        </div>

        <div>
            <x-input-label for="content" value="Apa yang ingin kamu ceritakan?" />
            <textarea id="content" name="content" rows="9"
                      class="font-hand text-2xl leading-snug tenang-input mt-1 block w-full resize-none focus:!ring-violet-300"
                      placeholder="Tulis apa saja… bebas, ini ruangmu sendiri.">{{ old('content', $journal?->content) }}</textarea>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
        </div>

        <div class="sm:w-56">
            <x-input-label for="entry_date" :value="__('Tanggal')" />
            <x-text-input id="entry_date" name="entry_date" type="date"
                          :value="old('entry_date', ($journal?->entry_date ?? \Illuminate\Support\Carbon::today())->format('Y-m-d'))"
                          :max="\Illuminate\Support\Carbon::today()->format('Y-m-d')"
                          class="tenang-input mt-1 block w-full" />
            <x-input-error :messages="$errors->get('entry_date')" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ $journal ? route('journals.show', $journal) : route('dashboard') }}" class="tenang-btn-secondary">Batal</a>
        <x-primary-button class="!px-7 !py-3 !rounded-full !bg-gradient-to-r from-violet-500 to-indigo-400 shadow-lg shadow-violet-300/50 hover:-translate-y-0.5">
            {{ $journal ? 'Simpan Perubahan' : '🌸 Simpan Jurnal' }}
        </x-primary-button>
    </div>
</form>
