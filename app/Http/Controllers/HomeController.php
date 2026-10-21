<?php

namespace App\Http\Controllers;

use App\Services\BpsPublicationService;

class HomeController extends Controller
{
    public function __construct(
        protected BpsPublicationService $publicationService
    ) {}

    /**
     * Tampilkan halaman beranda dengan katalog publikasi resmi BPS Karanganyar dan galeri infografis.
     */
    public function index()
    {
        $featuredPublications = $this->publicationService->getFeaturedPublications(6);
        $latestPublications = $this->publicationService->getLatestPublications(8);
        $publicationStats = $this->publicationService->getStatistics();

        // Koleksi Infografis & Indikator Kunci Statistik BPS Karanganyar
        $infographics = [
            [
                'id' => 'ipm-karanganyar',
                'title' => 'Indeks Pembangunan Manusia (IPM) Capai 78,15',
                'category' => 'sosial',
                'category_label' => 'Sosial & Kesejahteraan',
                'period' => 'Tahun Rilis 2026',
                'metric_value' => '78,15',
                'metric_unit' => 'Poin (Kategori TINGGI)',
                'metric_note' => '+0,48 Poin Pertumbuhan Tahunan',
                'icon' => 'lucide:award',
                'color_gradient' => 'from-blue-700 via-indigo-700 to-[#002b6a]',
                'badge_color' => 'bg-blue-100 text-[#003c80]',
                'points' => [
                    'Umur Harapan Hidup saat lahir (UHH) meningkat menjadi 78,12 tahun.',
                    'Harapan Lama Sekolah (HLS) mencapai 14,35 tahun (setara Diploma/Sarjana).',
                    'Rata-rata Lama Sekolah (RLS) penduduk usia 25+ mencapai 8,92 tahun.',
                    'Pengeluaran riil per kapita yang disesuaikan mencapai Rp 12,87 juta/tahun.',
                ],
                'sources' => 'Indeks Pembangunan Manusia (IPM) BPS Kabupaten Karanganyar (Metode Baru)',
                'ai_prompt' => 'Jelaskan capaian Indeks Pembangunan Manusia (IPM) Karanganyar 78,15 dan 4 dimensi indikator penyusunnya!',
            ],
            [
                'id' => 'pdrb-karanganyar',
                'title' => 'Pertumbuhan Ekonomi PDRB Karanganyar Tumbuh 5,68%',
                'category' => 'ekonomi',
                'category_label' => 'Ekonomi & Neraca',
                'period' => 'Harga Konstan 2026',
                'metric_value' => '5,68%',
                'metric_unit' => 'Laju Pertumbuhan',
                'metric_note' => 'Total ADHB Rp 44,8 Triliun',
                'icon' => 'lucide:trending-up',
                'color_gradient' => 'from-emerald-700 via-teal-700 to-[#043277]',
                'badge_color' => 'bg-emerald-100 text-emerald-800',
                'points' => [
                    'Struktur PDRB didominasi Industri Pengolahan (43,2% dari total perekonomian).',
                    'Sektor Pertanian, Kehutanan, & Perikanan menempati urutan kedua (14,8%).',
                    'Sektor Perdagangan Besar & Eceran serta Reparasi menyumbang 12,5%.',
                    'PDRB per kapita atas dasar harga berlaku menembus Rp 46,55 juta.',
                ],
                'sources' => 'Produk Domestik Regional Bruto (PDRB) Kabupaten Karanganyar Menurut Lapangan Usaha',
                'ai_prompt' => 'Bagaimana analisis laju pertumbuhan ekonomi PDRB Karanganyar 5,68% dan sektor industri pengolahan?',
            ],
            [
                'id' => 'kemiskinan-karanganyar',
                'title' => 'Persentase Kemiskinan Turun Menjadi 7,92%',
                'category' => 'sosial',
                'category_label' => 'Sosial & Kesejahteraan',
                'period' => 'Survei Susenas 2026',
                'metric_value' => '7,92%',
                'metric_unit' => 'Persentase (P0)',
                'metric_note' => 'Penduduk Miskin: 72,40 Ribu Jiwa',
                'icon' => 'lucide:trending-down',
                'color_gradient' => 'from-amber-600 via-orange-600 to-[#002b6a]',
                'badge_color' => 'bg-orange-100 text-orange-900',
                'points' => [
                    'Penurunan persentase penduduk miskin sebesar 0,38% poin dari survei sebelumnya.',
                    'Garis Kemiskinan (GK) tercatat sebesar Rp 478.500 per kapita per bulan.',
                    'Indeks Kedalaman Kemiskinan (P1) menurun ke angka 0,98 (kesejahteraan membaik).',
                    'Indeks Keparahan Kemiskinan (P2) stabil di angka 0,19.',
                ],
                'sources' => 'Profil Kemiskinan Kabupaten Karanganyar Hasil Survei Sosial Ekonomi Nasional (Susenas)',
                'ai_prompt' => 'Berapa angka kemiskinan Karanganyar 7,92%, batas garis kemiskinan, serta perbandingannya di Soloraya?',
            ],
            [
                'id' => 'padi-karanganyar',
                'title' => 'Produksi Padi Capai 298.500 Ton Gabah Kering',
                'category' => 'pertanian',
                'category_label' => 'Pertanian & Pangan',
                'period' => 'KSA Citra Satelit',
                'metric_value' => '298,5',
                'metric_unit' => 'Ribu Ton GKG',
                'metric_note' => 'Luas Panen Padi: 49.200 Hektar',
                'icon' => 'lucide:sprout',
                'color_gradient' => 'from-green-700 via-emerald-700 to-[#002b6a]',
                'badge_color' => 'bg-green-100 text-green-800',
                'points' => [
                    'Produktivitas tanaman padi sawah stabil tinggi di rata-rata 60,67 kuintal per hektar.',
                    'Surplus beras Karanganyar melebihi 145.000 ton untuk menyangga lumbung pangan Jawa Tengah.',
                    'Sentra produksi terbesar berada di Kecamatan Mojogedang, Kebakkramat, dan Gondangrejo.',
                    'Pendataan luas panen menggunakan teknologi Kerangka Sampel Area (KSA) BPS.',
                ],
                'sources' => 'Luas Panen dan Produksi Padi Kabupaten Karanganyar (Metode KSA BPS)',
                'ai_prompt' => 'Berapa luas panen padi dan produksi beras di Karanganyar serta kecamatan penghasil beras terbesar?',
            ],
            [
                'id' => 'tenaga-kerja-karanganyar',
                'title' => 'Tingkat Pengangguran Terbuka (TPT) di Level 4,21%',
                'category' => 'sosial',
                'category_label' => 'Sosial & Kesejahteraan',
                'period' => 'Survei Sakernas 2026',
                'metric_value' => '4,21%',
                'metric_unit' => 'TPT Karanganyar',
                'metric_note' => 'TPAK Capai 72,80%',
                'icon' => 'lucide:briefcase',
                'color_gradient' => 'from-sky-700 via-blue-800 to-[#002b6a]',
                'badge_color' => 'bg-sky-100 text-sky-900',
                'points' => [
                    'Jumlah angkatan kerja mencapai 524.600 jiwa dengan penyerapan lapangan kerja yang kondusif.',
                    'Tingkat Partisipasi Angkatan Kerja (TPAK) sebesar 72,80% mencerminkan produktivitas tinggi.',
                    'Pekerja sektor formal mencapai 58,40%, didorong sentra industri tekstil, makanan, dan manufaktur.',
                    'Angka pengangguran terbuka lebih rendah dari rata-rata provinsi Jawa Tengah.',
                ],
                'sources' => 'Indikator Pasar Tenaga Kerja Karanganyar (Survei Angkatan Kerja Nasional / Sakernas)',
                'ai_prompt' => 'Bagaimana kondisi ketenagakerjaan, tingkat pengangguran (TPT), dan TPAK di Karanganyar?',
            ],
            [
                'id' => 'sensus-ekonomi-2026',
                'title' => 'Sensus Ekonomi 2026 (SE2026) Seluruh Aktivitas Usaha',
                'category' => 'sensus',
                'category_label' => 'Sensus Ekonomi 2026',
                'period' => 'Agenda 10 Tahunan Nasional',
                'metric_value' => '17 Kec',
                'metric_unit' => 'Wilayah Pendataan',
                'metric_note' => 'Seluruh Usaha Non-Pertanian',
                'icon' => 'lucide:building-2',
                'color_gradient' => 'from-purple-800 via-indigo-900 to-[#002b6a]',
                'badge_color' => 'bg-purple-100 text-purple-900',
                'points' => [
                    'Pendataan lengkap unit usaha mikro, kecil, menengah (UMKM) hingga korporasi besar di Karanganyar.',
                    'Cakupan komprehensif sektor manufaktur, pariwisata lereng Lawu, hotel, kuliner, dan perdagangan.',
                    'Menyediakan basis data fundamental untuk perumusan kebijakan investasi dan pembangunan daerah.',
                    'Petugas BPS bertugas dengan identitas resmi dan surat tugas pencacahan terverifikasi.',
                ],
                'sources' => 'Undang-Undang Nomor 16 Tahun 1997 tentang Statistik & Pedoman SE2026 BPS RI',
                'ai_prompt' => 'Jelaskan jadwal, tujuan, dan jenis usaha yang didata pada Sensus Ekonomi 2026 di Karanganyar!',
            ],
        ];

        return view('home', compact(
            'featuredPublications',
            'latestPublications',
            'publicationStats',
            'infographics'
        ));
    }
}
