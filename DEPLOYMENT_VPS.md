# 🏛️ Panduan Resmi Deployment VPS — Portal & Chatbot PST BPS Kabupaten Karanganyar

> **Dokumen Panduan Standar Operasional Prosedur (SOP) Hosting Server Production**  
> *Badan Pusat Statistik (BPS) Kabupaten Karanganyar*  
> *Versi Rilis: 2026.1 (Production Ready)*

---

## 📑 Daftar Isi

- [1. Spesifikasi Server & Kebutuhan Sistem](#1-spesifikasi-server--kebutuhan-sistem)
- [2. Langkah 1: Persiapan & Pengamanan Awal Server Linux](#2-langkah-1-persiapan--pengamanan-awal-server-linux)
- [3. Langkah 2: Instalasi Web Stack (LEMP: Nginx, PHP 8.3, MySQL)](#3-langkah-2-instalasi-web-stack-lemp)
- [4. Langkah 3: Instalasi Composer & Node.js 20 LTS](#4-langkah-3-instalasi-composer--nodejs-20-lts)
- [5. Langkah 4: Pembuatan Database MySQL](#5-langkah-4-pembuatan-database-mysql)
- [6. Langkah 5: Clone Repositori & Hak Akses Direktori](#6-langkah-5-clone-repositori--hak-akses-direktori)
- [7. Langkah 6: Konfigurasi Environment (`.env`) & 9router AI Engine](#7-langkah-6-konfigurasi-environment-env--9router-ai-engine)
- [8. Langkah 7: Kompilasi Aset Frontend & Caching Laravel](#8-langkah-7-kompilasi-aset-frontend--caching-laravel)
- [9. Langkah 8: Konfigurasi Virtual Host Nginx & SSL HTTPS](#9-langkah-8-konfigurasi-virtual-host-nginx--ssl-https)
- [10. Langkah 9: Setup Queue Worker (Supervisor) & Cron Scheduler](#10-langkah-9-setup-queue-worker-supervisor--cron-scheduler)
- [11. 🚀 Fitur Auto-Deploy Tanpa Buka Terminal / CMD](#11--fitur-auto-deploy-tanpa-buka-terminal--cmd)
  - [Opsi A: Deploy Instan via URL Browser (Sangat Mudah)](#opsi-a-deploy-instan-via-url-browser-sangat-mudah)
  - [Opsi B: Auto-Deploy Otomatis via GitHub Webhook (Rekomendasi)](#opsi-b-auto-deploy-otomatis-via-github-webhook-rekomendasi)
  - [Opsi C: CI/CD Pipeline via GitHub Actions](#opsi-c-cicd-pipeline-via-github-actions)
- [12. Skrip Manual Deployment (`deploy.sh`)](#12-skrip-manual-deployment-deploysh)
- [13. Panduan Pemeliharaan & Troubleshooting](#13-panduan-pemeliharaan--troubleshooting)

---

## 1. Spesifikasi Server & Kebutuhan Sistem

Aplikasi dibangun menggunakan arsitektur modern **Laravel 12**, **Tailwind CSS**, **Iconify**, generator **Laporan Rekapitulasi PDF**, dan integrasi **9router AI Gateway (Gemini API)**:

| Komponen | Spesifikasi Minimum | Rekomendasi Production |
| :--- | :--- | :--- |
| **Sistem Operasi** | Ubuntu 22.04 LTS / 24.04 LTS (64-bit) | Ubuntu 24.04 LTS (64-bit) |
| **CPU Core** | 1 vCPU | 2 vCPU atau lebih |
| **RAM (Memori)** | 1 GB (+ 2GB Swap Memory) | 2 GB – 4 GB RAM |
| **Penyimpanan** | 20 GB SSD / NVMe | 40 GB+ NVMe SSD |
| **Web Server** | Nginx 1.18+ | Nginx terbaru |
| **Database** | MySQL 8.0 / MariaDB 10.6+ | MySQL 8.0 |
| **PHP Runtime** | PHP 8.3-FPM | PHP 8.3-FPM (Opcache Aktif) |
| **Node.js** | Node.js 18 LTS | Node.js 20 LTS |

---

## 2. Langkah 1: Persiapan & Pengamanan Awal Server Linux

Hubungkan ke server VPS Anda melalui terminal SSH:
```bash
ssh root@IP_SERVER_ANDA
```

### A. Update dan Upgrade Paket Sistem
```bash
sudo apt update && sudo apt upgrade -y
```

### B. Buat Pengguna Baru dengan Hak Akses Sudo
```bash
# 1. Tambahkan user baru (contoh: bpsadmin)
adduser bpsadmin

# 2. Berikan izin sudo
usermod -aG sudo bpsadmin

# 3. Masuk menggunakan user baru
su - bpsadmin
```

### C. Konfigurasi Firewall Keamanan (UFW)
```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### D. Buat Swap Memory 2GB (Wajib untuk RAM 1GB–2GB)
```bash
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

---

## 3. Langkah 2: Instalasi Web Stack (LEMP)

### A. Instal Nginx Web Server
```bash
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx
```

### B. Instal PHP 8.3 & Ekstensi yang Dibutuhkan
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-common \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd \
    php8.3-intl php8.3-bcmath php8.3-soap php8.3-readline \
    php8.3-sqlite3 php8.3-opcache
```

Optimalkan batas unggah dokumen publikasi & berkas lampiran aduan pada PHP-FPM:
```bash
sudo nano /etc/php/8.3/fpm/php.ini
```
Sesuaikan parameter berikut:
```ini
upload_max_filesize = 15M
post_max_size = 20M
memory_limit = 256M
max_execution_time = 60
```
Simpan (`CTRL+O`, `ENTER`), keluar (`CTRL+X`), lalu restart PHP-FPM:
```bash
sudo systemctl restart php8.3-fpm
```

### C. Instal Database Server MySQL
```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

# Jalankan pengamanan MySQL
sudo mysql_secure_installation
```

---

## 4. Langkah 3: Instalasi Composer & Node.js 20 LTS

### A. Instal Composer (PHP Package Manager)
```bash
cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
composer -V
```

### B. Instal Node.js 20 & NPM (Untuk Kompilasi Vite Assets)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

---

## 5. Langkah 4: Pembuatan Database MySQL

Masuk ke konsol MySQL:
```bash
sudo mysql -u root -p
```

Eksekusi perintah SQL berikut:
```sql
CREATE DATABASE bps_karanganyar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'bps_user'@'localhost' IDENTIFIED BY 'PasswordKuatBPS2026!';

GRANT ALL PRIVILEGES ON bps_karanganyar.* TO 'bps_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

---

## 6. Langkah 5: Clone Repositori & Hak Akses Direktori

### A. Clone Proyek ke Folder `/var/www/`
```bash
sudo mkdir -p /var/www/chatbot-bps
sudo chown -R $USER:$USER /var/www/chatbot-bps

cd /var/www
git clone https://github.com/faizarfi/aichatbotbps.git chatbot-bps
cd /var/www/chatbot-bps
```

### B. Atur Izin Hak Akses File Web Server
```bash
sudo chown -R www-data:www-data /var/www/chatbot-bps
sudo chmod -R 755 /var/www/chatbot-bps
sudo chmod -R 775 /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
sudo usermod -a -G www-data $USER
```

---

## 7. Langkah 6: Konfigurasi Environment (`.env`) & 9router AI Engine

Salin berkas konfigurasi:
```bash
cd /var/www/chatbot-bps
cp .env.example .env
nano .env
```

Isi dan sesuaikan variabel `.env` production:

```ini
APP_NAME="BPS Kabupaten Karanganyar"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://karanganyarkab.bps.go.id

LOG_CHANNEL=daily
LOG_LEVEL=error

# -------------------------------------------------------------
# KONFIGURASI DATABASE PRODUCTION (MySQL)
# -------------------------------------------------------------
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bps_karanganyar
DB_USERNAME=bps_user
DB_PASSWORD=PasswordKuatBPS2026!

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

# -------------------------------------------------------------
# KONFIGURASI AI ENGINE (GOOGLE AI STUDIO GEMINI / 9ROUTER)
# -------------------------------------------------------------
# Pilihan Utama: Google AI Studio (Direct Gemini API — Tanpa perlu buka gateway/CMD)
AI_PROVIDER=gemini
AI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
AI_API_KEY=your_google_ai_studio_api_key_here
AI_MODEL=gemini-3.6-flash
AI_TIMEOUT=45

# Pilihan Alternatif (9router AI Gateway):
# AI_PROVIDER=openai
# AI_BASE_URL=https://api.9router.com/v1
# AI_API_KEY=sk-9router-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
# AI_MODEL=ag/gemini-3-flash


# Token Rahasia untuk Fitur Auto-Deploy via Browser / Webhook
DEPLOY_SECRET_TOKEN=bps-karanganyar-secret-deploy-2026

# -------------------------------------------------------------
# GOOGLE OAUTH 2.0 (SSO LOGIN PETUGAS & PENGUNJUNG)
# Redirect URI: https://karanganyarkab.bps.go.id/auth/google/callback
# -------------------------------------------------------------
GOOGLE_CLIENT_ID=isi-dengan-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=isi-dengan-client-secret
GOOGLE_REDIRECT_URI=https://karanganyarkab.bps.go.id/auth/google/callback

# -------------------------------------------------------------
# PENGIRIMAN EMAIL NOTIFIKASI TIKET PST (OPSIONAL)
# -------------------------------------------------------------
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=pst.bps3313@gmail.com
MAIL_PASSWORD=app-password-gmail-anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="pst3313@bps.go.id"
MAIL_FROM_NAME="Pelayanan Statistik Terpadu BPS Karanganyar"
```

Simpan file (`CTRL+O`, `ENTER`, `CTRL+X`).

---

## 8. Langkah 7: Kompilasi Aset Frontend & Caching Laravel

Jalankan perintah inisialisasi aplikasi production:

```bash
cd /var/www/chatbot-bps

# 1. Install Dependensi PHP Production (Tanpa paket development)
composer install --no-dev --optimize-autoloader

# 2. Generate Application Encryption Key
php artisan key:generate --force

# 3. Buat Symlink Storage Dokumen Publik
php artisan storage:link

# 4. Eksekusi Migrasi Tabel Database & Seeder Data Awal
php artisan migrate --force
php artisan db:seed --force

# 5. Build Aset Frontend Tailwind CSS & Vite
npm install
npm run build

# 6. Kompilasi & Kunci Cache Laravel Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Pastikan kepemilikan folder storage tetap milik `www-data`:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 9. Langkah 8: Konfigurasi Virtual Host Nginx & SSL HTTPS

### A. Buat Server Block Nginx
```bash
sudo nano /etc/nginx/sites-available/chatbot-bps
```

Tempel konfigurasi Nginx berikut (sesuaikan `server_name` dengan domain Anda):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name karanganyarkab.bps.go.id www.karanganyarkab.bps.go.id;
    root /var/www/chatbot-bps/public;

    # Header Keamanan Standar Portal Pemerintah
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    index index.php index.html;
    charset utf-8;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 90;
    }

    # Blokir akses ke file rahasia (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Optimasi Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml application/javascript application/json;
}
```

Aktifkan konfigurasi Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/chatbot-bps /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

### B. Pasang Sertifikat SSL Gratis (Let's Encrypt / Certbot)
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d karanganyarkab.bps.go.id -d www.karanganyarkab.bps.go.id
```
Pilih opsi **Redirect HTTP to HTTPS (Otomatis)**.

---

## 10. Langkah 9: Setup Queue Worker (Supervisor) & Cron Scheduler

### A. Konfigurasi Background Worker (Supervisor)
```bash
sudo apt install supervisor -y
sudo systemctl enable supervisor
sudo systemctl start supervisor

sudo nano /etc/supervisor/conf.d/bps-worker.conf
```

Isi konfigurasi worker:
```ini
[program:bps-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/chatbot-bps/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/chatbot-bps/storage/logs/worker.log
stopwaitsecs=3600
```

Terapkan:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start bps-worker:*
```

### B. Konfigurasi Laravel Cron Job Scheduler
```bash
sudo crontab -u www-data -e
```
Tambahkan baris berikut di paling bawah:
```cron
* * * * * cd /var/www/chatbot-bps && php artisan schedule:run >> /dev/null 2>&1
```

---

## 11. 🚀 Fitur Auto-Deploy Tanpa Buka Terminal / CMD

Untuk mempermudah pembaruan (*update*) aplikasi tanpa perlu repot membuka Terminal SSH / CMD di komputer Anda:

---

### Opsi A: Deploy Instan via URL Browser (Sangat Mudah)
Anda cukup membuka link rahasia di browser laptop atau smartphone Anda kapan saja untuk memicu pembaruan otomatis.

1. **Buat file endpoint deploy di folder publik**:
   ```bash
   sudo nano /var/www/chatbot-bps/public/deploy-webhook.php
   ```

2. **Tempelkan skrip PHP berikut**:
   ```php
   <?php
   // Skrip Auto-Deploy Web BPS Karanganyar
   $secret = 'bps-karanganyar-secret-deploy-2026'; // Samakan dengan DEPLOY_SECRET_TOKEN di .env

   // Validasi Token
   $token = $_GET['token'] ?? $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '';
   if ($token !== $secret) {
       http_response_code(403);
       die(json_encode(['status' => 'error', 'message' => 'Akses Ditolak: Token Tidak Valid']));
   }

   $projectPath = '/var/www/chatbot-bps';
   $log = [];

   $commands = [
       "cd {$projectPath} && git pull origin main 2>&1",
       "cd {$projectPath} && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader 2>&1",
       "cd {$projectPath} && php artisan migrate --force 2>&1",
       "cd {$projectPath} && php artisan optimize:clear 2>&1",
       "cd {$projectPath} && php artisan config:cache 2>&1",
       "cd {$projectPath} && php artisan route:cache 2>&1",
       "cd {$projectPath} && php artisan view:cache 2>&1",
       "cd {$projectPath} && npm run build 2>&1",
       "cd {$projectPath} && sudo supervisorctl restart bps-worker:* 2>&1",
   ];

   foreach ($commands as $cmd) {
       $log[] = ">>> " . $cmd;
       $log[] = shell_exec($cmd);
   }

   header('Content-Type: application/json');
   echo json_encode([
       'status' => 'success',
       'timestamp' => date('Y-m-d H:i:s'),
       'output' => implode("\n", $log)
   ], JSON_PRETTY_PRINT);
   ```

3. **Berikan izin eksekusi perintah sudo untuk `www-data`**:
   ```bash
   sudo visudo
   ```
   Tambahkan baris berikut di baris paling bawah:
   ```sudoers
   www-data ALL=(ALL) NOPASSWD: /usr/bin/supervisorctl restart bps-worker:*
   www-data ALL=(ALL) NOPASSWD: /usr/bin/git pull origin main
   ```

4. **Cara Penggunaan**:  
   Kapan saja Anda ingin mengupdate website, cukup buka URL ini di browser Anda:
   ```text
   https://karanganyarkab.bps.go.id/deploy-webhook.php?token=bps-karanganyar-secret-deploy-2026
   ```
   Halaman browser akan menampilkan riwayat proses update (*git pull, build asset, refresh cache*) secara langsung hingga status sukses!

---

### Opsi B: Auto-Deploy Otomatis via GitHub Webhook (Rekomendasi)
Setiap kali Anda melakukan `git push` dari komputer lokal ke GitHub, server VPS akan otomatis ter-update sendiri tanpa perlu membuka terminal sama sekali.

1. Buka Repositori GitHub Anda → Masuk ke tab **Settings** → **Webhooks** → Klik **Add webhook**.
2. **Payload URL**: `https://karanganyarkab.bps.go.id/deploy-webhook.php?token=bps-karanganyar-secret-deploy-2026`
3. **Content type**: `application/json`
4. **Events**: Pilih `Just the push event`.
5. Klik **Add webhook**.
6. *Selesai!* Sekarang setiap kali Anda `git push`, VPS otomatis terdeploy dalam hitungan detik.

---

### Opsi C: CI/CD Pipeline via GitHub Actions
Jika Anda ingin menggunakan alur kerja GitHub Actions, buat berkas `.github/workflows/deploy.yml` di repositori Anda:

```yaml
name: Deploy BPS Karanganyar to VPS

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Trigger Webhook Deployment
        run: |
          curl -X POST "https://karanganyarkab.bps.go.id/deploy-webhook.php?token=bps-karanganyar-secret-deploy-2026"
```

---

## 12. Skrip Manual Deployment (`deploy.sh`)

Jika sewaktu-waktu Anda ingin menjalankan update langsung di terminal VPS:

```bash
cd /var/www/chatbot-bps
nano deploy.sh
```

Isi skrip:
```bash
#!/bin/bash
set -e

echo "🚀 Memulai proses pembaruan Portal BPS Karanganyar..."

php artisan down --message="Sistem sedang diperbarui. Mohon tunggu beberapa saat." --retry=60 || true

git pull origin main
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan migrate --force

php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

npm install --no-audit
npm run build

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo supervisorctl restart bps-worker:*

php artisan up

echo "✅ Pembaruan Berhasil! Portal BPS aktif kembali."
```

Beri izin eksekusi:
```bash
chmod +x deploy.sh
```
Jalankan perintah ini kapan saja:
```bash
./deploy.sh
```

---

## 13. Panduan Pemeliharaan & Troubleshooting

### A. Memantau Log Error Laravel
```bash
tail -n 100 -f /var/www/chatbot-bps/storage/logs/laravel.log
```

### B. Reset Total Cache Laravel Production
```bash
cd /var/www/chatbot-bps
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### C. Memperbaiki Izin Folder Storage yang Terkunci
```bash
sudo chown -R www-data:www-data /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
sudo chmod -R 775 /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
```

### D. Backup Database MySQL Cepat
```bash
mysqldump -u bps_user -p bps_karanganyar > backup_bps_$(date +%Y%m%d_%H%M%S).sql
```

### E. Memeriksa Status Antrean Worker
```bash
sudo supervisorctl status
```

---

## 🏛️ Penutup

Dengan mengikuti panduan di atas, sistem **Portal Pelayanan Statistik Terpadu (PST) & Chatbot AI BPS Kabupaten Karanganyar** telah terpasang dengan konfigurasi aman, cepat, handal, dan mendukung otomasi deployment penuh tanpa memerlukan akses terminal berulang kali! 🚀
