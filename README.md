# 🏛️ Portal Layanan Statistik Terpadu & Chatbot AI BPS Kabupaten Karanganyar

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

Portal resmi pelayanan publik digital dan kecerdasan buatan (AI) \ Aplikasi ini dirancang untuk mempermudah masyarakat, peneliti, akademisi, dan instansi daerah dalam mengakses data statistik resmi, konsultasi online/offline, pengajuan data mikro & ROMANTIK, survei kepuasan, hingga pengaduan layanan secara terpadu dan transparan.

---

## 🚀 Fitur Ringkas

- **🤖 AI Chatbot Statistik 24 Jam**: Konsultasi data makro daerah (Kemiskinan, IPM, PDRB, Ketenagakerjaan, Pertanian) dengan sistem *Deep Reasoning*, grounding data resmi 2026, visualisasi grafik *Chart.js*, serta handoff ke petugas PST.
- **🌐 Multi-Bahasa Global (80+ Bahasa)**: Terintegrasi dengan Google Cloud Translation di seluruh halaman serta respon AI adaptif (Bahasa Indonesia, English, Basa Jawa Krama Alus, Arab, Jepang, Mandarin, dll.).
- **🎙️ Web Speech AI**: Dilengkapi *Speech-to-Text* (Voice Mic) dan *Text-to-Speech* (Audio Reader) ramah aksesibilitas lintas bahasa.
- **📅 Reservasi Layanan Tatap Muka**: Booking sesi konsultasi offline di PST BPS Karanganyar lengkap dengan tiket QR Code digital.
- **📊 Permohonan Data Mikro & ROMANTIK**: Pengajuan raw data skripsi/penelitian (tarif Rp0,-) dan rekomendasi kegiatan statistik sektoral bagi OPD.
- **⭐ Survei Kepuasan Masyarakat (SKM)**: Evaluasi mutu layanan 4 unsur standar KemenPAN-RB dengan kalkulasi otomatis Indeks Kepuasan.
- **🛡️ Saluran Pengaduan Resmi**: Pelaporan pengaduan masyarakat terenkripsi dengan kode tiket pelacakan status.
- **🗺️ Peta Tematik & Kalkulator Statistik**: Visualisasi 17 kecamatan Karanganyar dan alat hitung Inflasi, Proyeksi Penduduk, serta Sampel Slovin.
- **🎛️ Panel Manajemen Admin & Petugas**: Monitoring antrean live chat, manajemen basis pengetahuan (RAG), dan cetak rekapitulasi PDF ber-KOP resmi.

---

## 🛠️ Ringkasan Teknologi

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 12 (PHP 8.2+) |
| **Frontend & UI** | Blade Templating, Tailwind CSS 3.x, Iconify (Lucide Icons) |
| **Database** | MySQL / MariaDB / PostgreSQL / SQLite |
| **Mesin AI (LLM)** | Google AI Studio (Gemini 3.6 Flash / Gemini Direct) |
| **Penerjemah** | Google Cloud Translation Engine (Headless Integration) |
| **Generasi Dokumen** | Barryvdh DomPDF (Ekspor Lembar Rekap PDF) |

---

## 💻 Cara Menjalankan di Komputer Lokal (Quick Start)

### 1. Clone Repositori

```bash
git clone https://github.com/faizarfi/aichatbotbps.git
```

### 2. Instal Dependensi

```bash
composer install
npm install
```

### 3. Setup Konfigurasi

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan database dan konfigurasi AI (`AI_API_KEY`) di file `.env`.

### 4. Migrasi Database & Jalankan Server

```bash
php artisan migrate --seed
php artisan storage:link
composer run dev
```

Buka `http://127.0.0.1:8000` di peramban Anda.

---

## 📖 Panduan Deployment VPS (Lengkap & Terperinci)

Untuk panduan lengkap langkah demi langkah mengenai cara memasang website ini di **Virtual Private Server (VPS)** Linux Ubuntu (Nginx, SSL HTTPS, MySQL, Supervisor Queue, Scheduler, dan CI/CD Webhook otomatis), silakan baca dokumentasi khusus berikut:

👉 **[BACA DOKUMEN PANDUAN LENGKAP DEPLOYMENT VPS (DEPLOYMENT_VPS.md)](./DEPLOYMENT_VPS.md)**

---

## 👤 Pengembang

Dikembangkan oleh **Faiz Arfian Ilhami**
