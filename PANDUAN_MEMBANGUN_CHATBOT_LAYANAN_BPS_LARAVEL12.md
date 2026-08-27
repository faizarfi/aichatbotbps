# Panduan Membangun Chatbot Layanan dan Aduan BPS Karanganyar

> Stack: Laravel 12, PHP 8.2+, MySQL/XAMPP, Blade, Tailwind CSS, JavaScript, OpenRouter, dan WhatsApp Cloud API pada tahap terakhir.

## 1. Tujuan Proyek

Membangun web layanan masyarakat yang memiliki:

1. Chatbot informasi layanan statistik.
2. Basis pengetahuan yang dikelola petugas.
3. Form aduan dan nomor tiket otomatis.
4. Dashboard admin/petugas.
5. Riwayat percakapan dan status aduan.
6. Pengalihan percakapan dari bot ke petugas.
7. Integrasi OpenRouter untuk menyusun jawaban berdasarkan sumber resmi.
8. Integrasi WhatsApp Cloud API setelah versi web stabil.

Jika proyek ini belum memperoleh izin resmi instansi, tampilkan label **“Prototipe — bukan kanal layanan resmi”** dan jangan memakai logo atau identitas resmi tanpa izin.

---

## 2. Prinsip Sistem

Chatbot tidak boleh menjawab bebas tanpa sumber. Alur jawabannya:

1. Menerima pertanyaan masyarakat.
2. Mendeteksi apakah pesan termasuk pertanyaan layanan, aduan, sapaan, atau permintaan petugas.
3. Mencari informasi relevan dari tabel basis pengetahuan.
4. Jika jawaban ditemukan, OpenRouter hanya membantu menyusun jawaban yang rapi.
5. Jika informasi tidak ditemukan, bot mengatakan bahwa informasi belum tersedia.
6. Percakapan yang sensitif atau tidak meyakinkan dialihkan ke petugas.

Untuk aduan, data pribadi dan isi sensitif disimpan di server aplikasi dan tidak dikirim ke AI eksternal.

---

## 3. Tahapan Pengerjaan

Urutan yang disarankan:

- [ ] Tahap 0 — Amankan kredensial.
- [ ] Tahap 1 — Siapkan Laravel dan database.
- [ ] Tahap 2 — Buat autentikasi dan role petugas.
- [ ] Tahap 3 — Buat tampilan web publik.
- [ ] Tahap 4 — Buat CRUD basis pengetahuan.
- [ ] Tahap 5 — Buat chatbot FAQ tanpa AI.
- [ ] Tahap 6 — Hubungkan OpenRouter.
- [ ] Tahap 7 — Buat sistem tiket aduan.
- [ ] Tahap 8 — Buat dashboard percakapan petugas.
- [ ] Tahap 9 — Tambahkan queue, keamanan, dan audit.
- [ ] Tahap 10 — Pengujian dan deployment.
- [ ] Tahap 11 — Integrasi WhatsApp Cloud API.

Selesaikan dan uji satu tahap sebelum berpindah ke tahap berikutnya.

---

## 4. Tahap 0 — Amankan Kredensial

Access token Meta yang pernah ditampilkan atau dikirim melalui chat harus dianggap bocor.

1. Cabut akses aplikasi pada menu **Integrasi Bisnis / Aplikasi dan Situs Web** di Facebook.
2. Buat token baru melalui Meta Developer.
3. Jangan menyimpan token di source code.
4. Jangan mengunggah file `.env` ke GitHub.
5. Jangan mengirim screenshot yang memperlihatkan token, password, OTP, nomor telepon, atau email pribadi.

Pastikan `.gitignore` berisi:

```gitignore
.env
/vendor
/node_modules
/storage/*.key
```

---

## 5. Tahap 1 — Persiapan Laravel 12

### 5.1 Perangkat yang diperlukan

- Windows 10/11.
- XAMPP dengan PHP minimal 8.2.
- Composer.
- Node.js dan npm.
- MySQL melalui XAMPP.
- Visual Studio Code.
- Git.

Periksa versi:

```cmd
php -v
composer --version
node -v
npm -v
git --version
```

Jika perintah `php` masih diarahkan ke WinGet dan diblokir Device Guard, gunakan PHP XAMPP:

```cmd
C:\xampp\php\php.exe -v
```

Pastikan `C:\xampp\php` berada di urutan atas pada Environment Variable `Path`.

### 5.2 Buat proyek

Buka CMD:

```cmd
cd C:\xampp\htdocs
composer create-project laravel/laravel chatbot-bps-karanganyar "12.*"
cd chatbot-bps-karanganyar
php artisan key:generate
npm install
```

Jika `php` belum dikenali, ganti perintah Artisan menjadi:

```cmd
C:\xampp\php\php.exe artisan key:generate
```

Jalankan aplikasi:

```cmd
php artisan serve
```

Pada CMD kedua:

```cmd
npm run dev
```

Buka:

```text
http://127.0.0.1:8000
```

### 5.3 Buat database

1. Jalankan Apache dan MySQL dari XAMPP.
2. Buka `http://localhost/phpmyadmin`.
3. Buat database:

```text
chatbot_bps_karanganyar
```

Atur `.env`:

```env
APP_NAME="Chatbot Layanan Statistik Karanganyar"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatbot_bps_karanganyar
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
```

Jalankan:

```cmd
php artisan config:clear
php artisan migrate
```

### 5.4 Inisialisasi Git

```cmd
git init
git add .
git commit -m "Inisialisasi Laravel 12"
```

Jangan jalankan `git add` jika file `.env` ikut terdeteksi.

---

## 6. Rancangan Fitur

### 6.1 Halaman publik

| Halaman | URL | Fungsi |
| --- | --- | --- |
| Beranda | `/` | Informasi layanan dan tombol mulai chat |
| Chatbot | `/chat` | Percakapan masyarakat dengan bot |
| Buat aduan | `/aduan` | Form pengiriman aduan |
| Cek status | `/status-aduan` | Pencarian status berdasarkan nomor tiket |
| Kebijakan privasi | `/kebijakan-privasi` | Penjelasan penggunaan dan penyimpanan data |

### 6.2 Halaman petugas

| Halaman | URL | Fungsi |
| --- | --- | --- |
| Login | `/admin/login` | Login admin/petugas |
| Dashboard | `/admin/dashboard` | Statistik ringkas |
| Basis pengetahuan | `/admin/pengetahuan` | CRUD FAQ dan informasi layanan |
| Percakapan | `/admin/percakapan` | Daftar chat dan pengalihan ke petugas |
| Aduan | `/admin/aduan` | Proses dan perubahan status aduan |
| Pengguna | `/admin/pengguna` | Kelola admin dan petugas |
| Pengaturan | `/admin/pengaturan` | Jam layanan, identitas, dan pesan otomatis |

---

## 7. Rancangan Database

### 7.1 Tabel utama

#### `users`

Tambahkan ke tabel pengguna bawaan Laravel:

- `role`: `admin` atau `petugas`.
- `is_active`: boolean.
- `last_login_at`: nullable timestamp.

#### `knowledge_categories`

- `id`
- `name`
- `slug`
- `description`
- `is_active`
- timestamps

#### `knowledge_articles`

- `id`
- `knowledge_category_id`
- `title`
- `question`
- `answer`
- `keywords`: JSON nullable
- `source_title`: nullable
- `source_url`: nullable
- `published_at`: nullable
- `is_active`: boolean
- `created_by`: foreign key ke `users`
- timestamps

Tambahkan FULLTEXT index pada `title`, `question`, dan `answer` untuk pencarian awal.

#### `conversations`

- `id`
- `public_id`: UUID, unique
- `channel`: `web` atau `whatsapp`
- `visitor_session`: nullable, index
- `visitor_name`: nullable
- `visitor_contact`: nullable dan terenkripsi
- `status`: `bot`, `waiting`, `handled`, atau `closed`
- `assigned_to`: nullable foreign key ke `users`
- `last_message_at`
- timestamps

#### `messages`

- `id`
- `conversation_id`
- `sender_type`: `visitor`, `bot`, atau `officer`
- `sender_user_id`: nullable
- `content`: text
- `knowledge_sources`: JSON nullable
- `ai_model`: nullable
- `confidence`: nullable decimal
- `is_fallback`: boolean
- timestamps

#### `complaints`

- `id`
- `ticket_number`: unique
- `conversation_id`: nullable
- `category`
- `reporter_name`
- `reporter_contact`: terenkripsi
- `description`: text
- `status`: `new`, `verified`, `processing`, `resolved`, atau `rejected`
- `priority`: `low`, `normal`, `high`
- `assigned_to`: nullable
- `resolved_at`: nullable
- timestamps

#### `complaint_attachments`

- `id`
- `complaint_id`
- `original_name`
- `stored_path`
- `mime_type`
- `file_size`
- timestamps

#### `complaint_status_logs`

- `id`
- `complaint_id`
- `status`
- `note`: nullable
- `changed_by`
- timestamps

#### `bot_feedback`

- `id`
- `message_id`
- `rating`: `helpful` atau `not_helpful`
- `comment`: nullable
- timestamps

#### `webhook_events`

- `id`
- `external_event_id`: unique
- `event_type`
- `payload_hash`
- `processing_status`
- `processed_at`: nullable
- timestamps

Simpan payload webhook seperlunya. Hindari menyimpan data mentah yang tidak dibutuhkan.

### 7.2 Buat model dan migration

Contoh perintah:

```cmd
php artisan make:model KnowledgeCategory -m
php artisan make:model KnowledgeArticle -m
php artisan make:model Conversation -m
php artisan make:model Message -m
php artisan make:model Complaint -m
php artisan make:model ComplaintAttachment -m
php artisan make:model ComplaintStatusLog -m
php artisan make:model BotFeedback -m
php artisan make:model WebhookEvent -m
```

Setelah migration selesai ditulis:

```cmd
php artisan migrate
```

---

## 8. Tahap 2 — Autentikasi dan Role

Gunakan autentikasi berbasis session untuk admin dan petugas.

Alur:

1. Admin membuka `/admin/login`.
2. Kredensial divalidasi menggunakan `Auth::attempt()`.
3. Pastikan pengguna aktif.
4. Regenerasi session setelah login.
5. Arahkan ke dashboard.
6. Logout harus menghapus session dan membuat ulang CSRF token.

Buat middleware role:

```cmd
php artisan make:middleware EnsureUserHasRole
```

Aturan akses:

- `admin`: seluruh fitur.
- `petugas`: percakapan, aduan, dan melihat basis pengetahuan.

Buat admin awal menggunakan seeder, jangan mendaftarkan admin dari halaman publik:

```cmd
php artisan make:seeder AdminUserSeeder
php artisan db:seed --class=AdminUserSeeder
```

Password seeder harus diambil dari environment atau segera diganti setelah login pertama.

---

## 9. Tahap 3 — Desain Web Publik

Gunakan Bahasa Indonesia, responsif, profesional, dan tidak berlebihan.

### 9.1 Identitas tampilan

- Warna utama: biru tua dan biru muda.
- Aksen: hijau kebiruan atau oranye secukupnya.
- Latar: putih dan abu-abu sangat muda.
- Gunakan Iconify atau Lucide, bukan emoji.
- Hindari elemen yang terlalu besar.
- Gunakan animasi ringan untuk membuka chat dan indikator mengetik.
- Tampilkan label prototipe jika belum resmi.

### 9.2 Beranda

Bagian yang disarankan:

1. Navbar.
2. Hero dengan judul layanan statistik dan tombol **Mulai Percakapan**.
3. Kartu layanan populer.
4. Cara menggunakan chatbot.
5. Jam operasional petugas.
6. FAQ ringkas.
7. Tombol buat aduan.
8. Kontak dan footer.

### 9.3 Widget chatbot

Komponen:

- Tombol chat mengambang.
- Panel chat responsif.
- Pesan pengguna dan bot berbeda warna.
- Indikator mengetik.
- Tombol pertanyaan cepat.
- Tautan sumber jawaban.
- Tombol **Jawaban membantu / tidak membantu**.
- Tombol **Hubungi petugas**.
- Tombol **Buat aduan**.

---

## 10. Tahap 4 — CRUD Basis Pengetahuan

Buat controller:

```cmd
php artisan make:controller Admin/KnowledgeCategoryController --resource
php artisan make:controller Admin/KnowledgeArticleController --resource
```

Fitur wajib:

- Tambah, edit, lihat, dan nonaktifkan artikel.
- Kategori layanan.
- Pencarian judul/pertanyaan.
- Filter status aktif.
- Pagination.
- Validasi URL sumber.
- Catat pembuat dan waktu perubahan.

Contoh isi awal:

- Cara memperoleh data statistik.
- Jadwal layanan.
- Jenis publikasi.
- Permintaan data khusus.
- Konsultasi statistik.
- Lokasi dan kontak layanan.
- Prosedur pengaduan.

Setiap jawaban harus memiliki sumber atau keterangan penanggung jawab.

---

## 11. Tahap 5 — Chatbot FAQ Tanpa AI

Bangun versi tanpa AI terlebih dahulu agar alur dasarnya dapat diuji.

Buat class:

```cmd
php artisan make:controller PublicChatController
php artisan make:class Services/KnowledgeSearchService
php artisan make:class Services/ChatService
```

Alur `ChatService`:

1. Validasi pesan maksimal, misalnya, 1.000 karakter.
2. Terapkan rate limit.
3. Cari artikel aktif berdasarkan kata kunci atau FULLTEXT.
4. Pilih hasil dengan skor terbaik.
5. Jika ditemukan, kirim jawaban artikel beserta sumber.
6. Jika tidak ditemukan, kirim fallback dan tawarkan petugas.
7. Simpan percakapan dan pesan.

Route awal pada `routes/web.php`:

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/chat', [PublicChatController::class, 'index'])->name('chat.index');
Route::post('/chat/message', [PublicChatController::class, 'store'])
    ->middleware('throttle:chat')
    ->name('chat.message');
```

Gunakan `@csrf` pada semua request POST dari web.

### Uji tahap ini

- Pertanyaan yang sama persis dengan FAQ.
- Pertanyaan dengan ejaan berbeda.
- Pesan kosong.
- Pesan sangat panjang.
- Spam berulang.
- Informasi tidak ditemukan.
- Percakapan baru setelah browser ditutup.

Jangan lanjut ke OpenRouter sebelum chatbot FAQ dasar berfungsi.

---

## 12. Tahap 6 — Integrasi OpenRouter

OpenRouter tidak digunakan untuk melatih model dari awal. Gunakan OpenRouter untuk menyusun jawaban berdasarkan artikel yang ditemukan dari database.

Tambahkan ke `.env`:

```env
OPENROUTER_API_KEY=
OPENROUTER_MODEL=
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
```

Tambahkan konfigurasi ke `config/services.php`:

```php
'openrouter' => [
    'key' => env('OPENROUTER_API_KEY'),
    'model' => env('OPENROUTER_MODEL'),
    'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
],
```

Buat service:

```cmd
php artisan make:class Services/OpenRouterService
php artisan make:class Services/PromptBuilder
php artisan make:class Services/PersonalDataRedactor
```

Aturan prompt sistem:

```text
Anda adalah asisten layanan statistik.
Jawab hanya menggunakan konteks resmi yang diberikan.
Jangan mengarang persyaratan, jadwal, biaya, nomor kontak, atau angka statistik.
Jika konteks tidak cukup, nyatakan bahwa informasi belum ditemukan.
Sarankan pengguna menghubungi petugas jika pertanyaan membutuhkan konfirmasi.
Gunakan Bahasa Indonesia yang sopan, jelas, dan ringkas.
```

Sebelum mengirim prompt ke OpenRouter:

1. Hapus atau samarkan NIK.
2. Samarkan nomor telepon dan email.
3. Jangan kirim lampiran aduan.
4. Jangan kirim isi aduan sensitif.
5. Batasi jumlah artikel dan panjang konteks.

Jika OpenRouter gagal atau timeout, gunakan jawaban FAQ asli sebagai fallback.

Simpan metadata seperlunya:

- Model yang digunakan.
- Waktu respons.
- Jumlah token jika tersedia.
- Status berhasil/gagal.

Jangan menyimpan API key atau prompt sensitif di log.

---

## 13. Tahap 7 — Sistem Tiket Aduan

Buat controller:

```cmd
php artisan make:controller ComplaintController
php artisan make:controller Admin/ComplaintController
php artisan make:class Services/TicketNumberService
```

Format nomor tiket:

```text
ADU-2026-000001
```

Nomor harus dibuat di dalam transaksi database dan memiliki unique index agar tidak ganda.

Form aduan minimal:

- Nama pelapor.
- Kontak yang dapat dihubungi.
- Kategori.
- Uraian.
- Tanggal kejadian jika relevan.
- Lampiran opsional.
- Persetujuan pemrosesan data.

Validasi lampiran:

- Hanya format yang dibutuhkan, misalnya PDF/JPG/PNG.
- Periksa MIME type, bukan hanya ekstensi.
- Batasi ukuran file.
- Simpan dengan nama acak di storage privat.
- Unduhan lampiran hanya boleh melalui controller yang memeriksa hak akses.

Status aduan:

```text
new → verified → processing → resolved
                     └──────→ rejected
```

Setiap perubahan status harus masuk ke `complaint_status_logs`.

---

## 14. Tahap 8 — Dashboard Petugas dan Human Handoff

Dashboard menampilkan:

- Percakapan hari ini.
- Percakapan menunggu petugas.
- Aduan baru.
- Aduan sedang diproses.
- Persentase pertanyaan terjawab.
- Artikel yang paling sering digunakan.
- Jawaban yang mendapat feedback negatif.

Alur pengalihan ke petugas:

1. Pengguna memilih **Hubungi petugas** atau bot tidak menemukan jawaban.
2. Status percakapan menjadi `waiting`.
3. Petugas mengambil percakapan.
4. Status menjadi `handled` dan `assigned_to` diisi.
5. Bot berhenti menjawab otomatis selama petugas menangani percakapan.
6. Petugas menutup percakapan setelah selesai.

MVP dapat menggunakan polling JavaScript setiap beberapa detik. Realtime WebSocket dapat ditambahkan setelah versi dasar stabil.

---

## 15. Tahap 9 — Queue dan Automation Laravel

Proses yang harus memakai queue:

- Request ke OpenRouter.
- Pengiriman notifikasi.
- Pemrosesan webhook WhatsApp.
- Pembersihan riwayat lama.
- Rekap statistik berkala.

Buat tabel queue jika belum tersedia:

```cmd
php artisan make:queue-table
php artisan migrate
```

Jalankan worker saat development:

```cmd
php artisan queue:work --tries=3 --timeout=60
```

Jadwal automation dapat ditempatkan di `routes/console.php`, misalnya:

- Menandai percakapan tidak aktif sebagai selesai.
- Menghapus session chat kedaluwarsa.
- Mengirim pengingat aduan yang belum diproses.
- Membuat laporan harian.

Pada produksi, queue worker harus dijalankan oleh process manager. Jangan mengandalkan CMD yang dibuka manual.

---

## 16. Keamanan Wajib

- Gunakan HTTPS pada produksi.
- `APP_DEBUG=false` pada produksi.
- Gunakan validasi Form Request.
- Gunakan CSRF untuk form web.
- Gunakan rate limit untuk chat, login, status tiket, dan webhook.
- Regenerasi session setelah login.
- Terapkan role dan authorization policy.
- Enkripsi data kontak sensitif.
- Jangan tampilkan ID database pada URL publik; gunakan UUID atau nomor tiket.
- Jangan menyimpan token di JavaScript frontend.
- Sanitasi output sebelum ditampilkan.
- Batasi dan periksa lampiran.
- Buat audit log tindakan petugas.
- Backup database dan storage.
- Terapkan masa retensi data.
- Jangan mengirim data aduan sensitif ke OpenRouter.

---

## 17. Tahap 10 — Testing

### 17.1 Feature test yang diperlukan

```cmd
php artisan make:test PublicChatTest
php artisan make:test KnowledgeArticleTest
php artisan make:test ComplaintTest
php artisan make:test AdminAuthenticationTest
php artisan make:test WhatsAppWebhookTest
```

Skenario minimal:

- Pengunjung dapat membuka chat.
- Pesan kosong ditolak.
- Rate limit bekerja.
- Artikel nonaktif tidak digunakan bot.
- Bot tidak mengarang saat sumber kosong.
- Aduan menghasilkan nomor tiket unik.
- Pengunjung tidak dapat membuka dashboard admin.
- Petugas tidak dapat mengelola akun admin.
- Lampiran berbahaya ditolak.
- OpenRouter timeout menghasilkan fallback.
- Event webhook yang sama tidak diproses dua kali.

Jalankan:

```cmd
php artisan test
```

### 17.2 Pemeriksaan sebelum deployment

- [ ] Seluruh test lulus.
- [ ] Tidak ada secret di repository.
- [ ] `APP_DEBUG=false`.
- [ ] Database sudah di-backup.
- [ ] Queue worker aktif.
- [ ] Scheduler aktif.
- [ ] HTTPS aktif.
- [ ] Hak akses storage benar.
- [ ] Akun admin awal sudah mengganti password.
- [ ] Label prototipe atau izin instansi sudah jelas.

---

## 18. Tahap 11 — WhatsApp Setelah Web Stabil

Jangan mulai tahap ini sebelum:

- Web chatbot berjalan.
- Basis pengetahuan dapat dikelola.
- OpenRouter memiliki fallback.
- Sistem aduan berjalan.
- Dashboard petugas berfungsi.
- Aplikasi tersedia melalui HTTPS publik.

### 18.1 Environment

```env
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_BUSINESS_ACCOUNT_ID=
WHATSAPP_VERIFY_TOKEN=
WHATSAPP_GRAPH_VERSION=
```

`WHATSAPP_VERIFY_TOKEN` adalah string rahasia yang dibuat sendiri, bukan access token Meta.

### 18.2 Endpoint webhook

```text
GET  /api/whatsapp/webhook   — verifikasi dari Meta
POST /api/whatsapp/webhook   — menerima event dan pesan
```

Contoh URL Callback:

```text
https://domain-anda.com/api/whatsapp/webhook
```

`localhost` tidak dapat digunakan sebagai callback Meta. Gunakan deployment HTTPS atau tunnel hanya untuk development.

### 18.3 Alur webhook

1. Meta mengirim event ke endpoint Laravel.
2. Laravel memvalidasi request.
3. Simpan ID event untuk idempotency.
4. Balas HTTP `200` secepat mungkin.
5. Proses pesan melalui queue.
6. Gunakan `ChatService` yang sama dengan chatbot web.
7. Kirim jawaban melalui WhatsApp Cloud API.
8. Simpan riwayat dengan `channel=whatsapp`.

Jangan membuat log berisi access token atau payload pribadi secara penuh.

---

## 19. Struktur Folder yang Disarankan

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── ComplaintController.php
│   │   ├── HomeController.php
│   │   ├── PublicChatController.php
│   │   └── WhatsAppWebhookController.php
│   ├── Middleware/
│   └── Requests/
├── Jobs/
│   ├── GenerateBotReply.php
│   └── ProcessWhatsAppMessage.php
├── Models/
├── Policies/
└── Services/
    ├── ChatService.php
    ├── KnowledgeSearchService.php
    ├── OpenRouterService.php
    ├── PersonalDataRedactor.php
    ├── PromptBuilder.php
    ├── TicketNumberService.php
    └── WhatsAppService.php

resources/views/
├── admin/
├── auth/
├── components/
├── complaints/
├── layouts/
├── chat.blade.php
└── home.blade.php
```

---

## 20. Urutan Implementasi Praktis

Kerjakan dalam commit kecil:

```text
1. Inisialisasi Laravel dan database
2. Autentikasi admin/petugas
3. Layout publik dan admin
4. CRUD kategori pengetahuan
5. CRUD artikel pengetahuan
6. Chatbot FAQ tanpa AI
7. Penyimpanan percakapan
8. Integrasi OpenRouter dan fallback
9. Sistem tiket aduan
10. Dashboard dan human handoff
11. Queue, rate limit, dan audit
12. Testing
13. Deployment HTTPS
14. Webhook WhatsApp
15. Nomor WhatsApp produksi
```

Setiap commit harus dapat dijalankan sebelum melanjutkan ke fitur berikutnya.

---

## 21. Definition of Done MVP

Versi web MVP dianggap selesai jika:

- [ ] Pengunjung dapat bertanya dari website.
- [ ] Jawaban berasal dari basis pengetahuan aktif.
- [ ] Jawaban menampilkan sumber.
- [ ] Bot memiliki fallback saat tidak mengetahui jawaban.
- [ ] Pengunjung dapat meminta petugas.
- [ ] Pengunjung dapat membuat dan memeriksa aduan.
- [ ] Petugas dapat mengelola artikel.
- [ ] Petugas dapat menangani percakapan dan aduan.
- [ ] Data pribadi tidak dikirim ke AI.
- [ ] Seluruh endpoint penting memiliki validasi dan rate limit.
- [ ] Test utama lulus.
- [ ] Aplikasi dapat berjalan di HTTPS publik.

Setelah semua poin tersebut selesai, lanjutkan integrasi WhatsApp Cloud API menggunakan service dan alur percakapan yang sama.

---

## 22. Aturan Saat Meminta Bantuan Coding

Saat melanjutkan pembangunan bersama asisten coding, gunakan instruksi berikut:

```text
Bangun satu tahap saja sesuai panduan.
Gunakan Laravel 12, Blade, Tailwind CSS, MySQL, dan Bahasa Indonesia.
Gunakan Iconify atau Lucide, tanpa emoji.
Berikan kode file lengkap dan perintah yang harus dijalankan.
Jangan mengubah route atau logika tahap sebelumnya tanpa menjelaskan alasannya.
Jalankan validasi, migration, dan test yang relevan.
Jangan masukkan token atau password ke source code.
Berhenti setelah tahap selesai agar hasil dapat diuji sebelum lanjut.
```

Tahap pertama yang harus dikerjakan setelah panduan ini adalah **Tahap 1 — Persiapan Laravel 12 dan database**, bukan webhook WhatsApp.
