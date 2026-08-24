<x-app-layout>
    <div class="animate-fade-up max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-full bg-gradient-to-br from-violet-400 to-indigo-400 flex items-center justify-center text-xl shadow-lg shadow-violet-200">☁️</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Tenang — Teman Curhat</h1>
                    <p class="text-xs text-slate-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Selalu siap mendengarkan
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('ai-chat.clear') }}" onsubmit="return confirm('Hapus semua riwayat obrolan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-slate-400 hover:text-rose-500 underline underline-offset-2 transition">Hapus riwayat</button>
            </form>
        </div>

        <!-- Area obrolan -->
        <div id="chat-scroll" class="tenang-card !rounded-b-none border-b-0 p-5 sm:p-6 h-[52vh] min-h-[340px] overflow-y-auto space-y-4">
            @if ($messages->isEmpty())
                <div class="text-center py-4">
                    <p class="text-4xl mb-2">🌙</p>
                    <p class="font-semibold">Hai, aku Tenang ☁️</p>
                    <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Ceritakan apa saja yang kamu rasakan. Aku tidak akan menghakimi — di sini kamu aman.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-2 pt-2">
                    @foreach (['Aku lagi ngerasa cemas banget 😔', 'Hari ini berat sekali…', 'Ada yang mau aku cerita', 'Aku lagi bahagia banget! ✨'] as $starter)
                        <button type="button" data-starter="{{ $starter }}"
                                class="starter-btn px-4 py-2 rounded-full bg-white/70 border border-violet-100 text-xs font-medium text-violet-500 hover:bg-violet-50 hover:border-violet-300 transition">
                            {{ $starter }}
                        </button>
                    @endforeach
                </div>
            @else
                @foreach ($messages as $m)
                    @include('ai-chat._bubble', ['role' => $m->role, 'text' => $m->content])
                @endforeach
            @endif

            <!-- Indikator mengetik -->
            <div id="typing-indicator" class="hidden flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-300 to-indigo-300 flex-shrink-0"></span>
                <div class="bg-white/80 rounded-2xl rounded-tl-md px-4 py-3 inline-flex gap-1.5 items-center">
                    <span class="w-2 h-2 rounded-full bg-violet-300 animate-bounce [animation-delay:0ms]"></span>
                    <span class="w-2 h-2 rounded-full bg-violet-300 animate-bounce [animation-delay:150ms]"></span>
                    <span class="w-2 h-2 rounded-full bg-violet-300 animate-bounce [animation-delay:300ms]"></span>
                    <span class="text-xs text-slate-400 ml-1">Tenang sedang menulis…</span>
                </div>
            </div>
        </div>

        <!-- Input -->
        <form id="chat-form" method="POST" action="{{ route('ai-chat.send') }}" class="tenang-card !rounded-t-none p-3 flex items-end gap-2">
            @csrf
            <textarea id="chat-input" name="message" rows="1" maxlength="2000"
                      placeholder="Tulis apa yang kamu rasakan…"
                      class="tenang-input flex-1 resize-none max-h-28 px-4 py-2.5 text-sm"></textarea>
            <button type="submit" id="chat-send-btn"
                    class="tenang-btn !px-5 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.3 3.7a.5.5 0 0 1 .7-.6l16.9 8.4a.5.5 0 0 1 0 .9L4 20.9a.5.5 0 0 1-.7-.6L6 12Zm0 0h7"/></svg>
            </button>
        </form>

        <div class="flex items-center justify-center gap-2 mt-3 px-6">
            <p class="text-center text-[11px] text-slate-400">
                ⚠️ Tenang adalah AI pendengar, bukan pengganti psikolog. Krisis? Hubungi <strong>SEJIWA 119 ext. 8</strong> (gratis, 24 jam).
            </p>
            <span class="text-[9px] text-violet-300 bg-violet-50 border border-violet-100 rounded px-1 py-0.5 font-mono">v3</span>
        </div>
    </div>

    <script>
    (function () {
        var scroll = document.getElementById('chat-scroll');
        var form = document.getElementById('chat-form');
        var input = document.getElementById('chat-input');
        var typing = document.getElementById('typing-indicator');
        var busy = false;

        console.log('%c[Tenang.in] Modul curhat v3 aktif ✔', 'color:#8b5cf6;font-weight:bold');

        function scrollToBottom() {
            scroll.scrollTop = scroll.scrollHeight;
        }

        function escapeHtml(text) {
            var d = document.createElement('div');
            d.textContent = text == null ? '' : String(text);
            return d.innerHTML;
        }

        function addBubble(role, text) {
            var safe = escapeHtml(text);
            var html = role === 'user'
                ? '<div class="flex justify-end"><div class="max-w-[80%] bg-gradient-to-br from-violet-500 to-indigo-400 text-white rounded-2xl rounded-tr-md px-4 py-2.5 text-sm leading-relaxed whitespace-pre-wrap break-words shadow-md shadow-violet-200">' + safe + '</div></div>'
                : '<div class="flex items-start gap-2.5"><span class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-300 to-indigo-300 flex-shrink-0"></span><div class="max-w-[80%] bg-white/90 border border-violet-100/70 rounded-2xl rounded-tl-md px-4 py-2.5 text-sm leading-relaxed text-slate-600 whitespace-pre-wrap break-words">' + safe + '</div></div>';
            scroll.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function showTyping(show) {
            typing.classList.toggle('hidden', !show);
            if (show) scrollToBottom();
        }

        function sendMessage(message) {
            message = (message || '').trim();
            if (!message || busy) return Promise.resolve();
            busy = true;
            input.value = '';
            addBubble('user', message);
            showTyping(true);

            var meta = document.querySelector('meta[name="csrf-token"]');
            return fetch("{{ route('ai-chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': meta ? meta.content : '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: message })
            }).then(function (res) {
                if (res.status === 419) {
                    addBubble('assistant', 'Sesi kamu kedaluwarsa nih 🙏 Tekan F5 dulu, lalu kirim ulang ya.');
                    return null;
                }
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            }).then(function (data) {
                if (data) addBubble('assistant', data.reply || '…');
            }).catch(function (err) {
                console.error(err);
                addBubble('assistant', 'Koneksi ke Tenang tersendat sebentar 😔 Coba kirim ulang ya 🙏');
            }).finally(function () {
                showTyping(false);
                busy = false;
                input.focus();
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            sendMessage(input.value);
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage(input.value);
            }
        });

        Array.prototype.forEach.call(document.querySelectorAll('.starter-btn'), function (btn) {
            btn.addEventListener('click', function () {
                sendMessage(btn.getAttribute('data-starter'));
            });
        });

        scrollToBottom();
        input.focus();
    })();
    </script>
</x-app-layout>
