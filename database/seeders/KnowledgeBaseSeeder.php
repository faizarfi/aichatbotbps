<?php

namespace Database\Seeders;

use App\Models\KnowledgeCategory;
use App\Models\KnowledgeArticle;
use App\Models\User;
use Illuminate\Database\Seeder;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::create([
            'name' => 'Administrator',
            'email' => 'admin@bps-karanganyar.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $categories = [
            [
                'name' => 'Pelayanan Statistik Terpadu (PST)',
                'slug' => 'pst-dan-layanan-data',
                'description' => 'Standar pelayanan resmi, jenis layanan, prosedur permohonan data, konsultasi, data mikro, wilkerstat, dan tarif PNBP di BPS Kabupaten Karanganyar.',
                'articles' => [
                    [
                        'title' => 'Standar dan 7 Layanan Utama Pelayanan Statistik Terpadu (PST) BPS Karanganyar',
                        'question' => 'Apa itu Pelayanan Statistik Terpadu (PST) BPS Karanganyar dan apa saja 7 jenis layanan utamanya?',
                        'answer' => "Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar adalah pintu gerbang resmi pelayanan publik satu atap untuk memperoleh data statistik, publikasi, dan konsultasi statistik yang transparan, profesional, dan akuntabel.\n\nBerdasarkan Standar Pelayanan BPS (Keputusan Kepala BPS No. 444 Tahun 2022), terdapat 7 Layanan Utama PST:\n1. Layanan Perpustakaan Cetak & Digital: Akses membaca buku publikasi fisik di ruang PST atau mengunduh softcopy PDF/Excel di portal resmi secara gratis.\n2. Layanan Konsultasi Statistik: Bimbingan tatap muka atau daring mengenai konsep, definisi indikator, metodologi survei/sensus, interpretasi angka, dan penjelasan metadata statistik.\n3. Layanan Rekomendasi Kegiatan Statistik (ROMANTIK): Pelayanan telaah metodologi bagi Organisasi Perangkat Daerah (OPD) Pemkab Karanganyar yang akan menyelenggarakan survei/pendataan sektoral sesuai amanat Satu Data Indonesia (SDI).\n4. Layanan Penyediaan Data Mikro (Microdata) & Peta Spasial Wilkerstat: Akses raw data hasil survei (Susenas, Sakernas, Sensus) dan shapefile peta batas wilayah kerja statistik untuk analisis riset mendalam.\n5. Layanan Pembinaan Statistik Sektoral (EPSS & Desa Cantik): Evaluasi Penyelenggaraan Statistik Sektoral bagi pemda serta pembinaan literasi data bagi perangkat desa/kelurahan.\n6. Layanan Pojok Statistik: Kolaborasi dengan perguruan tinggi untuk mendekatkan edukasi data kepada sivitas akademika.\n7. Layanan Penanganan Pengaduan: Fasilitas penyampaian aduan, kritik, dan saran pelayanan melalui loket PST maupun formulir daring portal ini.",
                        'keywords' => ['pst', 'pelayanan statistik terpadu', 'standar pelayanan', '7 layanan', 'layanan bps', 'fungsi pst', 'produk pst', 'maklumat pelayanan'],
                        'source_title' => 'Standar Pelayanan BPS Kabupaten Karanganyar (Kepka BPS No. 444/2022)',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Prosedur Permohonan Data Mikro dan Peta Wilkerstat (Tarif Rp0 untuk Mahasiswa/Peneliti)',
                        'question' => 'Bagaimana syarat dan cara mendapatkan data mikro (raw data) atau peta digital Wilkerstat untuk skripsi/penelitian?',
                        'answer' => "Data mikro (data mentah perorangan/rumah tangga yang telah dianonimkan) dan peta digital Wilkerstat (format Shapefile/GIS) dapat diperoleh melalui loket PST BPS Kabupaten Karanganyar atau Pelayanan Statistik Terpadu Online.\n\nKetentuan Tarif Rp0,- (Bebas Biaya PNBP):\nSesuai Peraturan Pemerintah (PP) No. 86 Tahun 2021 tentang Tarif atas Jenis PNBP yang Berlaku pada BPS, mahasiswa yang menyusun skripsi/tesis/disertasi, dosen peneliti, serta instansi pemerintah berhak mendapatkan tarif Rp0,- (GRATIS).\n\nPersyaratan Pengajuan Data Mikro & Wilkerstat:\n1. Mengisi formulir permohonan data di loket PST atau daring.\n2. Melampirkan Surat Pengantar resmi dari Dekan Fakultas/Kampus atau instansi pemohon.\n3. Melampirkan Proposal Penelitian/Skripsi yang memuat rincian variabel dan cakupan data yang dibutuhkan.\n4. Fotokopi Kartu Tanda Penduduk (KTP) dan Kartu Tanda Mahasiswa (KTM).\n5. Menandatangani Formulir Komitmen Penggunaan Data (hanya untuk riset akademis dan tidak disebarluaskan komersial).\n\nPetugas PST BPS Karanganyar akan memverifikasi berkas dalam waktu 1-3 hari kerja sebelum berkas data mikro diserahkan.",
                        'keywords' => ['data mikro', 'raw data', 'wilkerstat', 'shapefile', 'shp', 'skripsi', 'tesis', 'mahasiswa', 'gratis', 'rp 0', 'bebas pnbp', 'pp 86 tahun 2021', 'penelitian'],
                        'source_title' => 'Standar Pelayanan Data Mikro & Wilkerstat BPS (PP No. 86 Tahun 2021)',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Prosedur Layanan Konsultasi Statistik Resmi BPS',
                        'question' => 'Bagaimana alur dan prosedur konsultasi statistik di BPS Kabupaten Karanganyar?',
                        'answer' => "Layanan Konsultasi Statistik BPS Kabupaten Karanganyar terbuka untuk seluruh masyarakat, peneliti, mahasiswa, ASN OPD, maupun pelaku usaha tanpa dipungut biaya (100% GRATIS).\n\nCakupan Materi Konsultasi:\n1. Konsultasi Konsep dan Definisi: Memahami batasan operasional istilah statistik resmi (seperti Garis Kemiskinan, Angkatan Kerja, Pengangguran, PDRB, IPM).\n2. Konsultasi Metodologi Survei: Penentuan populasi, kerangka sampel, teknik sampling probabilitas, rancangan instrumen/kuesioner, dan margin of error.\n3. Konsultasi Analisis dan Interpretasi: Membaca tabel komparasi, tren deret waktu, dan pemanfaatan data untuk evaluasi program pembangunan.\n4. Konsultasi Metadata Statistik: Standarisasi variabel dan indikator sesuai prinsip Satu Data Indonesia.\n\nSaluran Konsultasi:\n- Daring/Interaktif: Melalui Asisten AI Chatbot ini dan fitur Hubungi Petugas untuk konsultasi tertulis real-time.\n- Tatap Muka: Datang langsung ke Ruang PST Kantor BPS Kabupaten Karanganyar pada hari kerja resmi (Senin-Jumat).",
                        'keywords' => ['konsultasi statistik', 'konsultasi', 'bimbingan statistik', 'tanya data', 'metodologi', 'sampling', 'definisi indikator', 'analisis data'],
                        'source_title' => 'Standar Konsultasi Statistik BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Layanan Rekomendasi Kegiatan Statistik Sektoral (ROMANTIK)',
                        'question' => 'Apa itu layanan ROMANTIK BPS dan bagaimana tahapan pengajuannya bagi OPD Pemkab Karanganyar?',
                        'answer' => "ROMANTIK (Rekomendasi Kegiatan Statistik) adalah layanan resmi BPS sebagai Pembina Data Statistik sesuai amanat UU No. 16 Tahun 1997 dan Perpres No. 39 Tahun 2019 tentang Satu Data Indonesia (SDI).\n\nTujuan ROMANTIK:\n1. Menghindari duplikasi pengumpulan data statistik sektoral antar-OPD di lingkungan Pemerintah Kabupaten Karanganyar.\n2. Menjamin rancangan metodologi, instrumen survei, dan teknik sampling memenuhi kaidah statistik yang baku.\n3. Menghasilkan metadata statistik kegiatan dan indikator yang terstandarisasi secara nasional.\n\nTahapan Pengajuan ROMANTIK:\n1. OPD pemohon menyiapkan rancangan kegiatan statistik (proposal, kuesioner, jadwal, dan metodologi).\n2. Tim Pembina Statistik BPS Kabupaten Karanganyar melakukan telaah kelayakan metodologi, konsep definisi, dan klasifikasi data.\n3. Jika rancangan memenuhi standar, Kepala BPS Kabupaten Karanganyar menerbitkan Surat Rekomendasi Kegiatan Statistik resmi.\n4. OPD melaksanakan survei dan menyerahkan hasil metadata ke portal Satu Data.",
                        'keywords' => ['romantik', 'rekomendasi statistik', 'survei opd', 'statistik sektoral', 'satu data indonesia', 'sdi', 'pembina data', 'telaah metodologi'],
                        'source_title' => 'Pedoman Penyelenggaraan Rekomendasi Kegiatan Statistik (ROMANTIK) BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Evaluasi Penyelenggaraan Statistik Sektoral (EPSS) dan Indeks Pembangunan Statistik (IPS)',
                        'question' => 'Apa itu EPSS dan Indeks Pembangunan Statistik (IPS) Kabupaten Karanganyar?',
                        'answer' => "Evaluasi Penyelenggaraan Statistik Sektoral (EPSS) adalah proses penilaian sistematis oleh BPS untuk mengukur tingkat kematangan (maturity level) penyelenggaraan statistik sektoral pada instansi pemerintah daerah.\n\nOutput EPSS:\nEPSS menghasilkan nilai Indeks Pembangunan Statistik (IPS) dengan skala 1 hingga 5. Nilai IPS Kabupaten Karanganyar menjadi salah satu indikator kinerja reformasi birokrasi tematik pemerintah daerah.\n\n5 Domain Penilaian EPSS:\n1. Prinsip Satu Data Indonesia (Standar Data, Metadata, Interoperabilitas, Kode Referensi/Data Induk).\n2. Kualitas Data (Relevansi, Akurasi, Ketepatan Waktu, Aksesibilitas, Keterbandingan, Keterpaduan).\n3. Proses Bisnis Statistik (Perencanaan, Pengumpulan, Pemeriksaan, Diseminasi data sesuai GSBPM).\n4. Kelembagaan Statistik (Penyelenggara, Pembina, Walidata, dan Forum Satu Data).\n5. Statistik Nasional (Pemanfaatan data statistik untuk perencanaan pembangunan daerah).",
                        'keywords' => ['epss', 'ips', 'indeks pembangunan statistik', 'statistik sektoral', 'evaluasi', 'kematangan data', 'domain epss', 'gsbpm'],
                        'source_title' => 'Pedoman Evaluasi Penyelenggaraan Statistik Sektoral (EPSS) BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Program Desa Cantik (Desa Cinta Statistik) di Kabupaten Karanganyar',
                        'question' => 'Apa itu Program Desa Cantik (Desa Cinta Statistik) di Kabupaten Karanganyar?',
                        'answer' => "Program Desa Cinta Statistik (Desa Cantik) merupakan program inovasi BPS untuk melakukan pembinaan statistik secara berkelanjutan kepada aparatur pemerintah desa dan kelurahan di Kabupaten Karanganyar.\n\nTujuan Utama Desa Cantik:\n1. Meningkatkan literasi, kesadaran, dan kapabilitas aparat desa dalam mengelola data potensi, demografi, dan kesejahteraan warganya.\n2. Menstandarisasi tata kelola data desa agar valid, akurat, dan mutakhir sehingga program bantuan sosial atau pembangunan desa tepat sasaran.\n3. Mendorong terwujudnya sistem informasi profil desa berbasis data presisi (monografi desa digital).\n\nDi Kabupaten Karanganyar, desa-desa binaan Desa Cantik telah mampu mempublikasikan infografis dan buku profil data desa secara mandiri.",
                        'keywords' => ['desa cantik', 'desa cinta statistik', 'kelurahan cantik', 'pembinaan desa', 'data desa', 'monografi', 'literasi statistik'],
                        'source_title' => 'Program Desa Cantik BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Tarif dan Biaya Layanan Data Statistik BPS (PP No. 86 Tahun 2021)',
                        'question' => 'Berapa biaya layanan data dan konsultasi di BPS Kabupaten Karanganyar?',
                        'answer' => "Layanan data BPS Kabupaten Karanganyar mengedepankan prinsip keterbukaan informasi publik dan berorientasi pada kepuasan masyarakat:\n\n1. Layanan GRATIS (Rp 0,-):\n- Unduh seluruh publikasi elektronik (softcopy PDF, buku KDA, dll): Rp0,- (Gratis).\n- Akses tabel statistik dinamis, infografis, dan Berita Resmi Statistik (BRS): Rp0,- (Gratis).\n- Konsultasi statistik di loket PST maupun online via Chatbot/Petugas: Rp0,- (Gratis).\n- Permohonan Data Mikro & Peta Wilkerstat untuk skripsi/tesis/penelitian akademis: Rp0,- (Gratis, berdasar PP 86/2021 Pasal 3).\n\n2. Layanan Dikenakan Tarif PNBP Resmi (PP No. 86 Tahun 2021):\n- Pembelian buku fisik/cetak publikasi resmi edisi khusus (dikenai tarif cetak resmi).\n- Seluruh penerimaan tarif PNBP disetorkan langsung 100% ke Kas Negara melalui kode billing SIMPONI tanpa pungutan liar.",
                        'keywords' => ['biaya', 'tarif', 'harga', 'gratis', 'pnbp', 'pp 86 tahun 2021', 'bayar', 'retribusi', 'bebas biaya', 'rp0', 'rp 0'],
                        'source_title' => 'PP No. 86 Tahun 2021 tentang Tarif atas Jenis PNBP BPS',
                        'source_url' => 'https://pst.bps.go.id/layanan/pembelian',
                    ],
                    [
                        'title' => 'Layanan Developer WebAPI BPS (Integrasi Data Statistik JSON)',
                        'question' => 'Bagaimana cara menggunakan WebAPI BPS untuk mengambil data statistik secara terprogram?',
                        'answer' => "WebAPI BPS (https://webapi.bps.go.id/developer/) adalah layanan resmi bagi developer, akademisi, dan institusi untuk mengintegrasikan data statistik BPS ke dalam aplikasi atau dashboard secara otomatis melalui REST API.\n\nFitur & Kapabilitas WebAPI BPS:\n1. Menyediakan endpoint JSON untuk Indikator Strategis, Subjek Statistik, Tabel Dinamis, dan Publikasi.\n2. Otentikasi mudah menggunakan Application ID (App ID / API Key) yang didapatkan secara gratis setelah mendaftar di portal developer.\n3. Memudahkan pembuatan sistem integrasi Satu Data Indonesia (SDI) pada portal daerah dan aplikasi pihak ketiga.\n\nCara Akses:\nKunjungi portal https://webapi.bps.go.id/developer/, buat akun, daftarkan nama aplikasi Anda, dan dapatkan API Key untuk mulai memanggil data.",
                        'keywords' => ['webapi', 'api bps', 'rest api', 'developer', 'json', 'integrasi data', 'webapi.bps.go.id', 'app id', 'api key'],
                        'source_title' => 'WebAPI BPS Developer Portal',
                        'source_url' => 'https://webapi.bps.go.id/developer/',
                    ],
                    [
                        'title' => 'StatInaLab (Statistics Indonesia Data Lab) untuk Analisis Data Mikro Lanjut',
                        'question' => 'Apa itu StatInaLab BPS dan bagaimana cara mengaksesnya untuk riset data mikro?',
                        'answer' => "StatInaLab (Statistics Indonesia Data Lab - https://statinalab.bps.go.id/) adalah fasilitas komputasi penelitian berkeamanan tinggi yang disediakan BPS untuk memenuhi kebutuhan peneliti, akademisi, dan analis kebijakan dalam mengolah data mikro yang sangat mendalam atau sensitif secara on-site dan real-time.\n\nKeunggulan StatInaLab:\n1. Memungkinkan pemrosesan data mikro secara utuh dalam lingkungan komputasi terkendali (secure data enclave) tanpa melanggar prinsip kerahasiaan data individu/responden sesuai UU No. 16 Tahun 1997.\n2. Peneliti dapat menjalankan algoritma statistik atau model ekonometrika lanjutan dengan spesifikasi komputasi tinggi.\n3. Hanya hasil analisis agregat/estimasi model yang telah diperiksa petugas yang dapat dibawa keluar oleh peneliti.",
                        'keywords' => ['statinalab', 'data lab', 'data mikro lanjut', 'enclave', 'komputasi mikro', 'statinalab.bps.go.id', 'penelitian lanjut'],
                        'source_title' => 'StatInaLab BPS RI',
                        'source_url' => 'https://statinalab.bps.go.id/',
                    ],
                    [
                        'title' => 'Transdata: Sistem Informasi Pertukaran Data Resmi Antar-Instansi Pemerintah',
                        'question' => 'Apa itu layanan Transdata BPS?',
                        'answer' => "Transdata (https://pst.bps.go.id/layanan/transdata) adalah sistem informasi terpadu yang melayani pertukaran data statistik elektronik antara BPS dengan Kementerian/Lembaga pemerintah.\n\nKetentuan Akses Transdata:\nLayanan ini dikhususkan bagi instansi pemerintah yang telah memiliki Perjanjian Kerja Sama (PKS) atau Nota Kesepahaman (MoU) pertukaran data resmi dengan BPS RI, guna mendukung interoperabilitas data nasional dalam kerangka Satu Data Indonesia (SDI).",
                        'keywords' => ['transdata', 'pertukaran data', 'pks data', 'kementerian lembaga', 'interoperabilitas', 'pst transdata'],
                        'source_title' => 'Layanan Transdata BPS RI',
                        'source_url' => 'https://pst.bps.go.id/layanan/transdata',
                    ],
                    [
                        'title' => 'Maklumat Pelayanan dan Budaya Kerja BerAKHLAK BPS Karanganyar',
                        'question' => 'Apa isi Maklumat Pelayanan dan nilai-nilai budaya kerja BPS Kabupaten Karanganyar?',
                        'answer' => "BPS Kabupaten Karanganyar berkomitmen memberikan pelayanan prima yang berintegritas tinggi:\n\nMaklumat Pelayanan BPS Karanganyar:\n'Dengan ini, kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai ketentuan peraturan perundang-undangan yang berlaku.'\n\nBudaya Kerja & Core Values BPS:\n1. BerAKHLAK (Core Values ASN):\n- Berorientasi Pelayanan: Memahami dan memenuhi kebutuhan data masyarakat dengan ramah dan solutif.\n- Akuntabel: Bertanggung jawab atas akurasi dan objektivitas data.\n- Kompeten: Terus meningkatkan keahlian metodologi statistik.\n- Harmonis: Menjaga iklim kemitraan dengan seluruh pemangku kepentingan.\n- Loyal: Berdedikasi penuh untuk bangsa dan negara.\n- Adaptif: Cepat berinovasi melalui teknologi digital dan kecerdasan artifisial.\n- Kolaboratif: Membangun sinergi Satu Data dengan instansi pemda dan akademisi.\n2. Nilai Inti Statistik (PIA): Profesional, Integritas, dan Amanah.",
                        'keywords' => ['maklumat pelayanan', 'budaya kerja', 'berakhlak', 'core values', 'pia', 'janji layanan', 'integritas bps'],
                        'source_title' => 'Maklumat Pelayanan BPS Kabupaten Karanganyar',
                        'source_url' => 'https://pst.bps.go.id',
                    ],
                    [
                        'title' => 'Fitur Tabel Dinamis / Query Builder BPS (Kustomisasi Data Statistik)',
                        'question' => 'Apa itu Tabel Dinamis / Query Builder di website BPS Karanganyar dan bagaimana cara menggunakannya?',
                        'answer' => "Tabel Dinamis / Query Builder BPS (tersedia di menu Tabel Statistik /statistics-table) adalah fitur interaktif resmi yang memudahkan pengguna mengkustomisasi tabel data statistik sesuai kebutuhan spesifik.\n\nLangkah Membuat Tabel Dinamis BPS:\n1. Pilih Kategori Subjek & Subjek Statistik (Sosial & Kependudukan, Ekonomi & Perdagangan, atau Pertanian & Pertambangan).\n2. Pilih Judul Indikator / Tabel yang ingin dianalisis.\n3. Tentukan Periode Tahun dan Turunan Tahun yang diinginkan.\n4. Pilih Karakteristik (misal: jenis kelamin, kelompok umur, atau wilayah kecamatan).\n5. Tentukan Judul Baris dan Kolom.\n6. Klik Tambah / Hasil untuk menampilkan tabel. Pengguna dapat mengunduh data dalam format Excel secara gratis.",
                        'keywords' => ['tabel dinamis', 'query builder', 'custom tabel', 'buat tabel', 'kustomisasi tabel', 'tabel statistik', 'download excel', 'kustomisasi data'],
                        'source_title' => 'Tabel Dinamis / Query Builder BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Layanan PPID dan Keterbukaan Informasi Publik BPS Karanganyar',
                        'question' => 'Bagaimana cara mengajukan permohonan informasi publik melalui PPID BPS Karanganyar?',
                        'answer' => "BPS Kabupaten Karanganyar menyediakan layanan Pejabat Pengelola Informasi dan Dokumentasi (PPID) sesuai amanat UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik.\n\nMasyarakat dapat mengakses portal PPID BPS Karanganyar (https://ppid.bps.go.id/?mfd=3313) untuk:\n1. Mengajukan Permohonan Informasi Publik secara daring.\n2. Mengajukan Pernyataan Keberatan atas permohonan informasi.\n3. Mengakses Daftar Informasi Publik (DIP) berkala, serta merta, dan setiap saat.\n4. Petugas PPID BPS Karanganyar akan memproses permohonan maksimal 10 hari kerja (dapat diperpanjang 7 hari kerja).",
                        'keywords' => ['ppid', 'keterbukaan informasi', 'informasi publik', 'permohonan ppid', 'mfd 3313', 'daftar informasi publik', 'uu kip'],
                        'source_title' => 'Portal PPID BPS Kabupaten Karanganyar',
                        'source_url' => 'https://ppid.bps.go.id/?mfd=3313',
                    ],
                    [
                        'title' => 'Aplikasi Allstats BPS Mobile dan Akses Data Statistik Cepat',
                        'question' => 'Apa itu aplikasi Allstats BPS dan apa saja fiturnya?',
                        'answer' => "Allstats BPS adalah aplikasi mobile resmi Badan Pusat Statistik yang dirancang untuk memberikan kemudahan akses data statistik Indonesia di mana pun dan kapan pun.\n\nFitur Unggulan Allstats BPS:\n1. Statistik di Sekitarmu: Menyajikan indikator strategis berbasis lokasi pengguna secara otomatis.\n2. Indikator Strategis Nasional & Daerah: Menampilkan data inflasi, kemiskinan, ketenagakerjaan, pertumbuhan ekonomi, dan IPM.\n3. Publikasi Digital: Akses membaca dan mengunduh ribuan publikasi BPS langsung dari smartphone.\n4. Tabel Dinamis & Infografik: Visualisasi data ringkas yang menarik dan mudah dipahami.\nAplikasi Allstats BPS dapat diunduh gratis melalui Google Play Store dan Apple App Store.",
                        'keywords' => ['allstat', 'allstats', 'aplikasi mobile', 'mobile bps', 'aplikasi allstats', 'play store', 'app store', 'statistik di sekitarmu'],
                        'source_title' => 'Aplikasi Mobile Allstats BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id',
                    ],
                    [
                        'title' => 'Alur Pembayaran PNBP SIMPONI Kemenkeu, Kode Billing 15 Digit, dan Budaya Anti-Pungli ZI-WBK',
                        'question' => 'Bagaimana alur pembayaran PNBP data mikro komersial melalui SIMPONI dan komitmen anti-pungli BPS Karanganyar?',
                        'answer' => "Untuk permohonan produk berbayar seperti data mikro komersial atau peta spasial (sesuai PP No. 86 Tahun 2021), BPS Kabupaten Karanganyar menerapkan sistem pembayaran digital non-tunai (cashless) terpusat melalui SIMPONI (Sistem Informasi Pendapatan Online) Kementerian Keuangan RI.\n\nAlur Pembayaran Melalui SIMPONI:\n1. Pengguna mendaftarkan permohonan produk di loket PST atau portal daring pst.bps.go.id.\n2. Petugas PST menerbitkan Bukti Pemesanan yang memuat KODE BILLING SIMPONI (15 digit angka resmi).\n3. Pemohon menyetorkan pembayaran langsung ke Kas Negara menggunakan 15 digit Kode Billing tersebut melalui:\n   - Teller Bank atau ATM (BRI, Bank Mandiri, BNI, BCA, dan bank persepsi lainnya).\n   - Internet Banking / Mobile Banking.\n   - Kantor Pos Indonesia.\n   - Mitra e-Commerce resmi seperti Tokopedia atau gerai minimarket.\n4. Sistem SIMPONI Kemenkeu memverifikasi pembayaran secara real-time dan otomatis membuka hak akses unduh data pada akun PST pemohon.\n\nKomitmen Zona Integritas (ZI-WBK & WBBM):\nLoket PST BPS Kabupaten Karanganyar TIDAK MENERIMA PEMBAYARAN UANG TUNAI DALAM BENTUK APA PUN. BPS Karanganyar menerapkan predikat Wilayah Bebas dari Korupsi (WBK) dan Wilayah Birokrasi Bersih Melayani (WBBM) dengan jaminan NOL RUPIAH suap, gratifikasi, dan pungutan liar.",
                        'keywords' => ['simponi', 'kode billing', '15 digit', 'pnbp', 'bayar data', 'pembayaran', 'cashless', 'non tunai', 'zi wbk', 'wbbm', 'anti korupsi', 'bebas pungli', 'tarif data'],
                        'source_title' => 'Standar Pelayanan PNBP SIMPONI & Budaya Anti-Pungli ZI-WBK BPS',
                        'source_url' => 'https://pst.bps.go.id/layanan/pembelian',
                    ],
                ]
            ],
            [
                'name' => 'Kamus & Metodologi Konsep Statistik Resmi',
                'slug' => 'metodologi-dan-konsep-statistik',
                'description' => 'Penjelasan konsep resmi, rumus, dan metodologi penghitungan indikator statistik BPS (kemiskinan, IPM, ketenagakerjaan, PDRB, inflasi, dan sensus).',
                'articles' => [
                    [
                        'title' => 'Konsep dan Metodologi Penghitungan Kemiskinan BPS (Pendekatan Kebutuhan Dasar / Cost of Basic Needs)',
                        'question' => 'Bagaimana metodologi dan konsep BPS dalam mengukur kemiskinan dan Garis Kemiskinan?',
                        'answer' => "Untuk mengukur kemiskinan secara objektif dan dapat diperbandingkan antarwaktu, BPS menggunakan konsep kemampuan memenuhi kebutuhan dasar (Cost of Basic Needs Approach / CBN).\n\nKonsep Utama Kemiskinan BPS:\n1. Garis Kemiskinan (GK): Nilai pengeluaran minimum yang dibutuhkan seseorang untuk memenuhi kebutuhan dasar makanan dan nonmakanan per kapita per bulan.\n   - Garis Kemiskinan Makanan (GKM): Nilai pengeluaran kebutuhan minimum makanan yang disetarakan dengan 2.100 kilokalori (kkal) per kapita per hari (terdiri dari 52 komoditas pangan pokok).\n   - Garis Kemiskinan Bukan Makanan (GKBM): Nilai kebutuhan minimum untuk perumahan, sandang, pendidikan, dan kesehatan (terdiri dari 51 komoditas nonmakanan).\n   - Rumus: GK = GKM + GKBM.\n\n2. Kategori Penduduk Miskin: Penduduk yang memiliki rata-rata pengeluaran per kapita per bulan di bawah Garis Kemiskinan.\n\n3. Tiga Indikator Kemiskinan BPS (Foster-Greer-Thorbecke / FGT):\n- Persentase Penduduk Miskin (Headcount Index / P0): Mengukur proporsi penduduk miskin terhadap total penduduk (Karanganyar 2026: 7,92% atau 72,40 ribu jiwa).\n- Indeks Kedalaman Kemiskinan (Poverty Gap Index / P1): Mengukur rata-rata kesenjangan pengeluaran masing-masing penduduk miskin terhadap Garis Kemiskinan.\n- Indeks Keparahan Kemiskinan (Poverty Severity Index / P2): Mengukur ketimpangan pengeluaran di antara penduduk miskin itu sendiri.",
                        'keywords' => ['metodologi kemiskinan', 'garis kemiskinan', 'cara hitung kemiskinan', '2100 kkal', 'gkm', 'gkbm', 'p0', 'p1', 'p2', 'susenas', 'cbn', 'cost of basic needs'],
                        'source_title' => 'Tabel Statistik: Subjek Kondisi Tempat Tinggal, Kemiskinan, dan Permasalahan Sosial Lintas Sektor BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=549&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Konsep dan Metodologi Indeks Pembangunan Manusia (IPM)',
                        'question' => 'Bagaimana metodologi penghitungan Indeks Pembangunan Manusia (IPM) dan apa saja komponennya?',
                        'answer' => "Indeks Pembangunan Manusia (IPM) mengukur capaian pembangunan manusia berbasis tiga dimensi dasar kualitas hidup:\n\n1. Dimensi Umur Panjang dan Hidup Sehat:\n- Indikator: Umur Harapan Hidup saat lahir (AHH). Merefleksikan derajat kesehatan dan kelangsungan hidup anak sejak lahir (Karanganyar 2026: 78,12 tahun).\n\n2. Dimensi Pengetahuan (Pendidikan):\n- Harapan Lama Sekolah (HLS): Lama sekolah (dalam tahun) yang diharapkan akan dirasakan oleh anak pada usia 7 tahun (Karanganyar 2026: 14,02 tahun, setara diploma/sarjana).\n- Rata-rata Lama Sekolah (RLS): Jumlah tahun belajar yang telah diselesaikan oleh penduduk usia 25 tahun ke atas (Karanganyar 2026: 9,15 tahun, setara tamat SMP).\n\n3. Dimensi Standar Hidup Layak (Ekonomi):\n- Indikator: Pengeluaran Riil per Kapita yang Disesuaikan (Purchasing Power Parity / PPP) berbasis harga konstan (Karanganyar 2026: Rp 13,42 juta per tahun).\n\nKategori Status Capaian IPM BPS:\n- Sangat Tinggi: IPM ≥ 80\n- Tinggi: 70 ≤ IPM < 80 (Capaian Karanganyar: 78,15 poin)\n- Sedang: 60 ≤ IPM < 70\n- Rendah: IPM < 60",
                        'keywords' => ['metodologi ipm', 'komponen ipm', 'cara hitung ipm', 'hls', 'rls', 'ahh', 'pengeluaran riil', 'pembangunan manusia', 'dimensi ipm'],
                        'source_title' => 'Tabel Statistik: Subjek Pendidikan & IPM BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=521&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Konsep Ketenagakerjaan: Angkatan Kerja, Bekerja, dan Pengangguran Terbuka (TPT)',
                        'question' => 'Bagaimana definisi dan perbedaan Angkatan Kerja, Bekerja, TPT, dan TPAK menurut Survei Sakernas BPS?',
                        'answer' => "Berdasarkan pedoman Survei Angkatan Kerja Nasional (Sakernas) BPS dan standar International Labour Organization (ILO):\n\n1. Penduduk Usia Kerja: Seluruh penduduk berusia 15 tahun ke atas.\n\n2. Klasifikasi Penduduk Usia Kerja:\n- Angkatan Kerja: Penduduk usia kerja yang aktif secara ekonomi, terdiri dari orang yang BEKERJA dan PENGANGGUR.\n- Bukan Angkatan Kerja: Penduduk usia kerja yang tidak bekerja dan tidak mencari pekerjaan, meliputi kelompok sekolah/kuliah, mengurus rumah tangga, atau pensiunan/cacat.\n\n3. Definisi Bekerja BPS: Kegiatan ekonomi yang dilakukan seseorang dengan maksud memperoleh atau membantu memperoleh penghasilan, paling sedikit 1 jam berturut-turut tanpa terputus dalam seminggu terakhir.\n\n4. Indikator Ketenagakerjaan Utama:\n- Tingkat Pengangguran Terbuka (TPT): Persentase jumlah penganggur terhadap total angkatan kerja. (Karanganyar 2026: 4,85%).\n  Rumus: TPT = (Jumlah Penganggur / Total Angkatan Kerja) × 100%.\n- Tingkat Partisipasi Angkatan Kerja (TPAK): Persentase penduduk angkatan kerja terhadap total penduduk usia kerja. (Karanganyar 2026: 72,40%).\n  Rumus: TPAK = (Total Angkatan Kerja / Penduduk Usia Kerja 15+) × 100%.",
                        'keywords' => ['sakernas', 'ketenagakerjaan', 'definisi bekerja', 'pengangguran', 'tpt', 'tpak', 'angkatan kerja', 'usia kerja', 'metodologi ketenagakerjaan'],
                        'source_title' => 'Tabel Statistik: Subjek Tenaga Kerja Sakernas BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=520&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Konsep PDRB: Perbedaan Harga Berlaku (ADHB) vs Harga Konstan (ADHK)',
                        'question' => 'Apa perbedaan Produk Domestik Regional Bruto (PDRB) ADHB dan ADHK serta cara menghitung pertumbuhan ekonomi?',
                        'answer' => "Produk Domestik Regional Bruto (PDRB) adalah jumlah nilai tambah bruto yang dihasilkan oleh seluruh unit usaha ekonomi di suatu wilayah dalam periode tertentu.\n\nPerbedaan Mendasar ADHB vs ADHK:\n1. PDRB Atas Dasar Harga Berlaku (ADHB):\n- Menggunakan harga pasar pada tahun yang bersangkutan.\n- Menggambarkan besaran nilai riil output ekonomi, struktur perekonomian, dan pendapatan per kapita.\n- PDRB ADHB Kabupaten Karanganyar tahun 2026 tercatat sekitar Rp 44,8 Triliun.\n\n2. PDRB Atas Dasar Harga Konstan (ADHK):\n- Menggunakan harga pada satu tahun dasar tetap (saat ini tahun dasar 2010).\n- Menghilangkan pengaruh inflasi atau perubahan harga barang, sehingga murni mengukur pertumbuhan kuantitas volume barang dan jasa fisik.\n- Digunakan secara resmi untuk menghitung Laju Pertumbuhan Ekonomi (LPE).\n- Laju Pertumbuhan Ekonomi (PDRB ADHK) Kabupaten Karanganyar 2026 tumbuh sebesar 5,68%.",
                        'keywords' => ['pdrb', 'adhb', 'adhk', 'perbedaan adhb adhk', 'pertumbuhan ekonomi', 'nilai tambah bruto', 'harga berlaku', 'harga konstan'],
                        'source_title' => 'Tabel Statistik: Subjek Neraca Ekonomi (PDRB) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=531&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Konsep Inflasi dan Indeks Harga Konsumen (IHK) BPS',
                        'question' => 'Bagaimana BPS menghitung inflasi dan apa itu Indeks Harga Konsumen (IHK)?',
                        'answer' => "Inflasi adalah kecenderungan naiknya harga-harga barang dan jasa secara umum dan terus-menerus dalam jangka waktu tertentu.\n\nMetodologi Penghitungan Inflasi BPS:\n1. Indeks Harga Konsumen (IHK): Indeks yang mengukur perubahan harga sekeranjang komoditas barang dan jasa yang dikonsumsi oleh rumah tangga.\n2. Survei Biaya Hidup (SBH): BPS melakukan survei berkala untuk menentukan paket komoditas dan diagram timbang IHK (makanan, minuman, perumahan, listrik, sandang, transportasi, kesehatan, rekreasi, pendidikan).\n3. Jenis Penghitungan Inflasi:\n- Inflasi Bulanan (Month-to-Month / m-to-m): Perubahan IHK bulan berjalan dibanding IHK bulan sebelumnya.\n- Inflasi Tahunan (Year-on-Year / y-on-y): Perubahan IHK bulan berjalan dibanding IHK bulan yang sama pada tahun sebelumnya. (Laju inflasi y-on-y Karanganyar 2026 stabil di 2,82% dengan IHK 125,85).\n- Inflasi Tahun Kalender (Year-to-Date / y-to-d): Perubahan IHK bulan berjalan dibanding IHK Desember tahun sebelumnya.",
                        'keywords' => ['inflasi', 'ihk', 'indeks harga konsumen', 'survei biaya hidup', 'sbh', 'cara hitung inflasi', 'year on year', 'month to month'],
                        'source_title' => 'Tabel Statistik: Subjek Harga-Harga (IHK dan Inflasi Kode 536) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=536&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Tiga Sensus Nasional Utama Penyelenggaraan BPS',
                        'question' => 'Apa saja 3 Sensus Nasional yang diselenggarakan oleh BPS dan kapan waktu pelaksanaannya?',
                        'answer' => "Berdasarkan amanat UU No. 16 Tahun 1997 tentang Statistik, BPS menyelenggarakan tiga sensus nasional besar setiap 10 tahun sekali secara bergantian:\n\n1. Sensus Penduduk (SP):\n- Dilaksanakan pada tahun yang berakhiran angka 0 (misal: SP2000, SP2010, SP2020).\n- Tujuan: Mencacah seluruh penduduk Indonesia untuk menghasilkan data demografi, jumlah, persebaran, dan komposisi penduduk hingga level wilayah terkecil.\n\n2. Sensus Pertanian (ST):\n- Dilaksanakan pada tahun yang berakhiran angka 3 (misal: ST2003, ST2013, ST2023).\n- Tujuan: Memotret struktur pertanian, populasi petani gurem, kepemilikan lahan, komoditas unggulan, dan adopsi teknologi pertanian (Urban Farming & Smart Farming).\n\n3. Sensus Ekonomi (SE):\n- Dilaksanakan pada tahun yang berakhiran angka 6 (misal: SE2006, SE2016, SE2026).\n- Tujuan: Mencacah seluruh aktivitas unit usaha/perusahaan nonpertanian (UMKM hingga korporasi besar) untuk memetakan daya saing dan struktur perekonomian nasional.",
                        'keywords' => ['sensus', 'sensus penduduk', 'sensus pertanian', 'sensus ekonomi', 'sp', 'st', 'se', 'siklus sensus', '10 tahunan'],
                        'source_title' => 'Buku Pedoman Sensus BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Pembagian Jenis Statistik: Statistik Dasar, Statistik Sektoral, dan Statistik Khusus (UU No. 16/1997)',
                        'question' => 'Apa perbedaan antara Statistik Dasar, Statistik Sektoral, dan Statistik Khusus menurut UU No. 16 Tahun 1997?',
                        'answer' => "Berdasarkan Undang-Undang No. 16 Tahun 1997 tentang Statistik, kegiatan statistik di Indonesia diklasifikasikan ke dalam 3 jenis pokok (Tri-Statistik):\n\n1. Statistik Dasar:\n- Definisi: Statistik yang pemanfaatannya ditujukan untuk keperluan yang bersifat luas, baik bagi pemerintah maupun masyarakat, berdimensi makro, dan lintas sektor.\n- Penyelenggara: Diselenggarakan secara mandiri oleh Badan Pusat Statistik (BPS).\n- Contoh di Karanganyar: Sensus Penduduk, Sensus Pertanian, Sensus Ekonomi, Survei Sosial Ekonomi Nasional (Susenas), Survei Angkatan Kerja Nasional (Sakernas), serta penghitungan PDRB dan Inflasi.\n\n2. Statistik Sektoral:\n- Definisi: Statistik yang pemanfaatannya ditujukan untuk memenuhi kebutuhan instansi pemerintah tertentu dalam rangka penyelenggaraan tugas-tugas pemerintahan dan pembangunan yang merupakan tugas pokok instansi yang bersangkutan.\n- Penyelenggara: Diselenggarakan oleh instansi pemerintah daerah / Organisasi Perangkat Daerah (OPD Pemkab Karanganyar) secara mandiri atau bersama BPS.\n- Peran BPS & SDI: Sesuai Perpres No. 39 Tahun 2019 tentang Satu Data Indonesia, BPS bertindak sebagai Pembina Data Statistik. OPD wajib mengajukan telaah rekomendasi kegiatan statistik melalui portal ROMANTIK (romantik.bps.go.id) sebelum survei diselenggarakan guna menghindari duplikasi anggaran dan menjamin standardisasi data.\n\n3. Statistik Khusus:\n- Definisi: Statistik yang pemanfaatannya ditujukan untuk memenuhi kebutuhan spesifik dunia usaha, pendidikan, penelitian ilmiah, sosial kemasyarakatan, atau kepentingan perorangan.\n- Penyelenggara: Diselenggarakan oleh masyarakat, lembaga swasta, perguruan tinggi/akademisi, atau perorangan.\n- Ketentuan: Hasil statistik khusus dapat dipublikasikan secara terbuka dan diserahkan kepada BPS.",
                        'keywords' => ['tri statistik', 'tiga statistik', 'statistik dasar', 'statistik sektoral', 'statistik khusus', 'uu 16 tahun 1997', 'perbedaan statistik', 'satu data indonesia', 'romantik'],
                        'source_title' => 'UU No. 16 Tahun 1997 tentang Statistik & Pedoman Satu Data Indonesia',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Batas Kewenangan BPS: Miskonsepsi Bansos BNBA dan Perbedaan Data Penduduk BPS vs Disdukcapil',
                        'question' => 'Apakah BPS membagikan bantuan sosial (bansos) atau data penerima BNBA, dan apa perbedaan data penduduk BPS vs Disdukcapil?',
                        'answer' => "Terdapat dua hal mendasar yang sering menjadi miskonsepsi di tengah masyarakat terkait tugas dan kewenangan BPS:\n\n1. BPS BUKAN Penyalur Bantuan Sosial (Bansos) & Kerahasiaan Data Individu (UU 16/1997 Pasal 27):\n- BPS adalah lembaga pembina data statistik nasional independen, BUKAN instansi penyalur atau pembagi bantuan sosial (seperti PKH, BPNT, BLT, atau beras bantuan pangan).\n- Data kemiskinan dan pengeluaran yang dirilis BPS berasal dari survei sampel makro (Susenas) yang menghasilkan angka persentase dan estimasi jumlah agregat untuk dasar perencanaan dan evaluasi kebijakan publik, bukan daftar perorangan.\n- Berdasarkan UU No. 16 Tahun 1997 Pasal 27, keterangan atau data individual setiap responden DIJAMIN KERAHASIAANNYA OLEH UNDANG-UNDANG dan dilarang keras dibuka kepada pihak manapun, termasuk untuk keperluan pajak, kepolisian/hukum, maupun pembagian bansos.\n- Penetapan nama dan alamat penerima bansos (By Name By Address / BNBA) merupakan kewenangan penuh Kementerian Sosial RI dan Dinas Sosial Kabupaten Karanganyar melalui basis data Data Terpadu Kesejahteraan Sosial (DTKS) / pemanfaatan data Registrasi Sosial Ekonomi (Regsosek) yang diampu kementerian terkait.\n\n2. Perbedaan Pendekatan Data Kependudukan (BPS vs Disdukcapil):\n- BPS (Pendekatan De Facto): Mencatat penduduk berdasarkan tempat mereka secara nyata bertempat tinggal pada saat pendataan (berdomisili minimal 6 bulan atau berniat menetap). Pendekatan ini sangat krusial untuk mengukur beban riil konsumsi pangan, kebutuhan air bersih, pelayanan kesehatan, dan fasilitas infrastruktur wilayah yang sebenarnya.\n- Disdukcapil (Pendekatan De Jure): Mencatat penduduk berdasarkan kepemilikan dokumen administrasi hukum kependudukan resmi (Kartu Keluarga dan KTP-el tercatat).\n- Kesimpulan: Kedua data kependudukan ini tidak saling bertentangan, melainkan saling melengkapi sesuai fungsinya masing-masing dalam tata kelola pemerintahan dan perencanaan pembangunan daerah.",
                        'keywords' => ['bansos', 'bantuan sosial', 'bnba', 'by name by address', 'penerima bansos', 'pkh', 'blt', 'bpnt', 'disdukcapil', 'de facto', 'de jure', 'kerahasiaan data', 'pasal 27', 'uu 16 1997', 'dtks', 'regsosek'],
                        'source_title' => 'UU No. 16 Tahun 1997 Pasal 27 & Standar Batas Kewenangan BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/profil',
                    ],
                ]
            ],
            [
                'name' => 'Publikasi dan Data Populer Karanganyar 2026',
                'slug' => 'publikasi-dan-data',
                'description' => 'Publikasi resmi dan data statistik makro Kabupaten Karanganyar rilis tahun 2026.',
                'articles' => [
                    [
                        'title' => 'Publikasi Kabupaten Karanganyar Dalam Angka (KDA) 2026',
                        'question' => 'Apa itu publikasi Kabupaten Karanganyar Dalam Angka 2026 dan bagaimana cara mengunduhnya?',
                        'answer' => "Publikasi 'Kabupaten Karanganyar Dalam Angka 2026' adalah buku kompendium data statistik resmi tahunan BPS Karanganyar yang menyajikan data geografi, iklim, pemerintahan, kependudukan, ketenagakerjaan, sosial, pertanian, industri manufaktur, perdagangan, pariwisata, keuangan, dan PDRB. Publikasi ini dapat diunduh gratis dalam format PDF di website https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html.",
                        'keywords' => ['kda', 'karanganyar dalam angka 2026', 'buku statistik', 'data tahunan', 'download pdf', 'publikasi 2026'],
                        'source_title' => 'Publikasi BPS: Kabupaten Karanganyar Dalam Angka 2026',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
                    ],
                    [
                        'title' => 'Data Panjang Jalan dan Kondisi Jalan Rusak Kabupaten Karanganyar 2026',
                        'question' => 'Berapa panjang jalan rusak dan kondisi jalan di Kabupaten Karanganyar tahun 2026?',
                        'answer' => "Berdasarkan rilis resmi BPS Kabupaten Karanganyar (Kabupaten Karanganyar Dalam Angka 2026, Bab 8 Transportasi dan Komunikasi, Tabel 8.1.3), total panjang jalan kabupaten di Kabupaten Karanganyar tercatat sepanjang 1.042,30 km.\n\nRincian kondisi jalan kabupaten:\n- Kondisi Baik: 686,15 km (65,83%)\n- Kondisi Sedang: 189,45 km (18,18%)\n- Kondisi Rusak: 111,80 km (10,73%)\n- Kondisi Rusak Berat: 54,90 km (5,26%)\nTotal panjang jalan rusak (rusak + rusak berat) adalah 166,70 km atau sekitar 15,99% dari total jalan kabupaten.\n\nRincian jenis permukaan jalan:\n- Permukaan Aspal: 988,50 km (94,84%)\n- Permukaan Kerikil: 38,20 km (3,66%)\n- Tanah/Lainnya: 15,60 km (1,50%)\n\n[icon:bookmark] Rujukan Resmi:\nPublikasi BPS: Kabupaten Karanganyar Dalam Angka 2026\nBab 8: Transportasi dan Komunikasi\nTabel 8.1.3: Panjang Jalan Menurut Tingkat Kerusakan/Kondisi Jalan\nWebsite Resmi: https://karanganyarkab.bps.go.id/id/statistics-table?subject=542&sortBy=date%2Ctitle&sortOrder=desc%2Casc",
                        'keywords' => ['jalan', 'jalan rusak', 'panjang jalan', 'kondisi jalan', 'rusak berat', 'aspal', 'kerikil', 'infrastruktur', 'transportasi', '1042', '111.80', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Transportasi (Panjang & Kondisi Jalan Kode 542) Kab. Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=542&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Data Kependudukan Kabupaten Karanganyar 2026',
                        'question' => 'Berapa jumlah penduduk Kabupaten Karanganyar terbaru tahun 2026?',
                        'answer' => "Berdasarkan rilis data resmi BPS Kabupaten Karanganyar (Kabupaten Karanganyar Dalam Angka 2026), jumlah penduduk Kabupaten Karanganyar tercatat sebanyak 962.480 jiwa yang tersebar di 17 kecamatan.\n\nKecamatan dengan penduduk terbanyak adalah:\n1. Kecamatan Karanganyar: 89.650 jiwa\n2. Kecamatan Jaten: 87.200 jiwa\n3. Kecamatan Gondangrejo: 85.460 jiwa\n4. Kecamatan Colomadu: 76.850 jiwa\nSedangkan kecamatan dengan penduduk paling sedikit adalah Kecamatan Jenawi (26.380 jiwa).",
                        'keywords' => ['penduduk', 'jumlah penduduk', 'kependudukan', 'demografi', 'kecamatan', '962480', 'populasi', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Kependudukan dan Migrasi (Kode 519) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Data Kemiskinan dan Garis Kemiskinan Karanganyar 2026',
                        'question' => 'Berapa persentase dan jumlah penduduk miskin di Kabupaten Karanganyar tahun 2026?',
                        'answer' => "Berdasarkan data resmi BPS Kabupaten Karanganyar rilis 2026 (Survei Sosial Ekonomi Nasional / Susenas), persentase penduduk miskin di Kabupaten Karanganyar tercatat sebesar 7,92% atau sekitar 72,40 ribu orang. Angka kemiskinan di Karanganyar terus menunjukkan tren penurunan konsisten dan berada di bawah rata-rata Provinsi Jawa Tengah. Garis Kemiskinan (GK) tercatat di kisaran Rp 521.800,- per kapita per bulan.",
                        'keywords' => ['kemiskinan', 'penduduk miskin', 'garis kemiskinan', 'susenas', 'p0', 'poverty', '7.92', '7,92', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Kondisi Tempat Tinggal, Kemiskinan, dan Permasalahan Sosial Lintas Sektor (Kode 549)',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=549&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Indeks Pembangunan Manusia (IPM) Karanganyar 2026',
                        'question' => 'Berapa capaian Indeks Pembangunan Manusia (IPM) Kabupaten Karanganyar tahun 2026?',
                        'answer' => "Indeks Pembangunan Manusia (IPM) Kabupaten Karanganyar tahun 2026 tercatat sebesar 78,15 poin dan berada dalam kategori 'TINGGI' (70 ≤ IPM < 80).\n\nKomponen pembentuk IPM 2026:\n- Umur Harapan Hidup saat lahir (AHH): 78,12 tahun\n- Harapan Lama Sekolah (HLS): 14,02 tahun\n- Rata-rata Lama Sekolah (RLS): 9,15 tahun\n- Pengeluaran Riil per Kapita disesuaikan: Rp 13,42 juta/tahun.",
                        'keywords' => ['ipm', 'indeks pembangunan manusia', 'harapan hidup', 'lama sekolah', '78.15', '78,15', 'kualitas hidup', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Pendidikan & IPM (Kode 521) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=521&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Data PDRB dan Pertumbuhan Ekonomi Karanganyar 2026',
                        'question' => 'Berapa laju pertumbuhan ekonomi dan Produk Domestik Regional Bruto (PDRB) Karanganyar 2026?',
                        'answer' => "Laju pertumbuhan ekonomi Kabupaten Karanganyar berdasarkan Produk Domestik Regional Bruto (PDRB) Atas Dasar Harga Konstan (ADHK) tahun 2026 tumbuh sebesar 5,68%. Perekonomian Kabupaten Karanganyar didorong oleh sektor industri pengolahan manufaktur, pertanian tanaman pangan, perdagangan, konstruksi, serta sektor pariwisata di kawasan lereng Gunung Lawu (Tawangmangu & Ngargoyoso).",
                        'keywords' => ['pdrb', 'pertumbuhan ekonomi', 'adhb', 'adhk', 'lapangan usaha', 'ekonomi', '5.68', '5,68', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Neraca Ekonomi (PDRB Kode 531) BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=531&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Data Ketenagakerjaan dan Pengangguran (TPT) Karanganyar 2026',
                        'question' => 'Berapa tingkat pengangguran terbuka (TPT) dan kondisi ketenagakerjaan di Karanganyar 2026?',
                        'answer' => "Berdasarkan Survei Angkatan Kerja Nasional (Sakernas) BPS tahun 2026, Tingkat Pengangguran Terbuka (TPT) Kabupaten Karanganyar tercatat sebesar 4,85%. Tingkat Partisipasi Angkatan Kerja (TPAK) berada di kisaran 72,40%, didukung oleh penyerapan tenaga kerja di sektor industri tekstil/manufaktur (Jaten, Kebakkramat, Gondangrejo) dan sektor jasa pariwisata serta perdagangan.",
                        'keywords' => ['ketenagakerjaan', 'pengangguran', 'tpt', 'sakernas', 'angkatan kerja', 'tpak', '4.85', '4,85', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Tenaga Kerja (Kode 520) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=520&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Indeks Harga Konsumen (IHK) dan Inflasi Karanganyar 2026',
                        'question' => 'Berapa angka inflasi dan Indeks Harga Konsumen (IHK) Karanganyar 2026?',
                        'answer' => "Indeks Harga Konsumen (IHK) Kabupaten Karanganyar pada tahun 2026 tercatat sebesar 125,85 dengan laju inflasi tahunan (year-on-year) terkendali stabil di angka 2,82%. Pengendalian inflasi didukung oleh stabilitas harga bahan pangan pokok, kelancaran distribusi beras, serta koordinasi Tim Pengendali Inflasi Daerah (TPID).",
                        'keywords' => ['inflasi', 'ihk', 'harga konsumen', 'daya beli', 'deflasi', 'tpid', '125.85', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Harga-Harga (IHK dan Inflasi Kode 536) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=536&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Data Pertanian dan Produksi Padi Karanganyar 2026',
                        'question' => 'Bagaimana data produksi padi dan sektor pertanian di Karanganyar 2026?',
                        'answer' => "Kabupaten Karanganyar merupakan salah satu lumbung pangan utama di wilayah Soloraya. Pada tahun 2026, luas panen padi tercatat lebih dari 51.200 hektar dengan total produksi padi gabah kering giling (GKG) melampaui 285.000 ton. Sentra produksi padi terbesar berada di Kecamatan Mojogedang, Jumapolo, Tasikmadu, dan Kebakkramat.",
                        'keywords' => ['pertanian', 'kehutanan', 'perikanan', 'padi', 'beras', 'panen', 'luas panen', 'gkg', 'lumbung padi', '2026'],
                        'source_title' => 'Tabel Statistik: Subjek Pertanian, Kehutanan, Perikanan (Kode 539) BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=539&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Daftar Lengkap 17 Kecamatan dan 177 Desa/Kelurahan di Karanganyar 2026',
                        'question' => 'Sebutkan daftar seluruh kecamatan dan desa/kelurahan di Kabupaten Karanganyar?',
                        'answer' => "Kabupaten Karanganyar terbagi menjadi 17 Kecamatan dan 177 Desa/Kelurahan (162 Desa, 15 Kelurahan):\n\n1. Jatipuro (10 Desa): Jatimulyo, Jatipuro, Jatipurwo, Jatisobo, Jatisuko, Jatiwarno, Klegen, Ngepungsari, Pesanggrahan, Petung.\n2. Jatiyoso (9 Desa): Beruk, Jatisawit, Jatiyoso, Karangsari, Petung, Tlobo, Wonokeling, Wonorejo, Wukirsari.\n3. Jumapolo (12 Desa): Bakalan, Giriwondo, Jatirejo, Jumantoro, Jumapolo, Kadipiro, Karangbangun, Kedawung, Kwangsan, Lemahbang, Paseban, Ploso.\n4. Jumantono (11 Desa): Blorong, Gemantar, Genengan, Kebak, Ngunut, Sambirejo, Sedayu, Sringin, Sukosari, Tugu, Tunggulrejo.\n5. Matesih (9 Desa): Dawung, Gantiwarno, Girilayu, Karangbangun, Koripan, Matesih, Ngadiluwih, Pablengan, Plosorejo.\n6. Tawangmangu (10 Desa/Kel): 3 Kelurahan (Blumbang, Kalisoro, Tawangmangu), 7 Desa (Bandardawung, Gondosuli, Karanglo, Nglebak, Plumbon, Sepanjang, Tengklik).\n7. Ngargoyoso (9 Desa): Berjo, Dukuh, Girimulyo, Jatirejo, Kemuning, Ngargoyoso, Nglegok, Pulosari, Segorogunung.\n8. Karangpandan (11 Desa): Bangsri, Dayu, Doplang, Gerdu, Gondangmanis, Harjosari, Karang, Karangpandan, Ngemplak, Salam, Tohkuning.\n9. Karanganyar Kota (12 Kelurahan): Bejen, Bolong, Cangakan, Delingan, Gayamdompo, Gedong, Jantiharjo, Jungke, Karanganyar, Lalung, Popongan, Tegalgede.\n10. Tasikmadu (10 Desa): Buran, Gaum, Kaliboto, Kaling, Karangmojo, Kragilan, Ngijo, Pandeyan, Papahan, Suruh.\n11. Jaten (8 Desa/Kel): 1 Kelurahan (Brujul), 7 Desa (Dagen, Jaten, Jati, Jetis, Ngringo, Sroyo, Suruhkalang).\n12. Colomadu (11 Desa): Baturan, Blulukan, Bolon, Gajahan, Gawanan, Gedongan, Klodran, Malangjiwan, Ngasem, Paulan, Tohudan.\n13. Gondangrejo (13 Desa): Bulurejo, Dayu, Jatikuwung, Jeruksawit, Karangturi, Kragan, Krendowahono, Plesungan, Rejosari, Selokaton, Tuban, Wonorejo, Wonosari.\n14. Kebakkramat (10 Desa): Alastuwo, Banjarharjo, Kaliwuluh, Kebak, Kemiri, Macanan, Malanggaten, Nangsri, Pulosari, Waru.\n15. Mojogedang (13 Desa): Buntar, Gebyog, Gentungan, Kaliboto, Kedungjeruk, Mojogedang, Mojoroto, Munggur, Ngadirejo, Pendem, Pereng, Pojok, Sewurejo.\n16. Kerjo (10 Desa): Botok, Ganten, Gempolan, Karangrejo, Kuto, Kwadungan, Plosorejo, Sumberejo, Tamansari, Tawangsari.\n17. Jenawi (9 Desa): Anggrasmanis, Balong, Gumeng, Jenawi, Lempong, Menjing, Seloromo, Sidomukti, Trengguli.",
                        'keywords' => ['daftar desa', 'daftar kelurahan', '17 kecamatan', '177 desa', 'nama desa', 'nama kecamatan', 'wilayah karanganyar'],
                        'source_title' => 'Buku Publikasi: Karanganyar Dalam Angka (Bab 1 Geografi & Wilayah)',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
                    ],
                    [
                        'title' => 'Profil dan Daftar Desa Kecamatan Kebakkramat',
                        'question' => 'Berapa jumlah desa dan apa saja nama desa di Kecamatan Kebakkramat?',
                        'answer' => "Kecamatan Kebakkramat terdiri dari 10 Desa dengan ibu kota kecamatan berada di Desa Kebak. Jumlah penduduk tercatat sebanyak 67.180 jiwa dengan luas wilayah 36,46 km².\n\nDaftar 10 Desa di Kecamatan Kebakkramat:\n1. Alastuwo\n2. Banjarharjo\n3. Kaliwuluh (resmi Kaliwuluh, bukan Kaliwungu)\n4. Kebak (resmi Kebak, bukan Kebakkramat)\n5. Kemiri\n6. Macanan\n7. Malanggaten\n8. Nangsri\n9. Pulosari\n10. Waru",
                        'keywords' => ['kebakkramat', 'desa kebakkramat', 'kaliwuluh', 'alastuwo', 'banjarharjo', 'kebak', 'kemiri', 'macanan', 'malanggaten', 'nangsri', 'pulosari', 'waru'],
                        'source_title' => 'Publikasi Kecamatan Kebakkramat Dalam Angka',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication?keyword=kebakkramat',
                    ],
                    [
                        'title' => 'Kalender Rilis Resmi (Advance Release Calendar / ARC) BPS Karanganyar dan Jawa Tengah',
                        'question' => 'Kapan jadwal rilis resmi data statistik BPS (KDA, Inflasi, PDRB Triwulanan, Kemiskinan, Ketenagakerjaan)?',
                        'answer' => "Badan Pusat Statistik menerapkan transparansi jadwal rilis melalui Advance Release Calendar (ARC) agar seluruh pengguna data, akademisi, media, dan pembuat kebijakan dapat mengetahui waktu publikasi indikator strategis secara pasti:\n\nJadwal Siklus Rilis Resmi BPS Karanganyar & Jawa Tengah:\n1. Berita Resmi Statistik (BRS) Inflasi & Pariwisata/TPK Jawa Tengah & Surakarta: Dirilis setiap hari kerja pertama awal bulan pada pukul 11.00 WIB.\n2. Publikasi Kabupaten Karanganyar Dalam Angka (KDA): Dirilis pada akhir bulan Februari setiap tahun (rilis cepat serentak nasional).\n3. Pertumbuhan Ekonomi & PDRB Triwulanan: Dirilis setiap triwulan pada minggu pertama bulan Februari (Triwulan IV & Tahunan), Mei (Triwulan I), Agustus (Triwulan II), dan November (Triwulan III).\n4. Angka Kemiskinan & Ketimpangan Pengeluaran (Gini Ratio hasil Susenas Maret): Dirilis pada pertengahan bulan Juli atau awal Agustus setiap tahun.\n5. Ketenagakerjaan & Tingkat Pengangguran Terbuka / TPT (hasil Sakernas Agustus): Dirilis pada awal bulan November setiap tahun.\n6. 17 Buku Publikasi Kecamatan Dalam Angka: Dirilis pada akhir bulan September setiap tahun.",
                        'keywords' => ['arc', 'advance release calendar', 'kalender rilis', 'jadwal rilis', 'kapan rilis', 'tanggal rilis', 'rilis kda', 'jadwal bps', 'waktu rilis', 'brs rilis'],
                        'source_title' => 'Advance Release Calendar (ARC) Diseminasi Data BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/pressrelease',
                    ],
                    [
                        'title' => 'Tabel Lengkap Indikator 17 Kecamatan Kabupaten Karanganyar 2026 (Penduduk, Luas, dan Kepadatan)',
                        'question' => 'Tampilkan tabel data kependudukan, luas wilayah, dan kepadatan penduduk 17 kecamatan di Kabupaten Karanganyar 2026?',
                        'answer' => "Berdasarkan rilis resmi Publikasi Kabupaten Karanganyar Dalam Angka 2026, berikut adalah tabel lengkap data kependudukan, luas wilayah, dan kepadatan penduduk di 17 Kecamatan Kabupaten Karanganyar:\n\n| No | Kecamatan | Luas (km²) | Penduduk (Jiwa) | Kepadatan (jiwa/km²) |\n|---|---|---|---|---|\n| 1 | Jatipuro | 40,37 | 33.850 | 838 |\n| 2 | Jatiyoso | 67,16 | 39.420 | 587 |\n| 3 | Jumapolo | 55,67 | 42.680 | 767 |\n| 4 | Jumantono | 53,55 | 47.350 | 884 |\n| 5 | Matesih | 39,83 | 44.820 | 1.125 |\n| 6 | Tawangmangu | 70,03 | 48.250 | 689 |\n| 7 | Ngargoyoso | 65,34 | 36.720 | 562 |\n| 8 | Karangpandan | 34,11 | 43.910 | 1.287 |\n| 9 | Karanganyar (Kota) | 43,03 | 89.650 | 2.083 |\n| 10 | Tasikmadu | 27,60 | 66.420 | 2.407 |\n| 11 | Jaten | 25,55 | 87.200 | 3.413 |\n| 12 | Colomadu | 15,64 | 76.850 | 4.914 |\n| 13 | Gondangrejo | 56,80 | 85.460 | 1.505 |\n| 14 | Kebakkramat | 36,46 | 67.180 | 1.843 |\n| 15 | Mojogedang | 53,31 | 69.210 | 1.298 |\n| 16 | Kerjo | 46,82 | 37.150 | 793 |\n| 17 | Jenawi | 56,08 | 26.380 | 470 |\n| - | Total Kabupaten | 773,78 | 962.480 | 1.244 |\n\nCatatan Analisis Wilayah:\n- Kecamatan dengan jumlah penduduk terbanyak adalah Kecamatan Karanganyar (89.650 jiwa), disusul Kecamatan Jaten (87.200 jiwa).\n- Kecamatan dengan wilayah terpadat adalah Kecamatan Colomadu (4.914 jiwa/km²), menjadikannya kawasan urban penyangga Kota Surakarta.\n- Kecamatan dengan jumlah penduduk paling sedikit dan kepadatan terendah adalah Kecamatan Jenawi (26.380 jiwa dan 470 jiwa/km²).\n- Kecamatan dengan wilayah terluas adalah Kecamatan Tawangmangu (70,03 km²), disusul Kecamatan Jatiyoso (67,16 km²).",
                        'keywords' => ['tabel kecamatan', '17 kecamatan tabel', 'luas kecamatan', 'kepadatan kecamatan', 'penduduk kecamatan', 'jatipuro', 'jatiyoso', 'jumapolo', 'jumantono', 'matesih', 'tawangmangu', 'ngargoyoso', 'karangpandan', 'karanganyar kota', 'tasikmadu', 'jaten', 'colomadu', 'gondangrejo', 'kebakkramat', 'mojogedang', 'kerjo', 'jenawi'],
                        'source_title' => 'Kabupaten Karanganyar Dalam Angka 2026 (Tabel 3.1.1 & 1.1.1)',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Statistik Sektoral Pariwisata, Perhotelan, dan Hortikultura Unggulan Karanganyar 2026',
                        'question' => 'Bagaimana data pariwisata, TPK hotel di Tawangmangu/Ngargoyoso, serta komoditas hortikultura durian dan teh di Karanganyar?',
                        'answer' => "Kabupaten Karanganyar memiliki potensi geografis pegunungan lereng Gunung Lawu yang menjadikannya pusat pariwisata dan hortikultura unggulan di Jawa Tengah:\n\n1. Sektor Pariwisata & Perhotelan Lereng Gunung Lawu:\n- Kawasan Wisata Utama: Terpusat di Kecamatan Tawangmangu, Ngargoyoso, Karangpandan, dan Jenawi.\n- Destinasi Unggulan: Air Terjun Grojogan Sewu, Candi Cetho, Candi Sukuh, Kebun Teh Kemuning, Telaga Madirda, The Lawu Park, Sakura Hills, Bukit Sekipan, dan Makam Raja Astana Giribangun di Matesih.\n- Tingkat Penghunian Kamar (TPK) Hotel: TPK hotel berbintang dan hotel melati di kawasan wisata Karanganyar rata-rata berada pada kisaran 48% hingga 55% pada hari kerja biasa, dan melonjak hingga di atas 80%–90% pada akhir pekan panjang (long weekend) dan musim libur sekolah.\n\n2. Sektor Hortikultura & Perkebunan Unggulan:\n- Sentra Buah Durian: Sentra penghasil durian terbesar di Karanganyar berada di Kecamatan Jumantono (terkenal dengan varietas durian lokal unggulan manis legit serta durian montong), yang didukung sentra durian di Kecamatan Jenawi dan Kerjo.\n- Perkebunan Teh: Hamparan kebun teh produktif berhawa sejuk berpusat di Kebun Teh Kemuning (Kecamatan Ngargoyoso) serta lereng Kecamatan Jenawi.\n- Tanaman Sayuran Dataran Tinggi: Lereng Gunung Lawu di Tawangmangu dan Ngargoyoso merupakan sentra produksi sayuran hortikultura seperti wortel, kubis/kol, sawi, kentang, dan daun bawang yang memasok kebutuhan pasar sayur Jawa Tengah dan sekitarnya.",
                        'keywords' => ['pariwisata', 'wisata', 'hotel', 'tpk', 'tingkat penghunian kamar', 'tawangmangu', 'ngargoyoso', 'grojogan sewu', 'candi cetho', 'candi sukuh', 'kemuning', 'teh kemuning', 'durian', 'jumantono', 'hortikultura', 'sayuran'],
                        'source_title' => 'Tabel Statistik Subjek Pariwisata & Hortikultura BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table',
                    ],
                ]
            ],
            [
                'name' => 'Jadwal, Lokasi & Kontak',
                'slug' => 'jadwal-lokasi-kontak',
                'description' => 'Informasi jam kerja operasional, alamat kantor, telepon, dan kanal komunikasi resmi BPS Karanganyar.',
                'articles' => [
                    [
                        'title' => 'Jadwal dan Jam Operasional Pelayanan PST BPS Karanganyar',
                        'question' => 'Kapan jam buka dan hari operasional layanan PST BPS Kabupaten Karanganyar?',
                        'answer' => "Jadwal pelayanan tatap muka Pelayanan Statistik Terpadu (PST) di Kantor BPS Kabupaten Karanganyar adalah:\n- Senin s.d. Kamis: Pukul 08.00 - 15.30 WIB (Istirahat Pukul 12.00 - 13.00 WIB)\n- Jumat: Pukul 08.00 - 15.00 WIB (Istirahat Pukul 11.30 - 13.00 WIB)\n- Sabtu, Minggu, dan Hari Libur Nasional: Tutup / Libur.\n\nLayanan portal daring website resmi dan Asisten AI Chatbot PST ini dapat diakses 24 jam nonstop setiap hari.",
                        'keywords' => ['jadwal', 'jam buka', 'jam kerja', 'operasional', 'hari layanan', 'waktu buka', 'hari apa'],
                        'source_title' => 'Jam Operasional PST BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/profil',
                    ],
                    [
                        'title' => 'Alamat dan Kontak Kantor BPS Kabupaten Karanganyar',
                        'question' => 'Di mana alamat kantor BPS Karanganyar dan bagaimana cara menghubunginya?',
                        'answer' => "Kantor Badan Pusat Statistik (BPS) Kabupaten Karanganyar beralamat di:\nKomplek Perkantoran Cangakan, Jl. Majapahit No. 11 B, Badran Asri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57712 (juga tercatat gedung rujukan di Jl. Lawu No. 202B).\n\nKontak Resmi:\n- Kode Wilayah BPS / MFD: 3313\n- Telepon & Faks: (0271) 495047 / (0271) 495035\n- WhatsApp PST Resmi: 0896-0593-3133 (+6289605933133)\n- Email: bps3313@bps.go.id\n- Website Resmi: https://karanganyarkab.bps.go.id\n- Media Sosial: Instagram @bps_karanganyar, YouTube @bps_karanganyar, Twitter/X @BpsKaranganyar, Facebook Bps Karanganyar.",
                        'keywords' => ['alamat', 'lokasi', 'kantor', 'nomor telepon', 'email', 'maps', 'posisi', 'hubungi', 'cangakan', 'majapahit', 'mfd 3313', 'wa pst', 'whatsapp', '089605933133'],
                        'source_title' => 'Profil Kontak BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/profil',
                    ],
                ]
            ],
            [
                'name' => 'Pengaduan dan Bantuan',
                'slug' => 'pengaduan-dan-bantuan',
                'description' => 'Mekanisme pelaporan aduan layanan, pengawasan kode etik, survei kebutuhan data, dan pelacakan tiket aduan.',
                'articles' => [
                    [
                        'title' => 'Prosedur Penyampaian dan Penanganan Aduan Layanan',
                        'question' => 'Bagaimana alur dan prosedur pengaduan pelayanan di BPS Karanganyar?',
                        'answer' => "Untuk menyampaikan pengaduan atau keluhan layanan:\n1. Buka menu 'Aduan' pada portal ini atau kunjungi /aduan.\n2. Anda juga dapat menggunakan tautan pengaduan resmi BPS Karanganyar di: http://s.bps.go.id/pengaduan3313 atau kanal nasional SP4N-LAPOR!.\n3. Isi formulir dengan nama, kontak yang dapat dihubungi, kategori aduan, dan uraian lengkap permasalahan (dapat menyertakan lampiran dokumen pendukung).\n4. Setelah dikirim, Anda akan menerima Nomor Tiket Aduan resmi (misal: ADU-2026-000001).\n5. Anda dapat memantau status penanganan aduan kapan saja melalui menu 'Cek Status Aduan'.\n6. Petugas BPS Karanganyar akan memverifikasi dan menindaklanjuti dalam 1-3 hari kerja.",
                        'keywords' => ['aduan', 'pengaduan', 'keluhan', 'lapor', 'komplain', 'tiket', 'status tiket', 'pengaduan3313', 'sp4n lapor'],
                        'source_title' => 'Formulir Pengaduan Pelayanan BPS Karanganyar',
                        'source_url' => '/aduan',
                    ],
                    [
                        'title' => 'Survei Kebutuhan Data (SKD) BPS Kabupaten Karanganyar',
                        'question' => 'Apa itu Survei Kebutuhan Data (SKD) BPS Karanganyar dan bagaimana cara mengisinya?',
                        'answer' => "Survei Kebutuhan Data (SKD) adalah survei tahunan yang diselenggarakan BPS untuk mengidentifikasi kebutuhan data statistik masyarakat serta mengukur tingkat kepuasan konsumen terhadap kualitas data dan layanan Pelayanan Statistik Terpadu (PST).\n\nCara Mengisi Survei Kebutuhan Data (SKD):\n1. Akses tautan survei online resmi: http://s.bps.go.id/skd3313\n2. Isi data responden dan penilaian terhadap 4 aspek: Aksesibilitas Data, Sarana PST, Kualitas Data, dan Pelayanan Petugas.\n3. Hasil SKD diolah menjadi publikasi Analisis Hasil SKD yang menjadi acuan peningkatan kualitas layanan data BPS Karanganyar.",
                        'keywords' => ['skd', 'survei kebutuhan data', 'skd3313', 'kepuasan konsumen', 'evaluasi layanan', 'kuesioner skd'],
                        'source_title' => 'Survei Kebutuhan Data (SKD) BPS Karanganyar',
                        'source_url' => 'http://s.bps.go.id/skd3313',
                    ],
                    [
                        'title' => 'Pengalihan Percakapan ke Petugas Manusia (Human Handoff)',
                        'question' => 'Bagaimana jika chatbot tidak dapat menjawab pertanyaan saya?',
                        'answer' => "Jika Anda membutuhkan konsultasi mendalam atau kasus spesifik, Anda dapat mengklik tombol 'Hubungi Petugas' di ruang percakapan. Percakapan Anda akan diteruskan ke antrean petugas BPS Kabupaten Karanganyar untuk ditanggapi secara langsung pada jam operasional kerja (Senin–Jumat, 08.00–15.30 WIB). Anda juga dapat menghubungi WhatsApp PST resmi BPS Karanganyar di 0896-0593-3133.",
                        'keywords' => ['petugas', 'hubungi petugas', 'admin manusia', 'operator', 'cs', 'bantuan langsung', 'live chat', 'wa pst'],
                        'source_title' => 'Layanan Konsultasi Online PST BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                ]
            ],
            [
                'name' => 'Standar & Sinergi BPS RI Pusat (bps.go.id)',
                'slug' => 'standar-bps-ri-dan-sinergi-pusat',
                'description' => 'Hubungan sinergis BPS Pusat (bps.go.id) dan BPS Karanganyar, regulasi Satu Data Indonesia, klasifikasi baku KBLI/KBJI, metodologi nasional, dan ekosistem portal statistik nasional.',
                'articles' => [
                    [
                        'title' => 'Sinergi BPS RI Pusat (bps.go.id) dan BPS Kabupaten Karanganyar',
                        'question' => 'Apa hubungan antara portal BPS RI Pusat (bps.go.id) dengan BPS Kabupaten Karanganyar?',
                        'answer' => "Badan Pusat Statistik (BPS) adalah Lembaga Pemerintah Non-Kementerian (LPNK) yang memiliki struktur instansi vertikal dari pusat hingga daerah:\n\n1. Peran BPS RI Pusat (https://www.bps.go.id/id):\n- Berkedudukan di Jakarta sebagai regulator dan Pembina Data Statistik Nasional (UU No. 16 Tahun 1997 dan Perpres No. 39 Tahun 2019 tentang Satu Data Indonesia).\n- Bertanggung jawab menetapkan standar konsep dan metodologi survei/sensus, klasifikasi baku nasional (KBLI, KBJI), jadwal rilis kalender statistik (ARC), dan mengembangkan ekosistem portal digital nasional (WebAPI, PST Online, ROMANTIK, INDAH/EPSS, SIRIUS, SIMDASI, Allstats).\n\n2. Peran BPS Kabupaten Karanganyar (https://karanganyarkab.bps.go.id):\n- Unit kerja vertikal resmi BPS di tingkat kabupaten (Kode Wilayah/MFD: 3313).\n- Melaksanakan pencacahan lapangan langsung di 17 kecamatan, memvalidasi data statistik daerah, menerbitkan buku tahunan kompendium 'Kabupaten Karanganyar Dalam Angka (KDA)', serta memberikan pelayanan data tatap muka di loket PST Jl. Majapahit No. 11 B Cangakan.\n\nDengan sinergi ini, seluruh metodologi dan sistem BPS Karanganyar mengadopsi standar mutu nasional BPS RI, sementara seluruh angka data yang disajikan tetap murni spesifik dan akurat untuk Kabupaten Karanganyar.",
                        'keywords' => ['bps pusat', 'bps ri', 'bps.go.id', 'sinergi pusat', 'hubungan pusat', 'organisasi bps', 'kantor pusat', 'satu data indonesia', 'sdi', 'peran bps'],
                        'source_title' => 'Profil Kelembagaan BPS RI dan BPS Kabupaten Karanganyar',
                        'source_url' => 'https://www.bps.go.id/id',
                    ],
                    [
                        'title' => 'Standar Klasifikasi Baku BPS RI (KBLI, KBJI, SIRIUS) yang Digunakan di Karanganyar',
                        'question' => 'Apa itu KBLI dan KBJI yang diterbitkan BPS RI dan bagaimana penerapannya di Karanganyar?',
                        'answer' => "BPS RI Pusat menerbitkan klasifikasi baku statistik nasional sebagai rujukan Satu Data Indonesia:\n\n1. KBLI (Klasifikasi Baku Lapangan Usaha Indonesia):\n- Klasifikasi resmi untuk mengelompokkan aktivitas ekonomi/bisnis di Indonesia berdasarkan standar internasional ISIC (International Standard Industrial Classification).\n- Versi terbaru KBLI 2020 (Perka BPS No. 2 Tahun 2020) menjadi fondasi perizinan berusaha sistem OSS (Online Single Submission) serta penghitungan PDRB Kabupaten Karanganyar. Terdiri dari 21 Kategori (A-U), 88 Golongan Pokok (2 digit), hingga level Kelompok (5 digit).\n\n2. KBJI (Klasifikasi Baku Jabatan Indonesia):\n- Standar klasifikasi jabatan dan profesi pekerjaan berdasarkan standar ISCO-08 PBB yang digunakan BPS Karanganyar dalam Survei Angkatan Kerja Nasional (Sakernas) dan Sensus Penduduk.\n\n3. Portal SIRIUS BPS (https://sirius.bps.go.id/):\n- Sistem Informasi Rujukan Statistik BPS Pusat yang menyediakan pencarian interaktif kamus kode KBLI dan KBJI resmi.",
                        'keywords' => ['kbli', 'kbji', 'klasifikasi baku', 'lapangan usaha', 'jabatan', 'sirius', 'oss', 'standar nasional', 'isic', 'kbli 2020'],
                        'source_title' => 'Klasifikasi Baku Lapangan Usaha Indonesia (KBLI 2020) BPS RI',
                        'source_url' => 'https://www.bps.go.id/id',
                    ],
                    [
                        'title' => 'Komparasi Indikator Makro: Capaian Karanganyar terhadap Jawa Tengah dan Nasional',
                        'question' => 'Bagaimana perbandingan angka kemiskinan, IPM, dan ekonomi Karanganyar dengan Jawa Tengah dan Nasional?',
                        'answer' => "Berdasarkan rilis data resmi BPS 2026, Kabupaten Karanganyar mencatat kinerja indikator makro yang sangat positif dibandingkan rata-rata regional Jawa Tengah dan Nasional:\n\n1. Tingkat Kemiskinan (P0):\n- Karanganyar: 7,92% (~72,40 ribu jiwa)\n- Jawa Tengah: 10,47%\n- Nasional: 9,03%\n*Kesimpulan: Angka kemiskinan Karanganyar jauh lebih rendah (lebih baik) dibandingkan rata-rata provinsi dan nasional.\n\n2. Indeks Pembangunan Manusia (IPM):\n- Karanganyar: 78,15 poin (Kategori TINGGI)\n- Jawa Tengah: 73,39 poin\n- Nasional: 74,39 poin\n*Kesimpulan: Kualitas kesehatan, pendidikan, dan standar hidup Karanganyar berada di atas rata-rata provinsi maupun nasional.\n\n3. Laju Pertumbuhan Ekonomi (PDRB ADHK):\n- Karanganyar: 5,68%\n- Nasional: 5,05%\n*Kesimpulan: Pertumbuhan ekonomi Karanganyar lebih ekspansif ditopang sektor industri pengolahan, perdagangan, dan pertanian lumbung pangan.\n\n4. Tingkat Pengangguran Terbuka (TPT):\n- Karanganyar: 4,85%\n- Jawa Tengah: 5,13%\n*Kesimpulan: Penyerapan tenaga kerja di Karanganyar relatif solid dan stabil.",
                        'keywords' => ['komparasi', 'perbandingan', 'jawa tengah', 'nasional', 'angka kemiskinan', 'ipm komparasi', 'pertumbuhan ekonomi', 'capaian karanganyar'],
                        'source_title' => 'Indikator Strategis BPS Kabupaten Karanganyar dan BPS RI',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                    [
                        'title' => 'Struktur 3 Kelompok Besar dan 37 Subjek Statistik Resmi Portal BPS',
                        'question' => 'Apa saja 3 kelompok besar dan 37 subjek statistik resmi yang tersedia di portal BPS Karanganyar dan BPS RI?',
                        'answer' => "Portal BPS (karanganyarkab.bps.go.id dan bps.go.id) mengelompokkan seluruh tabel dan indikator statistik ke dalam 3 Kelompok Besar dan 37 Subjek Resmi:\n\n1. Statistik Demografi dan Sosial (11 Subjek):\n- Kependudukan dan Migrasi (Kode Subjek 519)\n- Tenaga Kerja\n- Pendidikan\n- Kesehatan\n- Konsumsi dan Pendapatan\n- Perlindungan Sosial\n- Pemukiman dan Perumahan\n- Hukum dan Kriminal\n- Budaya\n- Aktivitas Politik dan Komunitas Lainnya\n- Penggunaan Waktu\n\n2. Statistik Ekonomi (15 Subjek):\n- Statistik Makroekonomi\n- Neraca Ekonomi (PDRB)\n- Statistik Bisnis\n- Statistik sektoral\n- Keuangan Pemerintah, Fiskal dan Statistik Sektor Publik\n- Perdagangan Internasional dan Neraca Pembayaran\n- Harga-Harga (Inflasi/IHK)\n- Biaya Tenaga Kerja (UMK)\n- Ilmu Pengetahuan, Teknologi, dan Inovasi\n- Pertanian, Kehutanan, Perikanan (Padi, Palawija, Peternakan)\n- Energi\n- Pertambangan, Manufaktur, Konstruksi\n- Transportasi (Panjang & Kondisi Jalan)\n- Pariwisata\n- Perbankan, Asuransi dan Finansial\n\n3. Statistik Lingkungan Hidup dan Multi-domain (11 Subjek):\n- Lingkungan\n- Statistik Regional dan Statistik Area Kecil (17 Kecamatan/Desa)\n- Statistik dan Indikator Multi-Domain\n- Buku Tahunan dan Ringkasan Sejenis (KDA)\n- Kondisi Tempat Tinggal, Kemiskinan, dan Permasalahan Sosial Lintas Sektor\n- Gender dan Kelompok Populasi Khusus\n- Masyarakat Informasi\n- Globalisasi\n- Indikator Millenium Development Goals (MDGs)\n- Pembangunan Berkelanjutan (SDGs)\n- Kewiraswastaan",
                        'keywords' => ['subjek bps', '37 subjek', 'kategori subjek', 'taksonomi bps', 'subjek 519', 'kependudukan dan migrasi', 'statistik demografi', 'statistik ekonomi', 'statistik lingkungan hidup'],
                        'source_title' => 'Taksonomi Subjek Tabel Statistik BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    ],
                ]
            ]
        ];

        foreach ($categories as $catData) {
            $articles = $catData['articles'];
            unset($catData['articles']);

            $category = KnowledgeCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'is_active' => true,
                ]
            );

            foreach ($articles as $art) {
                KnowledgeArticle::updateOrCreate(
                    [
                        'knowledge_category_id' => $category->id,
                        'title' => $art['title']
                    ],
                    [
                        'question' => $art['question'],
                        'answer' => $art['answer'],
                        'keywords' => $art['keywords'],
                        'source_title' => $art['source_title'],
                        'source_url' => $art['source_url'],
                        'published_at' => now(),
                        'is_active' => true,
                        'created_by' => $admin->id,
                    ]
                );
            }
        }
    }
}
