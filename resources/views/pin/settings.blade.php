<x-app-layout>
    <div class="animate-fade-up max-w-md mx-auto space-y-6">
        <div class="text-center pt-4">
            <p class="text-5xl mb-3">{{ $hasPin ? '🔐' : '🔓' }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold">{{ $hasPin ? 'Kunci PIN Aktif' : 'Aktifkan Kunci PIN' }}</h1>
            <p class="text-slate-400 text-sm mt-1">
                @if ($hasPin)
                    Jurnalmu terlindungi PIN setiap kali dibuka.
                @else
                    Lindungi jurnal pribadimu dengan PIN 6 digit.
                @endif
            </p>
        </div>

        <form method="POST" action="{{ route('pin.update') }}" class="tenang-card p-7 space-y-5">
            @csrf
            @method('PUT')

            @if ($hasPin)
                <div>
                    <x-input-label for="current_pin" value="Konfirmasi password akunmu" />
                    <x-text-input id="current_pin" name="current_pin" type="password" class="tenang-input mt-1 block w-full" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('current_pin')" class="mt-2" />
                </div>
            @endif

            <div>
                <x-input-label for="pin" :value="__('PIN baru (6 angka)')" />
                <x-text-input id="pin" name="pin" type="password" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                              class="tenang-input mt-1 block w-full tracking-[0.5em] text-center font-mono" required placeholder="••••••" />
                <x-input-error :messages="$errors->get('pin')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="pin_confirmation" value="Ulangi PIN baru" />
                <x-text-input id="pin_confirmation" name="pin_confirmation" type="password" inputmode="numeric" maxlength="6"
                              class="tenang-input mt-1 block w-full tracking-[0.5em] text-center font-mono" required placeholder="••••••" />
                <x-input-error :messages="$errors->get('pin_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-1">
                <a href="{{ route('dashboard') }}" class="tenang-btn-secondary">Batal</a>
                <x-primary-button class="!px-7 !py-2.5 !rounded-full !bg-gradient-to-r from-violet-500 to-indigo-400">
                    {{ $hasPin ? 'Ganti PIN' : 'Simpan PIN' }}
                </x-primary-button>
            </div>
        </form>

        @if ($hasPin)
            <form method="POST" action="{{ route('pin.destroy') }}"
                  onsubmit="return confirm('Nonaktifkan kunci PIN? Jurnalmu tidak akan terlindungi lagi.')">
                @csrf
                @method('DELETE')
                <div class="tenang-card p-5 space-y-3">
                    <p class="text-sm font-semibold text-slate-600">Ingin mematikan kunci PIN?</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="password" name="password" required placeholder="Masukkan password akun untuk konfirmasi"
                               class="tenang-input flex-1 px-4 py-2.5 text-sm">
                        <button type="submit" class="px-5 py-2.5 rounded-full text-sm font-semibold text-rose-500 bg-rose-50 hover:bg-rose-100 transition whitespace-nowrap">Nonaktifkan PIN</button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" />
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
