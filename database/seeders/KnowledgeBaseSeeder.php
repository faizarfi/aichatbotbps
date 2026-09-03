<?php

namespace Database\Seeders;

use App\Models\KnowledgeCategory;
use App\Models\KnowledgeArticle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'description' => 'Informasi mengenai prosedur, tata cara permintaan data, konsultasi statistik, dan pojok statistik di BPS Karanganyar.',
                'articles' => [
                    [
                        'title' => 'Cara Memperoleh Data Statistik BPS Karanganyar 2026',
                        'question' => 'Bagaimana cara meminta atau memperoleh data statistik BPS Kabupaten Karanganyar terbaru tahun 2026?',
                        'answer' => "Pelayanan data statistik BPS Kabupaten Karanganyar dapat diperoleh melalui:\n1. Layanan Chatbot AI Resmi ini: Anda dapat langsung menanyakan data statistik Karanganyar terbaru dan sistem akan menyajikan angka resmi, analisis, serta visualisasi grafik langsung di ruang chat ini.\n2. Layanan Konsultasi Tatap Muka: Pelayanan Statistik Terpadu (PST) di Kantor BPS Kabupaten Karanganyar pada jam kerja resmi (Senin-Jumat).\n3. Konsultasi Petugas: Klik tombol Hubungi Petugas di ruang percakapan ini untuk terhubung langsung dengan petugas statistik BPS Karanganyar.",
                        'keywords' => ['minta data', 'permohonan data', 'unduh data', 'cara dapat data', 'prosedur data', 'skripsi', 'penelitian', 'download', '2026'],
                        'source_title' => 'Standar Pelayanan BPS Kabupaten Karanganyar 2026',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Biaya dan Tarif Layanan Data Statistik',
                        'question' => 'Berapa biaya untuk mendapatkan data atau konsultasi statistik di BPS?',
                        'answer' => "Layanan data statistik dasar, unduh publikasi elektronik (softcopy PDF/Excel), dan konsultasi statistik di BPS Kabupaten Karanganyar adalah 100% GRATIS (Tanpa Biaya). Pembelian buku cetak edisi khusus tertentu dikenakan tarif PNBP resmi sesuai Peraturan Pemerintah dan disetor langsung ke Kas Negara.",
                        'keywords' => ['biaya', 'tarif', 'harga data', 'gratis', 'pnbp', 'bayar', 'retribusi'],
                        'source_title' => 'PP Tarif PNBP BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    ],
                    [
                        'title' => 'Layanan Konsultasi dan Rekomendasi Statistik (ROMANTIK)',
                        'question' => 'Bagaimana cara melakukan konsultasi statistik atau pengajuan rekomendasi kegiatan statistik?',
                        'answer' => "Konsultasi statistik dan pengajuan Rekomendasi Kegiatan Statistik (ROMANTIK) dapat diajukan oleh OPD Pemkab Karanganyar, instansi vertikal, maupun akademisi melalui website https://romantik.bps.go.id atau loket PST BPS Karanganyar. Tim BPS akan memberikan telaah metodologi, rancangan kuesioner, konsep definisi, serta penjaminan kualitas statistik sektoral.",
                        'keywords' => ['konsultasi', 'romantik', 'rekomendasi statistik', 'metodologi', 'survei', 'opd', 'sektoral'],
                        'source_title' => 'Portal ROMANTIK BPS',
                        'source_url' => 'https://romantik.bps.go.id',
                    ],
                    [
                        'title' => 'Program Pembinaan Desa Cantik (Desa Cinta Statistik)',
                        'question' => 'Apa itu program Desa Cantik di Kabupaten Karanganyar?',
                        'answer' => "Program Desa Cinta Statistik (Desa Cantik) merupakan program pembinaan BPS Karanganyar kepada aparatur desa/kelurahan untuk meningkatkan literasi dan tata kelola data statistik di tingkat desa. Tujuannya agar perencanaan pembangunan desa dan penyaluran program bantuan berbasis data yang valid, akurat, dan mutakhir.",
                        'keywords' => ['desa cantik', 'desa cinta statistik', 'kelurahan', 'data desa', 'pembinaan statistik'],
                        'source_title' => 'Program Desa Cantik BPS Karanganyar 2026',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication?keyword=desa+cantik',
                    ],
                ]
            ],
            [
                'name' => 'Jadwal, Lokasi & Kontak',
                'slug' => 'jadwal-lokasi-kontak',
                'description' => 'Informasi jam kerja operasional, alamat kantor, telepon, dan kanal komunikasi resmi.',
                'articles' => [
                    [
                        'title' => 'Jadwal dan Jam Operasional Pelayanan PST',
                        'question' => 'Kapan jam buka dan hari operasional layanan BPS Kabupaten Karanganyar?',
                        'answer' => "Jadwal pelayanan tatap muka Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar adalah:\n- Senin s.d. Kamis: Pukul 08.00 - 15.30 WIB (Istirahat 12.00 - 13.00 WIB)\n- Jumat: Pukul 08.00 - 15.00 WIB (Istirahat 11.30 - 13.00 WIB)\n- Sabtu, Minggu, dan Hari Libur Nasional: Tutup / Libur.\nLayanan portal website dan asisten chatbot ini dapat diakses 24 jam setiap hari.",
                        'keywords' => ['jadwal', 'jam buka', 'jam kerja', 'operasional', 'hari layanan', 'waktu buka', 'hari apa'],
                        'source_title' => 'Jam Operasional PST BPS Karanganyar 2026',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/profil',
                    ],
                    [
                        'title' => 'Alamat dan Kontak Kantor BPS Karanganyar',
                        'question' => 'Di mana alamat kantor BPS Karanganyar dan bagaimana cara menghubunginya?',
                        'answer' => "Kantor BPS Kabupaten Karanganyar beralamat di:\nJl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57714.\n\nKontak Resmi:\n- Telepon: (0271) 495035\n- Email: bps3313@bps.go.id\n- Website: https://karanganyarkab.bps.go.id",
                        'keywords' => ['alamat', 'lokasi', 'kantor', 'nomor telepon', 'email', 'maps', 'posisi', 'hubungi'],
                        'source_title' => 'Profil Kontak BPS Kabupaten Karanganyar 2026',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/profil',
                    ],
                ]
            ],
            [
                'name' => 'Publikasi dan Data Populer (Rilis 2026)',
                'slug' => 'publikasi-dan-data',
                'description' => 'Publikasi rutin seperti Karanganyar Dalam Angka 2026, Sensus, Indikator Kemiskinan, Pertumbuhan Ekonomi, PDRB, dan Inflasi.',
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
                        'title' => 'Data Kependudukan Kabupaten Karanganyar 2026',
                        'question' => 'Berapa jumlah penduduk Kabupaten Karanganyar terbaru tahun 2026?',
                        'answer' => "Berdasarkan rilis data resmi BPS Kabupaten Karanganyar (Kabupaten Karanganyar Dalam Angka 2026), jumlah penduduk Kabupaten Karanganyar tercatat sebanyak 962.480 jiwa yang tersebar di 17 kecamatan.\n\nKecamatan dengan penduduk terbanyak adalah:\n1. Kecamatan Karanganyar: 89.650 jiwa\n2. Kecamatan Jaten: 87.200 jiwa\n3. Kecamatan Gondangrejo: 85.460 jiwa\n4. Kecamatan Colomadu: 76.850 jiwa\nSedangkan kecamatan dengan penduduk paling sedikit adalah Kecamatan Jenawi (26.380 jiwa).",
                        'keywords' => ['penduduk', 'jumlah penduduk', 'kependudukan', 'demografi', 'kecamatan', '962480', 'populasi', '2026'],
                        'source_title' => 'Tabel Statistik Kependudukan BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=penduduk',
                    ],
                    [
                        'title' => 'Data Kemiskinan dan Garis Kemiskinan Karanganyar 2026',
                        'question' => 'Berapa persentase dan jumlah penduduk miskin di Kabupaten Karanganyar tahun 2026?',
                        'answer' => "Berdasarkan data resmi BPS Kabupaten Karanganyar rilis 2026 (Survei Sosial Ekonomi Nasional / Susenas), persentase penduduk miskin di Kabupaten Karanganyar tercatat sebesar 7,92% atau sekitar 72,40 ribu orang. Angka kemiskinan di Karanganyar terus menunjukkan tren penurunan konsisten dan berada di bawah rata-rata Provinsi Jawa Tengah. Garis Kemiskinan (GK) tercatat di kisaran Rp 521.800,- per kapita per bulan.",
                        'keywords' => ['kemiskinan', 'penduduk miskin', 'garis kemiskinan', 'susenas', 'p0', 'poverty', '7.92', '7,92', '2026'],
                        'source_title' => 'Tabel Statistik Kemiskinan BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=kemiskinan',
                    ],
                    [
                        'title' => 'Indeks Pembangunan Manusia (IPM) Karanganyar 2026',
                        'question' => 'Berapa capaian Indeks Pembangunan Manusia (IPM) Kabupaten Karanganyar tahun 2026?',
                        'answer' => "Indeks Pembangunan Manusia (IPM) Kabupaten Karanganyar tahun 2026 tercatat sebesar 78,15 poin dan berada dalam kategori 'TINGGI' (70 ≤ IPM < 80).\n\nKomponen pembentuk IPM 2026:\n- Umur Harapan Hidup saat lahir (AHH): 78,12 tahun\n- Harapan Lama Sekolah (HLS): 14,02 tahun\n- Rata-rata Lama Sekolah (RLS): 9,15 tahun\n- Pengeluaran Riil per Kapita disesuaikan: Rp 13,42 juta/tahun.",
                        'keywords' => ['ipm', 'indeks pembangunan manusia', 'harapan hidup', 'lama sekolah', '78.15', '78,15', 'kualitas hidup', '2026'],
                        'source_title' => 'Tabel Statistik Indikator Pembangunan Manusia (IPM)',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=IPM',
                    ],
                    [
                        'title' => 'Data PDRB dan Pertumbuhan Ekonomi Karanganyar 2026',
                        'question' => 'Berapa laju pertumbuhan ekonomi dan Produk Domestik Regional Bruto (PDRB) Karanganyar 2026?',
                        'answer' => "Laju pertumbuhan ekonomi Kabupaten Karanganyar berdasarkan Produk Domestik Regional Bruto (PDRB) Atas Dasar Harga Konstan (ADHK) tahun 2026 tumbuh sebesar 5,68%. Perekonomian Kabupaten Karanganyar didorong oleh sektor industri pengolahan manufaktur, pertanian tanaman pangan, perdagangan, konstruksi, serta sektor pariwisata di kawasan lereng Gunung Lawu (Tawangmangu & Ngargoyoso).",
                        'keywords' => ['pdrb', 'pertumbuhan ekonomi', 'adhb', 'adhk', 'lapangan usaha', 'ekonomi', '5.68', '5,68', '2026'],
                        'source_title' => 'Tabel Statistik PDRB BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=PDRB',
                    ],
                    [
                        'title' => 'Data Ketenagakerjaan dan Pengangguran (TPT) Karanganyar 2026',
                        'question' => 'Berapa tingkat pengangguran terbuka (TPT) dan kondisi ketenagakerjaan di Karanganyar 2026?',
                        'answer' => "Berdasarkan Survei Angkatan Kerja Nasional (Sakernas) BPS tahun 2026, Tingkat Pengangguran Terbuka (TPT) Kabupaten Karanganyar tercatat sebesar 4,85%. Tingkat Partisipasi Angkatan Kerja (TPAK) berada di kisaran 72,40%, didukung oleh penyerapan tenaga kerja di sektor industri tekstil/manufaktur (Jaten, Kebakkramat, Gondangrejo) dan sektor jasa pariwisata serta perdagangan.",
                        'keywords' => ['ketenagakerjaan', 'pengangguran', 'tpt', 'sakernas', 'angkatan kerja', 'tpak', '4.85', '4,85', '2026'],
                        'source_title' => 'Tabel Statistik Ketenagakerjaan BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=pengangguran',
                    ],
                    [
                        'title' => 'Indeks Harga Konsumen (IHK) dan Inflasi Karanganyar 2026',
                        'question' => 'Berapa angka inflasi dan Indeks Harga Konsumen (IHK) Karanganyar 2026?',
                        'answer' => "Indeks Harga Konsumen (IHK) Kabupaten Karanganyar pada tahun 2026 tercatat sebesar 125,85 dengan laju inflasi tahunan (year-on-year) terkendali stabil di angka 2,82%. Pengendalian inflasi didukung oleh stabilitas harga bahan pangan pokok, kelancaran distribusi beras, serta koordinasi Tim Pengendali Inflasi Daerah (TPID).",
                        'keywords' => ['inflasi', 'ihk', 'harga konsumen', 'daya beli', 'deflasi', 'tpid', '125.85', '2026'],
                        'source_title' => 'Tabel Statistik IHK dan Inflasi BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=inflasi',
                    ],
                    [
                        'title' => 'Data Pertanian dan Produksi Padi Karanganyar 2026',
                        'question' => 'Bagaimana data produksi padi dan sektor pertanian di Karanganyar 2026?',
                        'answer' => "Kabupaten Karanganyar merupakan salah satu lumbung pangan utama di wilayah Soloraya. Pada tahun 2026, luas panen padi tercatat lebih dari 51.200 hektar dengan total produksi padi gabah kering giling (GKG) melampaui 285.000 ton. Sentra produksi padi terbesar berada di Kecamatan Mojogedang, Jumapolo, Tasikmadu, dan Kebakkramat.",
                        'keywords' => ['pertanian', 'padi', 'beras', 'panen', 'luas panen', 'gkg', 'lumbung padi', '2026'],
                        'source_title' => 'Tabel Statistik Pertanian Padi BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=padi',
                    ],
                    [
                        'title' => 'Data Panjang Jalan dan Kondisi Jalan Rusak Kabupaten Karanganyar 2026',
                        'question' => 'Berapa panjang jalan rusak dan kondisi jalan di Kabupaten Karanganyar tahun 2026?',
                        'answer' => "Berdasarkan rilis resmi BPS Kabupaten Karanganyar (Kabupaten Karanganyar Dalam Angka 2026, Bab 8 Transportasi dan Komunikasi, Tabel 8.1.3), total panjang jalan kabupaten di Kabupaten Karanganyar tercatat sepanjang 1.042,30 km.\n\nRincian kondisi jalan kabupaten:\n- Kondisi Baik: 686,15 km (65,83%)\n- Kondisi Sedang: 189,45 km (18,18%)\n- Kondisi Rusak: 111,80 km (10,73%)\n- Kondisi Rusak Berat: 54,90 km (5,26%)\nTotal panjang jalan rusak (rusak + rusak berat) adalah 166,70 km atau sekitar 15,99% dari total jalan kabupaten.\n\nRincian jenis permukaan jalan:\n- Permukaan Aspal: 988,50 km (94,84%)\n- Permukaan Kerikil: 38,20 km (3,66%)\n- Tanah/Lainnya: 15,60 km (1,50%)\n\n📌 Rujukan Resmi:\nPublikasi BPS: Kabupaten Karanganyar Dalam Angka 2026\nBab 8: Transportasi dan Komunikasi\nTabel 8.1.3: Panjang Jalan Menurut Tingkat Kerusakan/Kondisi Jalan\nWebsite Resmi: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=panjang+jalan",
                        'keywords' => ['jalan', 'jalan rusak', 'panjang jalan', 'kondisi jalan', 'rusak berat', 'aspal', 'kerikil', 'infrastruktur', 'transportasi', '1042', '111.80', '2026'],
                        'source_title' => 'Tabel Statistik: Panjang Jalan Menurut Tingkat Kondisi Kab. Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=panjang+jalan',
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
                        'title' => 'Tugas dan Peran Statistik Sosial Ekonomi BPS Karanganyar',
                        'question' => 'Apa peran BPS Karanganyar dalam pendataan sosial ekonomi masyarakat (Regsosek & Susenas)?',
                        'answer' => "Badan Pusat Statistik (BPS) Kabupaten Karanganyar bertugas mengumpulkan data statistik dasar melalui pendataan lapangan yang objektif (seperti Registrasi Sosial Ekonomi / Regsosek dan Susenas) untuk memotret kondisi riil masyarakat. BPS berperan murni sebagai lembaga independen penyedia data statistik resmi dan tidak bertindak sebagai penentu kebijakan penerimaan bantuan atau program bantuan tertentu.",
                        'keywords' => ['bansos', 'bantuan sosial', 'regsosek', 'susenas', 'pendataan', 'profil sosial', 'kesejahteraan'],
                        'source_title' => 'Publikasi BPS: Indikator Kesejahteraan Rakyat Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
                    ],
                ]
            ],
            [
                'name' => 'Pengaduan dan Bantuan',
                'slug' => 'pengaduan-dan-bantuan',
                'description' => 'Mekanisme pelaporan aduan layanan, pengawasan kode etik, dan pelacakan tiket aduan.',
                'articles' => [
                    [
                        'title' => 'Prosedur Penyampaian dan Penanganan Aduan Layanan',
                        'question' => 'Bagaimana alur dan prosedur pengaduan pelayanan di BPS Karanganyar?',
                        'answer' => "Untuk menyampaikan pengaduan atau keluhan:\n1. Buka menu 'Aduan' pada portal ini atau kunjungi /aduan.\n2. Isi formulir dengan nama, kontak yang dapat dihubungi, kategori aduan, dan uraian lengkap permasalahan (bisa sertakan lampiran dokumen/foto pendukung).\n3. Setelah dikirim, Anda akan menerima Nomor Tiket Aduan resmi (misal: ADU-2026-000001).\n4. Anda dapat memantau status penanganan aduan kapan saja melalui menu 'Cek Status Aduan'.\n5. Petugas BPS akan memverifikasi dan menindaklanjuti dalam 1-3 hari kerja.",
                        'keywords' => ['aduan', 'pengaduan', 'keluhan', 'lapor', 'komplain', 'tiket', 'status tiket', '2026'],
                        'source_title' => 'Formulir Pengaduan Pelayanan BPS Karanganyar',
                        'source_url' => '/aduan',
                    ],
                    [
                        'title' => 'Pengalihan Percakapan ke Petugas Manusia (Human Handoff)',
                        'question' => 'Bagaimana jika chatbot tidak dapat menjawab pertanyaan saya?',
                        'answer' => "Jika chatbot tidak menemukan informasi yang Anda cari atau Anda membutuhkan konsultasi mendalam, Anda dapat mengklik tombol 'Hubungi Petugas' di ruang percakapan. Percakapan Anda akan diteruskan ke antrean petugas BPS Kabupaten Karanganyar untuk ditanggapi secara langsung pada jam operasional kerja (Senin–Jumat, 08.00–15.30 WIB).",
                        'keywords' => ['petugas', 'hubungi petugas', 'admin manusia', 'operator', 'cs', 'bantuan langsung', 'live chat'],
                        'source_title' => 'Layanan Konsultasi Online PST BPS Karanganyar 2026',
                        'source_url' => 'https://pst.bps.go.id',
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

