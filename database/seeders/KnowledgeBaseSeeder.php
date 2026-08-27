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
                'description' => 'Informasi mengenai prosedur, tata cara permintaan data, dan konsultasi statistik di BPS Karanganyar.',
                'articles' => [
                    [
                        'title' => 'Cara Memperoleh Data Statistik BPS Karanganyar',
                        'question' => 'Bagaimana cara meminta atau memperoleh data statistik BPS Kabupaten Karanganyar?',
                        'answer' => "Masyarakat dan peneliti dapat memperoleh data statistik BPS Kabupaten Karanganyar melalui beberapa kanal:\n1. Website Resmi: Kunjungi karanganyarkab.bps.go.id untuk mengunduh publikasi dan tabel data secara gratis.\n2. Portal PST Online: Akses pst.bps.go.id untuk layanan permohonan data dan konsultasi secara daring.\n3. Datang Langsung ke PST: Kunjungi Pelayanan Statistik Terpadu di Kantor BPS Kabupaten Karanganyar pada jam kerja.\n4. Email Permintaan Data: Kirimkan surat permohonan resmi ke bps3313@bps.go.id dengan menyertakan identitas dan rincian data yang dibutuhkan.",
                        'keywords' => ['minta data', 'permohonan data', 'unduh data', 'cara dapat data', 'prosedur data', 'skripsi', 'penelitian'],
                        'source_title' => 'Standar Pelayanan BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Biaya dan Tarif Layanan Data Statistik',
                        'question' => 'Berapa biaya untuk mendapatkan data atau konsultasi statistik di BPS?',
                        'answer' => "Layanan data statistik dasar dan konsultasi di BPS Kabupaten Karanganyar TIDAK DIPUNGUT BIAYA (GRATIS) untuk publikasi softcopy dan data yang telah tersedia secara umum. Pembelian publikasi cetak tertentu dikenakan tarif PNBP (Penerimaan Negara Bukan Pajak) sesuai Peraturan Pemerintah yang berlaku dan disetorkan langsung ke kas negara.",
                        'keywords' => ['biaya', 'tarif', 'harga data', 'gratis', 'pnbp', 'bayar'],
                        'source_title' => 'PP Tarif PNBP BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Layanan Konsultasi dan Rekomendasi Statistik',
                        'question' => 'Bagaimana cara melakukan konsultasi statistik atau pengajuan rekomendasi kegiatan statistik?',
                        'answer' => "Konsultasi statistik dan pengajuan Rekomendasi Statistik (ROMANTIK) dapat dilakukan oleh OPD, akademisi, dan instansi dengan mengajukan rancangan survei atau konsultasi melalui PST BPS Karanganyar atau via website romantik.bps.go.id. Tim BPS akan memberikan telaah metodologi, konsep, definisi, dan penjaminan kualitas statistik sektoral.",
                        'keywords' => ['konsultasi', 'romantik', 'rekomendasi statistik', 'metodologi', 'survei', 'opd'],
                        'source_title' => 'Portal ROMANTIK BPS',
                        'source_url' => 'https://romantik.bps.go.id',
                    ],
                ]
            ],
            [
                'name' => 'Jadwal, Lokasi & Kontak',
                'slug' => 'jadwal-lokasi-kontak',
                'description' => 'Informasi jam kerja operasional, alamat kantor, telepon, dan kanal komunikasi resmi.',
                'articles' => [
                    [
                        'title' => 'Jadwal dan Jam Operasional Pelayanan',
                        'question' => 'Kapan jam buka dan hari operasional layanan BPS Kabupaten Karanganyar?',
                        'answer' => "Jadwal pelayanan tatap muka Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar adalah:\n- Senin s.d. Kamis: Pukul 08.00 - 15.30 WIB (Istirahat 12.00 - 13.00 WIB)\n- Jumat: Pukul 08.00 - 15.00 WIB (Istirahat 11.30 - 13.00 WIB)\n- Sabtu, Minggu, dan Hari Libur Nasional: Tutup / Libur.\nLayanan website dan chatbot ini dapat diakses 24 jam setiap hari.",
                        'keywords' => ['jadwal', 'jam buka', 'jam kerja', 'operasional', 'hari layanan', 'waktu buka'],
                        'source_title' => 'Jam Kerja Resmi BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Alamat dan Kontak Kantor BPS Karanganyar',
                        'question' => 'Di mana alamat kantor BPS Karanganyar dan bagaimana cara menghubunginya?',
                        'answer' => "Kantor BPS Kabupaten Karanganyar beralamat di:\nJl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57714.\n\nKontak Resmi:\n- Telepon: (0271) 495035\n- Email: bps3313@bps.go.id\n- Website: https://karanganyarkab.bps.go.id",
                        'keywords' => ['alamat', 'lokasi', 'kantor', 'nomor telepon', 'email', 'maps', 'posisi'],
                        'source_title' => 'Profil Kontak BPS Kabupaten Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                ]
            ],
            [
                'name' => 'Publikasi dan Data Populer',
                'slug' => 'publikasi-dan-data',
                'description' => 'Publikasi rutin seperti Karanganyar Dalam Angka, Sensus, Indikator Kemiskinan, Pertumbuhan Ekonomi, PDRB, dan Inflasi.',
                'articles' => [
                    [
                        'title' => 'Publikasi Kabupaten Karanganyar Dalam Angka (KDA)',
                        'question' => 'Apa itu Kabupaten Karanganyar Dalam Angka dan bagaimana cara mengunduhnya?',
                        'answer' => "Publikasi 'Kabupaten Karanganyar Dalam Angka' adalah publikasi komprehensif tahunan yang memuat data statistik geografi, pemerintahan, kependudukan, ketenagakerjaan, sosial, pertanian, industri, perdagangan, dan keuangan di Kabupaten Karanganyar. Publikasi ini terbit setiap akhir bulan Februari dan dapat diunduh gratis dalam format PDF di website resmi https://karanganyarkab.bps.go.id pada menu Publikasi.",
                        'keywords' => ['kda', 'karanganyar dalam angka', 'buku statistik', 'data tahunan', 'download pdf', 'publikasi'],
                        'source_title' => 'Publikasi Karanganyar Dalam Angka',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Data Kependudukan dan Sensus Penduduk Karanganyar',
                        'question' => 'Berapa jumlah penduduk Kabupaten Karanganyar terbaru?',
                        'answer' => "Berdasarkan publikasi resmi BPS Kabupaten Karanganyar (Kabupaten Karanganyar Dalam Angka 2024), jumlah penduduk Kabupaten Karanganyar tercatat sebanyak 953.696 jiwa yang tersebar di 17 kecamatan. Kecamatan dengan jumlah penduduk terbesar adalah Kecamatan Karanganyar (88.719 jiwa) dan Gondangrejo (84.342 jiwa). Data selengkapnya dapat diunduh gratis di website resmi https://karanganyarkab.bps.go.id.",
                        'keywords' => ['penduduk', 'jumlah penduduk', 'sensus penduduk', 'kependudukan', 'demografi', 'kecamatan', '953696'],
                        'source_title' => 'BPS Karanganyar Dalam Angka 2024',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Data Kemiskinan dan Garis Kemiskinan Karanganyar',
                        'question' => 'Berapa persentase dan jumlah penduduk miskin di Kabupaten Karanganyar?',
                        'answer' => "Berdasarkan data resmi BPS Kabupaten Karanganyar, persentase penduduk miskin di Kabupaten Karanganyar tercatat sebesar 8,48% atau sekitar 77,66 ribu orang. Angka ini menunjukkan tren penurunan kemiskinan yang konsisten dan lebih rendah dari rata-rata persentase kemiskinan Provinsi Jawa Tengah. Garis Kemiskinan (GK) tercatat di kisaran Rp 484.500,- per kapita per bulan.",
                        'keywords' => ['kemiskinan', 'penduduk miskin', 'garis kemiskinan', 'susenas', 'p0', 'poverty', '8.48', '8,48'],
                        'source_title' => 'Berita Resmi Statistik Kemiskinan BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Indeks Pembangunan Manusia (IPM) Karanganyar',
                        'question' => 'Berapa capaian Indeks Pembangunan Manusia (IPM) Kabupaten Karanganyar?',
                        'answer' => "Indeks Pembangunan Manusia (IPM) Kabupaten Karanganyar (Metode Baru BPS) tercatat sebesar 77,31 poin dan masuk dalam kategori 'TINGGI' (70 ≤ IPM < 80). Capaian ini ditopang oleh Angka Harapan Hidup (AHH) saat lahir 77,85 tahun, Harapan Lama Sekolah (HLS) 13,87 tahun, dan Rata-rata Lama Sekolah (RLS) 8,96 tahun.",
                        'keywords' => ['ipm', 'indeks pembangunan manusia', 'harapan hidup', 'lama sekolah', '77.31', '77,31', 'kualitas hidup'],
                        'source_title' => 'Indikator Pembangunan Manusia BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Data PDRB dan Pertumbuhan Ekonomi Karanganyar',
                        'question' => 'Berapa laju pertumbuhan ekonomi dan Produk Domestik Regional Bruto (PDRB) Karanganyar?',
                        'answer' => "Laju pertumbuhan ekonomi Kabupaten Karanganyar berdasarkan Produk Domestik Regional Bruto (PDRB) Atas Dasar Harga Konstan (ADHK) tercatat tumbuh sebesar 5,54%. Perekonomian Kabupaten Karanganyar didorong oleh sektor industri pengolahan, pertanian, perdagangan, konstruksi, serta pariwisata di kawasan Tawangmangu dan Ngargoyoso.",
                        'keywords' => ['pdrb', 'pertumbuhan ekonomi', 'adhb', 'adhk', 'lapangan usaha', 'ekonomi', '5.54', '5,54'],
                        'source_title' => 'Publikasi PDRB BPS Karanganyar',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
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
                        'answer' => "Untuk menyampaikan pengaduan atau keluhan:\n1. Buka menu 'Aduan' pada portal ini atau kunjungi /aduan.\n2. Isi formulir dengan nama, kontak yang dapat dihubungi, kategori aduan, dan uraian lengkap permasalahan (bisa sertakan lampiran dokumen/foto pendukung).\n3. Setelah dikirim, Anda akan menerima Nomor Tiket Aduan (misal: ADU-2026-000001).\n4. Anda dapat memantau status penanganan aduan kapan saja melalui menu 'Cek Status Aduan'.\n5. Petugas BPS akan memverifikasi dan menindaklanjuti dalam 1-3 hari kerja.",
                        'keywords' => ['aduan', 'pengaduan', 'keluhan', 'lapor', 'komplain', 'tiket', 'status tiket'],
                        'source_title' => 'SOP Pengelolaan Pengaduan Masyarakat BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
                    ],
                    [
                        'title' => 'Pengalihan Percakapan ke Petugas Manusia (Human Handoff)',
                        'question' => 'Bagaimana jika chatbot tidak dapat menjawab pertanyaan saya?',
                        'answer' => "Jika chatbot tidak menemukan informasi yang Anda cari atau Anda membutuhkan konsultasi mendalam, Anda dapat mengklik tombol 'Hubungi Petugas' di ruang percakapan. Percakapan Anda akan diteruskan ke antrean petugas BPS Kabupaten Karanganyar untuk ditanggapi secara langsung pada jam operasional kerja.",
                        'keywords' => ['petugas', 'hubungi petugas', 'admin manusia', 'operator', 'cs', 'bantuan langsung'],
                        'source_title' => 'Layanan Konsultasi Online BPS',
                        'source_url' => 'https://karanganyarkab.bps.go.id',
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
