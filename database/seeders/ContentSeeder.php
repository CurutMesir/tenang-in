<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\ReflectionPrompt;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $prompts = [
            'Apa satu hal kecil hari ini yang membuatmu tersenyum?',
            'Kalau kamu bisa ngobrol dengan dirimu sendiri 5 tahun lalu, mau bilang apa?',
            'Apa beban terberat yang sedang kamu pikul sekarang? Tulis saja, tidak apa-apa.',
            'Sebutkan tiga hal yang kamu syukuri minggu ini.',
            'Kapan terakhir kali kamu merasa benar-benar tenang? Apa yang sedang kamu lakukan?',
            'Apa satu hal baik yang pernah kamu lakukan untuk orang lain belakangan ini?',
            'Hari ini lelah sekali, ya? Apa yang paling ingin kamu lepaskan malam ini?',
            'Apa hal baru yang berhasil kamu pelajari bulan ini?',
            'Kalau hari ini adalah sebuah bab di buku, apa judulnya?',
            'Siapa orang yang membuat harimu lebih hangat? Sudahkah kamu berterima kasih padanya?',
            'Apa ketakutan terbesarmu saat ini, dan seberapa besar pengaruhnya dalam hidupmu?',
            'Tulis surat pendek untuk dirimu yang sedang merasa down.',
            'Apa satu kebiasaan kecil yang ingin kamu mulai besok?',
            'Lihat ke belakang: dari mana kamu berasal, dan sudah sejauh mana kamu melangkah?',
            'Apa arti "pulang" bagimu?',
        ];

        foreach ($prompts as $text) {
            ReflectionPrompt::create(['text' => $text]);
        }

        $quotes = [
            ['Tidak apa-apa merasa tidak apa-apa.', 'Tenang.in'],
            ['Kamu tidak harus positif setiap saat. Kamu hanya perlu jujur pada dirimu sendiri.', 'Tenang.in'],
            ['Istirahat itu bukan menyerah, melainkan bagian dari perjalanan.', 'Tenang.in'],
            ['Perasaanmu valid. Semua perasaanmu valid.', 'Tenang.in'],
            ['Langkah kecil hari ini tetap sebuah kemajuan.', 'Tenang.in'],
            ['Badai selalu berlalu. Kamu akan tetap ada setelahnya.', 'Tenang.in'],
            ['Bersikap lembut pada dirimu sendiri bukan kemewahan, tapi kebutuhan.', 'Tenang.in'],
            ['Hidup bukan lomba lari. Nikmati langkahmu.', 'Tenang.in'],
            ['Bahagia itu sederhana, kadang kita saja yang terlalu sibuk mencarinya.', 'Anonim'],
            ['Yang terbaik untuk dirimu dimulai dengan memaafkan dirimu sendiri.', 'Anonim'],
            ['Setiap hari adalah kesempatan baru untuk memulai lagi.', 'Anonim'],
            ['Jangan bandingkan bab 3 hidupmu dengan bab 20 orang lain.', 'Anonim'],
        ];

        foreach ($quotes as [$text, $author]) {
            Quote::create(['text' => $text, 'author' => $author]);
        }
    }
}
