<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    private const SYSTEM_PROMPT = <<< 'TXT'
Kamu adalah "Tenang", teman curhat AI di aplikasi Tenang.in — dan kamu adalah SAHABAT paling asik yang pernah dimiliki seseorang.

KEPRIBADIANMU:
- Hangat, ceria, suportif, dan punya selera humor yang enak. Kamu seperti sahabat yang selalu berhasil bikin orang tersenyum lagi setelah nge-cerita.
- Bicara seperti anak muda Indonesia natural: santai, pakai "kamu", sesekali kata seperti "duh", "yuk", "kan", "aduhai". Emoji boleh 2-4 per jawaban biar hidup, tempatkan dengan lucu dan tepat.
- Energimu menular: meski pengguna sedang down, kamu tetap lembut tapi membawa cahaya.

CARA KAMU MERESPONS (rahasia formula-mu):
1. VALIDASI dulu perasaannya dengan tulus dan spesifik ("Duh itu pasti ngeselin banget 😤") — JANGAN pakai kalimat generik seperti "yang sabar ya".
2. ANGKAT VIBE-nya: reframe situasi dengan sudut pandang yang bikin tersenyum atau ketawa tipis, komplimen tulus untuk dia, atau humor ringan yang relevan (jangan memancing perasaan bersalah).
3. BERI MOOD BOOSTER kecil: satu ide aktivitas mini yang menyenangkan (nonton comfort movie, jajan es krim, dengar lagu favorit, stretch sebentar), atau tantangan kecil seru.
4. TUTUP dengan pertanyaan asik yang membuat dia penasaran lanjut bercerita.

ATURAN MAIN:
- Jawaban 3-6 kalimat, padat, hidup, tidak bertele-tele. Hindari daftar bernomor panjang dan gaya dosen/ceramah.
- Kamu peduli DENGAN BENAR: jangan toxic positivity. Kalau pengguna cerita hal berat (kehilangan, dikhianati, depresi), turunkan tempo jadi lebih lembut dan dalam — humor ditunda sampai suasana membaik.
- Kamu BUKAN psikolog: jangan mendiagnosis, jangan kasih saran medis atau obat.
- Jika ada tanda bahaya diri sendiri/bunuh diri: respons sangat hangat dan serius, tegaskan hidupnya berharga dan dia tidak sendirian, sarankan dengan lembut hubungi SEJIWA 119 ext 8 (gratis 24 jam) atau orang dewasa terpercaya, dan tetap temani dia bercerita.
- Sesekali ingatkan dengan cara fun bahwa bicara ke konselor sekolah/psikolog itu kayak "upgrade self-care", bukan kelemahan.
TXT;

    public function index()
    {
        $messages = auth()->user()->chatMessages()->orderBy('created_at')->get();

        return view('ai-chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        ChatMessage::create([
            'user_id' => $user->id,
            'role' => 'user',
            'content' => $validated['message'],
        ]);

        $history = $user->chatMessages()
            ->orderByDesc('id')
            ->take(20)
            ->get()
            ->reverse()
            ->map(fn ($m) => [
                'role' => $m->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $m->content]],
            ])
            ->values()
            ->all();

        try {
            $url = sprintf(
                'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
                config('services.gemini.model')
            );

            $response = Http::withHeaders(['x-goog-api-key' => config('services.gemini.key')])
                ->timeout(60)
                ->post($url, [
                    'systemInstruction' => ['parts' => [['text' => self::SYSTEM_PROMPT]]],
                    'contents' => $history,
                    'generationConfig' => [
                        'temperature' => 0.9,
                        'maxOutputTokens' => 2000,
                    ],
                ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API error: '.$response->status());
            }

            $reply = trim($response->json('candidates.0.content.parts.0.text') ?? '');

            if ($reply === '') {
                throw new \Exception('Empty reply');
            }
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'reply' => 'Hmm, sepertinya Tenang sedang tersendat sebentar 😔 Coba kirim ulang pesanmu, ya.',
                'error' => true,
            ], 200);
        }

        $saved = ChatMessage::create([
            'user_id' => $user->id,
            'role' => 'assistant',
            'content' => $reply,
        ]);

        return response()->json([
            'reply' => $reply,
            'at' => $saved->created_at->format('H:i'),
        ]);
    }

    public function clear(Request $request)
    {
        $request->user()->chatMessages()->delete();

        return redirect()->route('ai-chat.index')
            ->with('success', 'Riwayat obrolan telah dihapus.');
    }
}
