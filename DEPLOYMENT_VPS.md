# 🚀 Panduan Lengkap Deployment Portal & Chatbot BPS ke VPS Linux (Ubuntu / Debian)

Dokumentasi ini memandu Anda melakukan proses hosting dan konfigurasi server **Virtual Private Server (VPS)** dari nol (*from scratch*) hingga aplikasi **Portal Layanan BPS Kabupaten Karanganyar & Chatbot AI** berjalan live dengan performa tinggi, aman, dan ber-SSL resmi.

---

## 📋 Daftar Isi

1. [Rekomendasi Spesifikasi VPS](#1-rekomendasi-spesifikasi-vps)
2. [Persiapan Awal & Keamanan Server](#2-persiapan-awal--keamanan-server)
3. [Instalasi Web Stack (LEMP: Linux, Nginx, MySQL, PHP 8.3)](#3-instalasi-web-stack-lemp)
4. [Instalasi Composer & Node.js](#4-instalasi-composer--nodejs)
5. [Konfigurasi Database MySQL](#5-konfigurasi-database-mysql)
6. [Clone Proyek & Pengaturan Izin Direktori](#6-clone-proyek--pengaturan-izin-direktori)
7. [Konfigurasi Environment (.env) Production](#7-konfigurasi-environment-env-production)
8. [Build Aset & Optimasi Caching Laravel](#8-build-aset--optimasi-caching-laravel)
9. [Konfigurasi Virtual Host Nginx](#9-konfigurasi-virtual-host-nginx)
10. [Pemasangan SSL Gratis (Let's Encrypt / Certbot)](#10-pemasangan-ssl-gratis-lets-encrypt--certbot)
11. [Setup Background Worker (Supervisor)](#11-setup-background-worker-supervisor)
12. [Setup Otomatisasi Jadwal (Cron Job Laravel Scheduler)](#12-setup-otomatisasi-jadwal-cron-job-laravel-scheduler)
13. [Skrip Update / Deployment Otomatis (deploy.sh)](#13-skrip-update--deployment-otomatis-deploysh)
14. [Troubleshooting & Perintah Pemeliharaan](#14-troubleshooting--perintah-pemeliharaan)

---

## 1. Rekomendasi Spesifikasi VPS

Untuk menjalankan Laravel 12 dengan AI Chatbot, PDF generator, dan antrean background:

| Komponen | Spesifikasi Minimum | Spesifikasi Rekomendasi |
| :--- | :--- | :--- |
| **Sistem Operasi** | Ubuntu 22.04 LTS / 24.04 LTS (64-bit) | Ubuntu 24.04 LTS (64-bit) |
| **CPU** | 1 vCPU | 2 vCPU atau lebih |
| **RAM** | 1 GB (+ 2GB Swap) | 2 GB – 4 GB RAM |
| **Storage (Disk)** | 20 GB SSD / NVMe | 40 GB+ NVMe SSD |
| **Penyedia VPS** | Niagahoster, DomaiNesia, IDCloudHost, DigitalOcean, Hetzner, AWS EC2, Linode |

---

## 2. Persiapan Awal & Keamanan Server

Masuk ke VPS Anda via terminal / SSH:
```bash
ssh root@IP_SERVER_ANDA
```

### A. Update Paket Sistem
```bash
sudo apt update && sudo apt upgrade -y
```

### B. Buat User Non-Root (Rekomendasi Keamanan)
```bash
# Buat user baru, misalnya 'bpsadmin'
adduser bpsadmin

# Berikan hak sudo
usermod -aG sudo bpsadmin

# Beralih ke user baru
su - bpsadmin
```

### C. Konfigurasi Firewall (UFW)
```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### D. Buat Swap File 2GB (Sangat penting jika RAM VPS 1GB - 2GB)
```bash
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

---

## 3. Instalasi Web Stack (LEMP)

### A. Instal Nginx Web Server
```bash
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx
```

### B. Instal PHP 8.3 & Ekstensi Lengkap
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-common \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd \
    php8.3-intl php8.3-bcmath php8.3-soap php8.3-readline \
    php8.3-sqlite3 php8.3-opcache
```

Verifikasi instalasi PHP:
```bash
php -v
```

Optimalkan konfigurasi PHP untuk unggah dokumen PST (PDF/Proposal):
```bash
sudo nano /etc/php/8.3/fpm/php.ini
```
Ubah baris berikut:
```ini
upload_max_filesize = 10M
post_max_size = 12M
memory_limit = 256M
max_execution_time = 60
```
Restart service PHP-FPM:
```bash
sudo systemctl restart php8.3-fpm
```

### C. Instal Database Server (MySQL / MariaDB)
```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

# Amankan instalasi MySQL
sudo mysql_secure_installation
```

---

## 4. Instalasi Composer & Node.js

### A. Instal Composer (Manajer Paket PHP)
```bash
cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
composer -V
```

### B. Instal Node.js 20 LTS & NPM (Untuk Build Aset Vite)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

---

## 5. Konfigurasi Database MySQL

Masuk ke console MySQL:
```bash
sudo mysql -u root -p
```

Jalankan perintah SQL berikut (ganti `PasswordKuat123!` dengan password aman Anda):
```sql
CREATE DATABASE bps_karanganyar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'bps_user'@'localhost' IDENTIFIED BY 'PasswordKuat123!';

GRANT ALL PRIVILEGES ON bps_karanganyar.* TO 'bps_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

---

## 6. Clone Proyek & Pengaturan Izin Direktori

### A. Clone Repositori ke `/var/www/`
```bash
sudo mkdir -p /var/www/chatbot-bps
sudo chown -R $USER:$USER /var/www/chatbot-bps

cd /var/www
git clone https://github.com/faizarfi/aichatbotbps.git chatbot-bps
cd /var/www/chatbot-bps
```

### B. Atur Hak Kepemilikan & Izin Folder
Web server Nginx (`www-data`) memerlukan izin tulis (*write permission*) pada folder `storage` dan `bootstrap/cache`:
```bash
sudo chown -R www-data:www-data /var/www/chatbot-bps
sudo chmod -R 755 /var/www/chatbot-bps
sudo chmod -R 775 /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
```

Tambahkan user Anda ke grup `www-data` agar bisa mengedit file tanpa hambatan:
```bash
sudo usermod -a -G www-data $USER
```

---

## 7. Konfigurasi Environment (.env) Production

Salin berkas `.env.example` ke `.env`:
```bash
cd /var/www/chatbot-bps
cp .env.example .env
nano .env
```

Sesuaikan nilai-nilai berikut di `.env`:

```ini
APP_NAME="BPS Kabupaten Karanganyar"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://chatbot.domainanda.bps.go.id

LOG_CHANNEL=daily
LOG_LEVEL=error

# Konfigurasi Database Production
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bps_karanganyar
DB_USERNAME=bps_user
DB_PASSWORD=PasswordKuat123!

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

# Konfigurasi Google OAuth 2.0 (SSO)
# Daftarkan Redirect URI di Google Cloud Console: https://chatbot.domainanda.bps.go.id/auth/google/callback
GOOGLE_CLIENT_ID=isi-dengan-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=isi-dengan-google-client-secret
GOOGLE_REDIRECT_URI=https://chatbot.domainanda.bps.go.id/auth/google/callback

# Konfigurasi AI Engine Chatbot (9router / OpenRouter / Gemini API)
# 9router gateway kompatibel OpenAI API
AI_BASE_URL=https://api.9router.com/v1
AI_API_KEY=sk-9router-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
AI_MODEL=ag/gemini-3-flash
AI_TIMEOUT=45

# Konfigurasi Pengiriman Email Notifikasi (Opsional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailresmi@bps.go.id
MAIL_PASSWORD=app-password-email-anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@bpskaranganyar.id"
MAIL_FROM_NAME="BPS Kabupaten Karanganyar"
```

Simpan file dengan menekan `CTRL + O`, `ENTER`, lalu keluar dengan `CTRL + X`.

---

## 8. Build Aset & Optimasi Caching Laravel

Jalankan perintah-perintah instalasi dan optimasi production:

```bash
cd /var/www/chatbot-bps

# 1. Install dependensi PHP production (tanpa dev package)
composer install --no-dev --optimize-autoloader

# 2. Generate Application Key
php artisan key:generate --force

# 3. Buat symlink storage publik (untuk unduh berkas lampiran & proposal)
php artisan storage:link

# 4. Jalankan migrasi tabel database & seeder
php artisan migrate --force
php artisan db:seed --force

# 5. Install dependensi Node.js & kompilasi aset frontend Tailwind Vite
npm install
npm run build

# 6. Bersihkan dan kompilasi Cache Laravel Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Pastikan kepemilikan file tetap dipegang oleh `www-data`:
```bash
sudo chown -R www-data:www-data /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
```

---

## 9. Konfigurasi Virtual Host Nginx

Buat file konfigurasi server block Nginx baru:
```bash
sudo nano /etc/nginx/sites-available/chatbot-bps
```

Tempelkan (*paste*) konfigurasi Nginx berikut (ganti `chatbot.domainanda.bps.go.id` dengan domain/subdomain Anda):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name chatbot.domainanda.bps.go.id;
    root /var/www/chatbot-bps/public;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    index index.php index.html index.htm;
    charset utf-8;

    # Ukuran maksimum unggah file lampiran data mikro/aduan
    client_max_body_size 12M;

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
        fastcgi_read_timeout 60;
    }

    # Blokir akses ke file tersembunyi (misal .env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Kompresi Gzip untuk Kecepatan Loading
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml application/javascript application/json;
    gzip_disable "MSIE [1-6]\.";
}
```

Aktifkan konfigurasi Nginx & uji sintaks:
```bash
# Aktifkan site symlink
sudo ln -s /etc/nginx/sites-available/chatbot-bps /etc/nginx/sites-enabled/

# Hapus default site bawaan Nginx jika tidak dipakai
sudo rm -f /etc/nginx/sites-enabled/default

# Tes sintaks konfigurasi Nginx
sudo nginx -t
```
Jika hasilnya `syntax is ok` dan `test is successful`, reload Nginx:
```bash
sudo systemctl reload nginx
```

---

## 10. Pemasangan SSL Gratis (Let's Encrypt / Certbot)

Pastikan DNS domain Anda (A Record) sudah diarahkan ke IP Publik VPS Anda.

Instal Certbot:
```bash
sudo apt install certbot python3-certbot-nginx -y
```

Jalankan Certbot untuk menerbitkan sertifikat SSL otomatis:
```bash
sudo certbot --nginx -d chatbot.domainanda.bps.go.id
```
- Masukkan alamat email admin Anda.
- Ketik `Y` untuk menyetujui ToS.
- Pilih opsi **Redirect HTTP to HTTPS (Otomatis)**.

Sertifikat SSL Let's Encrypt akan terpasang dan diperbarui secara otomatis sebelum kedaluwarsa.

---

## 11. Setup Background Worker (Supervisor)

Aplikasi menggunakan antrean (*queue*) untuk memproses pengiriman notifikasi, email, dan integrasi AI asinkron agar tidak membebani web server.

Instal Supervisor:
```bash
sudo apt install supervisor -y
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

Buat file konfigurasi worker Laravel:
```bash
sudo nano /etc/supervisor/conf.d/bps-worker.conf
```

Isi dengan konfigurasi:
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

Aktifkan worker di Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start bps-worker:*
```

Periksa status worker:
```bash
sudo supervisorctl status
```

---

## 12. Setup Otomatisasi Jadwal (Cron Job Laravel Scheduler)

Laravel Scheduler bertugas menjalankan tugas berkala (seperti pembersihan token kadaluarsa, rekap data harian, dll).

Buka editor cron job user `www-data`:
```bash
sudo crontab -u www-data -e
```
Pilih editor (misal `1` untuk Nano), lalu tambahkan baris berikut di bagian paling bawah:
```cron
* * * * * cd /var/www/chatbot-bps && php artisan schedule:run >> /dev/null 2>&1
```
Simpan dan keluar.

---

## 13. Skrip Update / Deployment Otomatis (deploy.sh)

Untuk mempermudah pembaruan (*update*) kode dari repositori Git di masa mendatang tanpa perlu mengetik banyak perintah manual, buat skrip deployment:

```bash
cd /var/www/chatbot-bps
nano deploy.sh
```

Tempelkan skrip berikut:

```bash
#!/bin/bash
set -e

echo "🚀 Memulai proses deployment BPS Karanganyar..."

# Masuk ke mode maintenance
php artisan down --message="Sistem sedang diperbarui. Mohon tunggu beberapa saat." --retry=60 || true

# Tarik update terbaru dari Git
git pull origin main

# Instal dependensi Composer (hanya production)
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Jalankan migrasi database
php artisan migrate --force

# Bersihkan dan refresh cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Build aset frontend
npm install --no-audit
npm run build

# Atur ulang izin direktori
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Restart queue worker Supervisor
sudo supervisorctl restart bps-worker:*

# Matikan mode maintenance (Live kembali)
php artisan up

echo "✅ Deployment Sukses! Website siap digunakan."
```

Beri hak eksekusi pada skrip:
```bash
chmod +x deploy.sh
```

Setiap kali Anda ingin memperbarui website setelah melakukan `git push`, di server VPS Anda cukup menjalankan:
```bash
./deploy.sh
```

---

## 14. Troubleshooting & Perintah Pemeliharaan

### A. Melihat Log Error Laravel
```bash
tail -n 100 -f /var/www/chatbot-bps/storage/logs/laravel.log
```

### B. Melihat Log Error Nginx
```bash
sudo tail -n 100 -f /var/log/nginx/error.log
```

### C. Reset Cache Jika Terjadi Perubahan Konfigurasi
```bash
cd /var/www/chatbot-bps
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### D. Memperbaiki Izin Folder Storage yang Terkunci
```bash
sudo chown -R www-data:www-data /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
sudo chmod -R 775 /var/www/chatbot-bps/storage /var/www/chatbot-bps/bootstrap/cache
```

### E. Backup Database MySQL Cepat
```bash
mysqldump -u bps_user -p bps_karanganyar > backup_bps_$(date +%Y%m%d_%H%M%S).sql
```

---

## 🎯 Selamat!

Website **Portal Layanan Statistik Terpadu & Chatbot AI BPS Kabupaten Karanganyar** Anda kini telah sukses dihosting di VPS dengan konfigurasi aman, cepat, dan siap melayani masyarakat! 🚀
