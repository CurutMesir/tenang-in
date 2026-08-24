# ☁️ Tenang.in — Ruang Tenang untuk Hatimu

<p align="center">
  <em>Jurnal kesehatan mental pribadi dengan teman curhat AI untuk remaja Indonesia</em>
</p>

---

**Tenang.in** adalah aplikasi web jurnal kesehatan mental yang memberi remaja ruang privat untuk menulis perasaan, melacak mood harian, dan berlatih bersyukur — dilengkapi **teman curhat AI bernama "Tenang"** yang siap mendengarkan kapan saja.

> 💡 *"Tulis apa pun yang kamu rasakan. Semua tersimpan aman, terkunci PIN, dan tidak akan dibaca siapa pun."*

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 📓 **Jurnal Harian** | Tulis cerita & keluh kesah dengan font handwritten yang hangat. Dilengkapi judul, tanggal mundur, pencarian, dan filter per mood |
| 🙂 **Mood Tracker** | Pilih 5 tingkat mood (emoji) setiap kali menulis jurnal |
| 📊 **Statistik Mood** | Kalender mood berwarna ala heatmap, grafik distribusi mood, dan tren 6 bulan terakhir |
| 🔥 **Streak Menulis** | Motivasi konsisten dengan penghitung hari berturut-turut |
| 💭 **Prompt Refleksi** | 15 pertanyaan refleksi acak sebagai pancingan saat bingung mau nulis apa |
| 🌱 **Gratitude List** | Catat hal-hal kecil yang disyukuri, terkelompok rapi per tanggal |
| 💬 **Curhat AI** | Ngobrol langsung dengan "Tenang" — AI suportif berbasis Google Gemini dengan kepribadian hangat & asik |
| 🔐 **Kunci PIN** | Lindungi jurnal dengan PIN 6 digit + keypad interaktif; sesi baru = jurnal terkunci lagi |

### 🛡️ Tanggung Jawab atas Kesehatan Mental

Tenang dirancang dengan prinsip *safe by design*:

- AI dikonfigurasi agar **tidak pernah mendiagnosis** atau memberi saran medis
- Jika pengguna menunjukkan tanda krisis, Tenang otomatis mengarahkan ke **SEJIWA 119 ext. 8** (layanan kesehatan jiwa Kemkes, gratis 24 jam)
- Disclaimer jelas: AI adalah pendengar, bukan pengganti psikolog

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Framework | Laravel 12 |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js |
| Database | MySQL |
| AI | Google Gemini API (`gemini-flash-lite-latest`) |
| Autentikasi | Laravel Breeze |
| Build Tool | Vite |

---

## 🚀 Cara Menjalankan Project

### Prasyarat
- PHP ≥ 8.2 ([cek versi](https://www.php.net/downloads))
- Composer
- Node.js ≥ 18
- MySQL (XAMPP/Laragon/MySQL standalone)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/AufaRijalRais/tenang.in.git
cd tenang.in

# 2. Install dependency
composer install
npm install && npm run build

# 3. Konfigurasi environment
copy .env.example .env        # Windows (Linux/Mac: cp .env.example .env)
php artisan key:generate
```

```bash
# 4. Buat database MySQL
mysql -u root -e "CREATE DATABASE tenangin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

5. Edit file `.env`, sesuaikan bagian ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tenangin
DB_USERNAME=root
DB_PASSWORD=

GEMINI_API_KEY=isi_api_key_gemini_kamu   # gratis dari https://aistudio.google.com/apikey
GEMINI_MODEL=gemini-flash-lite-latest
```

```bash
# 6. Migrasi database + isi prompt refleksi & kutipan motivasi
php artisan migrate --seed

# 7. Jalankan!
php artisan serve
```

Buka **http://127.0.0.1:8000** → daftar akun → mulai menulis! ☁️

---

## 🗂️ Struktur Database

```
users                → akun pengguna (+ pin_code terenkripsi hash)
├── journals         → jurnal harian (mood 1–5, prompt refleksi opsional)
├── gratitude_items  → catatan syukur per tanggal
├── chat_messages    → riwayat obrolan dengan AI
reflection_prompts   → bank pertanyaan refleksi (global, di-seed)
quotes               → kutipan motivasi (global, di-seed)
```

## 🔀 Alur Privasi Jurnal

```
Login ✓ → Ada PIN? ──Ya──→ Layar Kunci PIN → PIN benar? → Akses Jurnal
                └─Tidak────────────────────────────────────┘
```

Setiap sesi browser baru, PIN harus dimasukkan ulang — meski perangkat dipinjam orang, jurnal tetap aman.

---

## 🖼️ Screenshot

> *(tambahkan screenshot aplikasimu di folder `docs/` lalu ganti bagian ini)*

| Dashboard | Statistik Mood | Curhat AI |
|---|---|---|
| ![Dashboard](docs/dashboard.png) | ![Statistik](docs/statistik.png) | ![Chat AI](docs/curhat-ai.png) |

---

## 👤 Author

**Aufa Rijal Rais**
- GitHub: [@AufaRijalRais](https://github.com/AufaRijalRais)

## 📄 License

Project ini dibuat untuk keperluan edukasi. Silakan gunakan dan modifikasi dengan bebas.
