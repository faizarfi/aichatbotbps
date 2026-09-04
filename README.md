# 🏛️ Portal Layanan Statistik Terpadu & Chatbot AI BPS Kabupaten Karanganyar

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

Portal resmi pelayanan publik digital dan kecerdasan buatan (AI) **Badan Pusat Statistik (BPS) Kabupaten Karanganyar**. Dirancang untuk mempermudah masyarakat, akademisi, peneliti, pemerintah daerah, dan pelaku usaha dalam mengakses data statistik resmi, melakukan konsultasi online/offline, mengajukan data mikro & rekomendasi statistik (ROMANTIK), mengisi survei kepuasan, hingga menyampaikan pengaduan layanan secara terintegrasi, cepat, dan transparan.

---

## 📌 Daftar Isi

1. [Fitur Utama Sistem](#-fitur-utama-sistem)
2. [Arsitektur & Peran Pengguna](#-arsitektur--peran-pengguna)
3. [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
4. [Struktur Direktori & Database](#-struktur-direktori--database)
5. [Instalasi di Lingkungan Lokal (Localhost)](#-instalasi-di-lingkungan-lokal-localhost)
6. [Panduan Deployment ke VPS](#-panduan-deployment-ke-vps)
7. [Keamanan & Mekanisme Anti-Spam](#-keamanan--mekanisme-anti-spam)

---

## 🌟 Fitur Utama Sistem

### 1. 🤖 Chatbot Asisten Statistik 24 Jam (AI-Powered)

- **Konsultasi Interaktif**: Menjawab pertanyaan seputar indikator makro (IPM, Kemiskinan, Pertumbuhan Ekonomi/PDRB, Ketenagakerjaan, Inflasi, Pertanian) bersumber dari publikasi resmi seperti *Karanganyar Dalam Angka (KDA)*.
- **Voice-to-Text (Speech Recognition)**: Pengguna dapat berbicara langsung melalui mikrofon tanpa perlu mengetik panjang.
- **Text-to-Speech (Sintesis Suara)**: Fitur pembacaan audio otomatis untuk mendengarkan jawaban asisten AI (ramah disabilitas/aksesibilitas).
- **Rujukan Resmi & Quick Topic Chips**: Menyertakan tautan sumber rujukan BPS dan chip pertanyaan populer satu-klik.
- **Live Handoff ke Petugas**: Jika AI belum mencukupi atau pengguna memerlukan konsultasi mendalam, percakapan dapat dialihkan langsung ke antrean petugas PST pada jam kerja.

### 2. 📅 Reservasi Konsultasi Tatap Muka PST Offline

- **Booking Jadwal Fleksibel**: Pengguna dapat memilih tanggal dan slot sesi layanan tatap muka di Ruang Pelayanan Statistik Terpadu (PST) Kantor BPS Kabupaten Karanganyar (Jl. Lawu No. 202B).
- **Tiket Digital QR Code**: Menghasilkan nomor registrasi resmi (misal: `PST-BKG-202608-001`) dan tiket digital ber-QR Code untuk verifikasi kehadiran.
- **Pelacakan Status Mandiri**: Fitur lacak status persetujuan tanpa perlu login ulang.

### 3. 📊 Layanan Permohonan Data Mikro & ROMANTIK

- **Pengajuan Data Sektoral & Mikro**: Khusus untuk mahasiswa (skripsi/tesis), instansi OPD Pemda, dan peneliti.
- **Unggah Dokumen Proposal**: Dukungan upload surat pengantar atau proposal penelitian resmi (PDF, JPG, PNG).
- **Unduh Hasil Olahan Data**: Petugas dapat mengunggah berkas data hasil pengolahan yang siap diunduh oleh pemohon.

### 4. ⭐ Survei Kepuasan Masyarakat (SKM / IKPS)

- **Standar Evaluasi KemenPAN-RB**: Evaluasi 4 unsur mutu pelayanan (Kualitas Data, Kecepatan Respon, Keramahan Petugas, Fasilitas Sarana).
- **Kalkulasi Otomatis Skor IKM**: Penghitungan otomatis Indeks Kepuasan Masyarakat (skala 25–100) dan predikat mutu (A/B/C/D).

### 5. 🛡️ Saluran Pengaduan Layanan Resmi

- **Penyampaian Keluhan & Masukan**: Saluran whistleblowing resmi masyarakat terkait mutu pelayanan, website, data, dan SDM.
- **Enkripsi Kontak Pelapor**: Nomor telepon/email pelapor dienkripsi secara aman di database untuk menjaga privasi.
- **Nomor Tiket Pelacakan**: Log histori tahapan tindak lanjut laporan (*Diterima*, *Diproses*, *Selesai*).

### 6. 🗺️ Peta Tematik 17 Kecamatan Karanganyar

- Visualisasi data statistik spasial interaktif untuk 17 kecamatan di wilayah Kabupaten Karanganyar (populasi, kepadatan, fasilitas).

### 7. 🧮 Kalkulator Statistik Interaktif

- **Kalkulator Inflasi**: Menghitung perubahan daya beli dan nilai riil uang berdasarkan indeks harga konsumen.
- **Proyeksi Pertumbuhan Penduduk**: Menghitung estimasi jumlah penduduk di masa depan dengan metode geometrik/eksponensial.
- **Kalkulator Sampel Slovin**: Menghitung ukuran sampel minimum penelitian dengan tingkat presisi/margin of error yang ditentukan.

### 8. 👤 Akun & Profil Pengguna Masyarakat (`/profil-saya`)

- **Login Fleksibel**: Mendukung login konvensional (Email + Password) dan **Google OAuth 2.0 Single Sign-On (SSO)**.
- **Dashboard Profil**: Melihat riwayat pengajuan reservasi, permohonan data, aduan, dan survei yang pernah diajukan.
- **Edit Profil & Hapus Akun**: Pengguna memiliki kendali penuh untuk memperbarui profil atau menghapus akun secara permanen.

### 9. 🎛️ Panel Manajemen Admin & Petugas BPS (`/admin`)

- **Live Statistik Dashboard**: Monitor metrik kunjungan, antrean chat aktif, reservasi harian, aduan baru, dan skor SKM.
- **Live Chat Takeover & Respon**: Petugas dapat mengambil alih percakapan bot secara real-time.
- **Basis Pengetahuan (Knowledge Base)**: Pengelolaan artikel dan dataset referensi chatbot AI.
- **Laporan Rekapitulasi PDF**: Cetak dan ekspor laporan layanan berkala dengan format resmi Kop Surat BPS.

---

## 👥 Arsitektur & Peran Pengguna

| Peran (Role) | Hak Akses & Kemampuan |
| :--- | :--- |
| **Guest / Publik** | Mengakses Beranda, Chatbot Publik, Peta Statistik, Kalkulator, dan Pelacakan Tiket (Lacak Reservasi, Lacak Data, Status Aduan). |
| **User (Masyarakat Terdaftar)** | Seluruh akses publik + membuat reservasi tatap muka, mengajukan data mikro, mengisi survei SKM, membuat aduan, dan mengelola profil akun mandiri di `/profil-saya`. |
| **Petugas BPS** | Mengakses Panel Admin (`/admin`), menangani percakapan live chat, memproses tiket reservasi & data mikro, menindaklanjuti aduan, dan mengunduh laporan PDF. |
| **Administrator** | Akses penuh sistem: manajemen pengguna, konfigurasi basis pengetahuan AI, audit log, dan pengaturan master data. |

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 8.2+ dengan Framework **Laravel 12**
- **Autentikasi**: Laravel Breeze + Laravel Socialite (Google OAuth 2.0)
- **Frontend**: Blade Templating + TailwindCSS 3.x + Vanilla JS
- **Icons & UI**: Iconify (Lucide Icons, Logos) + SweetAlert2
- **Ekspor Dokumen**: `barryvdh/laravel-dompdf` (Generasi Laporan Resmi PDF)
- **Database**: MySQL 8.0 / MariaDB / PostgreSQL (SQLite untuk dev lokal)
- **AI Integration**: Endpoint LLM kompatibel OpenAI / Google Gemini

---

## 📁 Struktur Direktori & Database

```text
chatbot-bps-karanganyar/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller Panel Admin & Petugas
│   │   │   ├── Auth/           # Controller Autentikasi & Google SSO
│   │   │   └── ...             # Controller Layanan Publik
│   │   └── Middleware/         # Proteksi Role & Security Headers
│   ├── Models/                 # Model Eloquent (User, Complaint, Conversation, dll)
│   └── Services/               # Logika Bisnis (AI Client, Ticket Service)
├── database/
│   ├── migrations/             # Struktur skema tabel database
│   └── seeders/                # Data awal & akun admin default
├── resources/
│   ├── views/
│   │   ├── admin/              # Tampilan Dashboard & Modul Admin
│   │   ├── layouts/            # Master Layout (public.blade.php & admin.blade.php)
│   │   ├── my-profile/         # Halaman Profil Masyarakat
│   │   └── ...                 # View Formulir Layanan Publik
│   └── css/ & js/              # Asset Tailwind & JavaScript Vite
├── routes/
│   ├── auth.php                # Rute autentikasi Laravel Breeze & Google OAuth
│   └── web.php                 # Rute publik & rute admin berproteksi
└── DEPLOYMENT_VPS.md           # Panduan lengkap hosting VPS
```

---

## 💻 Instalasi di Lingkungan Lokal (Localhost)

### Prasyarat

- PHP >= 8.2 (dengan ekstensi: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- Database MySQL atau SQLite

### Langkah-langkah

1. **Clone repositori**:

   ```bash
   git clone https://github.com/faizarfi/aichatbotbps.git
   cd chatbot-bps-karanganyar
   ```

2. **Instal dependensi Composer & Node.js**:

   ```bash
   composer install
   npm install
   ```

3. **Setup Berkas Konfigurasi `.env`**:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database & API AI 9router di `.env`**:

   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_chatbot_bps
   DB_USERNAME=root
   DB_PASSWORD=

   # =========================================================================
   # KONFIGURASI AI CHATBOT (9ROUTER / OPENROUTER / GEMINI)
   # =========================================================================
   # 9router adalah AI Gateway kompatibel OpenAI API
   AI_BASE_URL=https://api.9router.com/v1
   AI_API_KEY=sk-9router-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   AI_MODEL=ag/gemini-3-flash
   AI_TIMEOUT=45

   # Catatan: Model alternatif yang didukung di 9router / OpenRouter:
   # - ag/gemini-3-flash (Default berkecepatan tinggi)
   # - google/gemini-2.0-flash-001
   # - openai/gpt-4o-mini
   # - deepseek/deepseek-chat
   # - meta-llama/llama-3.3-70b-instruct

   # Google OAuth (Opsional untuk Login Google)
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
   ```

---

## 🧠 Panduan Integrasi AI Engine (9router / OpenRouter)

Aplikasi ini menggunakan modul `App\Services\AiLlmService` yang terhubung melalui standar **OpenAI Chat Completions API** (`/v1/chat/completions`). Hal ini memungkinkan pergantian provider AI secara fleksibel hanya dengan mengubah konfigurasi di `.env` tanpa mengubah kode program.

### Cara Menghubungkan 9router ke Aplikasi

1. **Dapatkan API Key dari 9router**:
   - Masuk ke dashboard akun 9router Anda.
   - Buat **API Key** baru (berawalan `sk-...` atau format token 9router Anda).
   - Pastikan saldo/kredit token tersedia dan model yang diinginkan telah diaktifkan.

2. **Atur 4 Variabel Environment di `.env`**:

   | Variabel `.env` | Nilai Rekomendasi | Penjelasan |
   | :--- | :--- | :--- |
   | `AI_BASE_URL` | `https://api.9router.com/v1` | Endpoint URL API 9router (atau port proxy lokal jika menggunakan desktop client) |
   | `AI_API_KEY` | `sk-9router-xxxxxx` | Kunci otentikasi API yang Anda miliki dari 9router |
   | `AI_MODEL` | `ag/gemini-3-flash` atau `google/gemini-2.0-flash-001` | ID model AI yang digunakan untuk menjawab pertanyaan data statistik |
   | `AI_TIMEOUT` | `45` | Batas waktu tunggu respon API dalam satuan detik (timeout) |

3. **Cara Kerja Retrieval-Augmented Generation (RAG)**:
   - Saat masyarakat bertanya ke chatbot, sistem terlebih dahulu mencari data relevan di **Basis Pengetahuan BPS (Knowledge Base)** di database lokal.
   - Sistem melakukan sensor otomatis terhadap data sensitif seperti NIK, Nomor HP, atau Email (`PersonalDataRedactor`).
   - Konteks data resmi dan riwayat chat dikirimkan ke model AI di 9router dengan *System Prompt* resmi BPS Karanganyar.
   - Jawaban yang dihasilkan AI langsung dikembalikan ke antarmuka pengguna lengkap dengan tautan sumber publikasi resmi BPS.

4. **Uji Coba Respon AI**:
   Setelah mengisi `.env`, lakukan clear config agar konfigurasi terbaca:

   ```bash
   php artisan config:clear
   ```

   Buka menu **Chatbot** di browser dan ketik pertanyaan (contoh: *"Berapa persentase penduduk miskin di Kabupaten Karanganyar tahun terakhir?"*). Bot akan langsung menjawab menggunakan inteligensi 9router!

5. **Jalankan Migrasi & Seeder Database**:

   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

6. **Jalankan Server Development**:

   ```bash
   composer run dev
   ```

   Aplikasi akan berjalan di `http://127.0.0.1:8000`.

---

## 🚀 Panduan Deployment ke VPS

Dokumentasi lengkap langkah demi langkah untuk melakukan hosting website ini ke **Virtual Private Server (VPS)** berbasis Linux Ubuntu/Debian dengan **Nginx, PHP-FPM, MySQL, SSL Gratis (Let's Encrypt), Supervisor, dan Scheduler Cron** telah kami sediakan secara terpisah dan mendalam di:

👉 **[BACA PANDUAN LENGKAP DEPLOYMENT VPS (DEPLOYMENT_VPS.md)](./DEPLOYMENT_VPS.md)**

---

## 🔒 Keamanan & Mekanisme Anti-Spam

Untuk menjaga kualitas data pelayanan publik dan mencegah serangan spam bot ataupun entri data kosong, sistem menerapkan mekanisme:

1. **Wajib Login untuk Layanan Publik**: Form reservasi PST, permohonan data mikro, survei kepuasan, dan pengaduan dilindungi middleware `auth`. Pengunjung diarahkan masuk terlebih dahulu (tersedia Google SSO 1-klik).
2. **Intended URL Redirect**: Pengguna yang diarahkan ke halaman login akan secara otomatis dikembalikan ke formulir yang dituju setelah berhasil login.
3. **Data Isolation & Traceability**: Setiap transaksi layanan otomatis terhubung dengan `user_id` pemohon.
4. **Rate Limiting (Throttle)**: Endpoint pengiriman pesan bot dan form dibatasi frekuensi aksesnya untuk menangkal brute-force dan flood request.
5. **Data Masking & Encryption**: Kontak pelapor aduan dienkripsi menggunakan fitur enkripsi native Laravel `casts => encrypted`.

---

## 📄 Lisensi

di kembangkan oleh faiz arfian ilhami
