<?php

namespace App\Services;

use App\Models\KnowledgeArticle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class KnowledgeSearchService
{
    public function __construct(
        protected ?AiLlmService $aiService = null
    ) {}

    /**
     * Taksonomi Subjek Statistik Resmi BPS (Portal BPS Karanganyar & BPS RI)
     * Mengelompokkan 3 Kategori Utama dan 37 Subjek Resmi.
     */
    public const BPS_CATEGORIES = [
        'demografi_sosial' => [
            'name' => 'Statistik Demografi dan Sosial',
            'icon' => 'lucide:users',
            'badge' => 'bg-blue-50 text-[#005b9f] border-blue-200',
            'subjects' => [
                'kependudukan_migrasi' => [
                    'name' => 'Kependudukan dan Migrasi',
                    'code' => '519',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['penduduk', 'populasi', 'migrasi', 'kelahiran', 'kematian', 'sex ratio', 'rasio jenis kelamin', 'kepadatan penduduk', 'sensus penduduk', 'sp2020', 'perpindahan penduduk', 'rumah tangga', 'demografi'],
                ],
                'tenaga_kerja' => [
                    'name' => 'Tenaga Kerja',
                    'code' => '520',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=520&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['tenaga kerja', 'ketenagakerjaan', 'tpt', 'pengangguran', 'angkatan kerja', 'bekerja', 'tpak', 'sakernas', 'pencari kerja', 'buruh', 'pekerja'],
                ],
                'pendidikan' => [
                    'name' => 'Pendidikan',
                    'code' => '521',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=521&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['pendidikan', 'sekolah', 'aps', 'apk', 'apm', 'melek huruf', 'rls', 'hls', 'lama sekolah', 'ijazah', 'literasi'],
                ],
                'kesehatan' => [
                    'name' => 'Kesehatan',
                    'code' => '522',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=522&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['kesehatan', 'harapan hidup', 'ahh', 'puskesmas', 'rumah sakit', 'tenaga medis', 'dokter', 'imunisasi', 'stunting', 'gizi'],
                ],
                'konsumsi_pendapatan' => [
                    'name' => 'Konsumsi dan Pendapatan',
                    'code' => '523',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=523&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['konsumsi', 'pendapatan', 'pengeluaran', 'pengeluaran per kapita', 'susenas', 'gini ratio', 'pola konsumsi', 'daya beli'],
                ],
                'perlindungan_sosial' => [
                    'name' => 'Perlindungan Sosial',
                    'code' => '524',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=524&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['perlindungan sosial', 'bansos', 'bantuan sosial', 'pkh', 'bpnt', 'subsidi', 'disabilitas', 'lansia', 'jaminan sosial'],
                ],
                'pemukiman_perumahan' => [
                    'name' => 'Pemukiman dan Perumahan',
                    'code' => '525',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=525&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['pemukiman', 'perumahan', 'tempat tinggal', 'sanitasi', 'air minum layak', 'rumah layak huni', 'atap', 'lantai'],
                ],
                'hukum_kriminal' => [
                    'name' => 'Hukum dan Kriminal',
                    'code' => '526',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=526&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['hukum', 'kriminal', 'kejahatan', 'tindak pidana', 'kamtibmas', 'keamanan', 'rasa aman'],
                ],
                'budaya' => [
                    'name' => 'Budaya',
                    'code' => '527',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=527&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['budaya', 'kebudayaan', 'cagar budaya', 'kesenian', 'bahasa daerah', 'tradisi', 'adat'],
                ],
                'aktivitas_politik_komunitas' => [
                    'name' => 'Aktivitas Politik dan Komunitas Lainnya',
                    'code' => '528',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=528&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['politik', 'pemilu', 'partisipasi politik', 'ormas', 'komunitas', 'gotong royong', 'demokrasi', 'idi'],
                ],
                'penggunaan_waktu' => [
                    'name' => 'Penggunaan Waktu',
                    'code' => '529',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=529&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['penggunaan waktu', 'waktu luang', 'alokasi waktu', 'time use survey', 'jam kerja harian'],
                ],
            ],
        ],
        'ekonomi' => [
            'name' => 'Statistik Ekonomi',
            'icon' => 'lucide:landmark',
            'badge' => 'bg-orange-50 text-[#ea580c] border-orange-200',
            'subjects' => [
                'statistik_makroekonomi' => [
                    'name' => 'Statistik Makroekonomi',
                    'code' => '530',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=530&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['makroekonomi', 'ekonomi makro', 'agregat ekonomi', 'stabilitas ekonomi'],
                ],
                'neraca_ekonomi' => [
                    'name' => 'Neraca Ekonomi',
                    'code' => '531',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=531&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['pdrb', 'neraca ekonomi', 'produk domestik regional bruto', 'pertumbuhan ekonomi', 'adhb', 'adhk', 'pdrb per kapita'],
                ],
                'statistik_bisnis' => [
                    'name' => 'Statistik Bisnis',
                    'code' => '532',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=532&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['bisnis', 'usaha', 'umkm', 'sensus ekonomi', 'se', 'direktori perusahaan', 'omzet bisnis'],
                ],
                'statistik_sektoral' => [
                    'name' => 'Statistik sektoral',
                    'code' => '533',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=533&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['sektoral', 'statistik sektoral', 'opd', 'romantik', 'satu data indonesia', 'sdi', 'epss'],
                ],
                'keuangan_pemerintah' => [
                    'name' => 'Keuangan Pemerintah, Fiskal dan Statistik Sektor Publik',
                    'code' => '534',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=534&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['keuangan pemerintah', 'apbd', 'pad', 'fiskal', 'belanja daerah', 'dau', 'dak', 'pendapatan asli daerah'],
                ],
                'perdagangan_internasional' => [
                    'name' => 'Perdagangan Internasional dan Neraca Pembayaran',
                    'code' => '535',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=535&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['ekspor', 'impor', 'perdagangan internasional', 'neraca perdagangan', 'komoditas ekspor'],
                ],
                'harga_harga' => [
                    'name' => 'Harga-Harga',
                    'code' => '536',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=536&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['harga', 'inflasi', 'ihk', 'indeks harga konsumen', 'sbh', 'deflasi', 'harga produsen', 'sembako'],
                ],
                'biaya_tenaga_kerja' => [
                    'name' => 'Biaya Tenaga Kerja',
                    'code' => '537',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=537&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['biaya tenaga kerja', 'umk', 'upah minimum', 'upah buruh', 'gaji', 'kompensasi'],
                ],
                'iptek_inovasi' => [
                    'name' => 'Ilmu Pengetahuan, Teknologi, dan Inovasi',
                    'code' => '538',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=538&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['iptek', 'inovasi', 'teknologi', 'riset', 'r&d', 'paten', 'digitalisasi'],
                ],
                'pertanian_kehutanan_perikanan' => [
                    'name' => 'Pertanian, Kehutanan, Perikanan',
                    'code' => '539',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=539&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['pertanian', 'kehutanan', 'perikanan', 'padi', 'panen', 'luas panen', 'beras', 'gabah', 'gkg', 'palawija', 'kebun', 'teh kemuning', 'peternakan', 'sapi', 'st2023', 'sensus pertanian', 'ksa'],
                ],
                'energi' => [
                    'name' => 'Energi',
                    'code' => '540',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=540&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['energi', 'listrik', 'pln', 'bbm', 'gas', 'ebt', 'daya listrik'],
                ],
                'tambang_manufaktur_konstruksi' => [
                    'name' => 'Pertambangan, Manufaktur, Konstruksi',
                    'code' => '541',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=541&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['manufaktur', 'industri', 'pabrik', 'konstruksi', 'tambang', 'galian c', 'bangunan'],
                ],
                'transportasi' => [
                    'name' => 'Transportasi',
                    'code' => '542',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=542&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['transportasi', 'jalan', 'panjang jalan', 'kondisi jalan', 'jalan rusak', 'aspal', 'kendaraan', 'lalu lintas'],
                ],
                'pariwisata' => [
                    'name' => 'Pariwisata',
                    'code' => '543',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=543&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['pariwisata', 'wisata', 'hotel', 'tpk', 'wisatawan', 'grojogan sewu', 'candi cetho', 'candi sukuh', 'lawu'],
                ],
                'perbankan_finansial' => [
                    'name' => 'Perbankan, Asuransi dan Finansial',
                    'code' => '544',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=544&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['perbankan', 'bank', 'asuransi', 'finansial', 'kredit', 'dpk', 'lembaga keuangan'],
                ],
            ],
        ],
        'lingkungan_multidomain' => [
            'name' => 'Statistik Lingkungan Hidup dan Multi-domain',
            'icon' => 'lucide:leaf',
            'badge' => 'bg-emerald-50 text-[#00a651] border-emerald-200',
            'subjects' => [
                'lingkungan' => [
                    'name' => 'Lingkungan',
                    'code' => '545',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=545&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['lingkungan', 'iklh', 'kualitas air', 'kualitas udara', 'hutan', 'sampah', 'iklim', 'curah hujan'],
                ],
                'statistik_regional_area_kecil' => [
                    'name' => 'Statistik Regional dan Statistik Area Kecil',
                    'code' => '546',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=546&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['regional', 'area kecil', '17 kecamatan', 'kecamatan', 'desa', 'kelurahan', 'desa cantik', 'podes', 'potensi desa'],
                ],
                'statistik_indikator_multidomain' => [
                    'name' => 'Statistik dan Indikator Multi-Domain',
                    'code' => '547',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=547&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['multi-domain', 'indikator komposit', 'lintas sektor', 'indikator strategis'],
                ],
                'buku_tahunan_ringkasan' => [
                    'name' => 'Buku Tahunan dan Ringkasan Sejenis',
                    'code' => '548',
                    'url' => 'https://karanganyarkab.bps.go.id/id/publication',
                    'keywords' => ['buku tahunan', 'karanganyar dalam angka', 'kda', 'kecamatan dalam angka', 'ringkasan statistik', 'publikasi tahunan'],
                ],
                'kemiskinan_permasalahan_sosial' => [
                    'name' => 'Kondisi Tempat Tinggal, Kemiskinan, dan Permasalahan Sosial Lintas Sektor',
                    'code' => '549',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=549&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['kemiskinan', 'penduduk miskin', 'garis kemiskinan', 'p0', 'p1', 'p2', 'kurang mampu', 'masalah sosial', 'kerentanan'],
                ],
                'gender_populasi_khusus' => [
                    'name' => 'Gender dan Kelompok Populasi Khusus',
                    'code' => '550',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=550&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['gender', 'ipg', 'idg', 'pemberdayaan gender', 'perempuan', 'disabilitas', 'kelompok rentan'],
                ],
                'masyarakat_informasi' => [
                    'name' => 'Masyarakat Informasi',
                    'code' => '551',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=551&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['masyarakat informasi', 'internet', 'tik', 'telekomunikasi', 'gadget', 'literasi informasi'],
                ],
                'globalisasi' => [
                    'name' => 'Globalisasi',
                    'code' => '552',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=552&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['globalisasi', 'investasi asing', 'rantai pasok', 'daya saing global'],
                ],
                'mdgs' => [
                    'name' => 'Indikator Millenium Development Goals (MDGs)',
                    'code' => '553',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=553&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['mdgs', 'millenium development goals', 'target milenium'],
                ],
                'pembangunan_berkelanjutan' => [
                    'name' => 'Pembangunan Berkelanjutan',
                    'code' => '554',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=554&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['sdgs', 'pembangunan berkelanjutan', 'tpb', 'agenda 2030', 'tujuan pembangunan berkelanjutan'],
                ],
                'kewiraswastaan' => [
                    'name' => 'Kewiraswastaan',
                    'code' => '555',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?subject=555&sortBy=date%2Ctitle&sortOrder=desc%2Casc',
                    'keywords' => ['kewiraswastaan', 'kewirausahaan', 'wirausaha', 'startup', 'rintisan usaha'],
                ],
            ],
        ],
    ];

    /**
     * Dapatkan daftar kategori dan subjek statistik resmi BPS.
     */
    public static function getBpsCategories(): array
    {
        return self::BPS_CATEGORIES;
    }

    /**
     * Peta Sinonim Kata Kunci Bahasa Indonesia untuk domain BPS
     */
    protected array $synonymMap = [
        'kependudukan dan migrasi' => ['penduduk', 'populasi', 'warga', 'jiwa', 'orang', 'masyarakat', 'demografi', 'sensus penduduk', 'sp2020', 'kelahiran', 'cacahe', 'tiyang', 'wong', 'pendhudhuk', 'migrasi', 'sex ratio'],
        'pertanian, kehutanan, perikanan' => ['pertanian', 'kehutanan', 'perikanan', 'panen', 'luas panen', 'padi', 'beras', 'palawija', 'kebun', 'teh kemuning', 'durian', 'sayuran', 'peternakan', 'sapi', 'sabin', 'tetanen', 'sensus pertanian', 'st2023', 'gabah', 'gkg'],
        'transportasi' => ['panjang jalan', 'kondisi jalan', 'jalan rusak', 'aspal', 'rusak berat', 'infrastruktur jalan', 'jembatan', 'transportasi', 'perhubungan', 'kendaraan'],
        'kondisi tempat tinggal, kemiskinan, dan permasalahan sosial lintas sektor' => ['kemiskinan', 'miskin', 'poverty', 'garis kemiskinan', 'bansos', 'dtks', 'desil', 'p0', 'p1', 'p2', 'kurang mampu', 'bantuan', 'mlarat', 'kirang mampu', 'boten gadhah', 'kebutuhan dasar', '2100 kkal', 'regsosek'],
        'tenaga kerja' => ['ketenagakerjaan', 'pengangguran', 'tpt', 'angkatan kerja', 'bekerja', 'tenaga kerja', 'sakernas', 'loker', 'nyambut damel', 'pedamelan', 'tpak', 'upah buruh'],
        'neraca ekonomi' => ['pdrb', 'ekonomi', 'pertumbuhan ekonomi', 'gdp', 'pendapatan daerah', 'adhk', 'adhb', 'lapangan usaha', 'pangupajiwa', 'pdrb per kapita'],
        'harga-harga' => ['inflasi', 'ihk', 'indeks harga konsumen', 'kenaikan harga', 'daya beli', 'deflasi', 'harga barang', 'rega', 'sbh', 'survei biaya hidup', 'sembako'],
        'pendidikan' => ['indeks pembangunan manusia', 'ipm', 'hdi', 'harapan hidup', 'lama sekolah', 'rls', 'hls', 'kualitas sdm', 'pendidikan', 'sekolah', 'aps', 'apk', 'apm'],
        'kesehatan' => ['angka harapan hidup', 'ahh', 'kesehatan', 'puskesmas', 'rumah sakit', 'tenaga medis', 'dokter', 'stunting', 'gizi'],
        'pariwisata' => ['pariwisata', 'wisata', 'destinasi', 'grojogan sewu', 'candi cetho', 'candi sukuh', 'lawu', 'tpk hotel', 'wisatawan'],
        'buku tahunan dan ringkasan sejenis' => ['karanganyar dalam angka', 'kda', 'kecamatan dalam angka', 'buku statistik', 'publikasi', 'tahunan', 'pdf'],
        'jadwal' => ['jam buka', 'jam kerja', 'waktu layanan', 'operasional', 'hari kerja', 'buka jam berapa', 'tutup', 'dina', 'jam pira', 'kapan bukak'],
        'lokasi' => ['alamat', 'kantor', 'tempat', 'posisi', 'nomor telepon', 'email', 'kontak', 'maps', 'hubungi', 'pundi', 'kantore', 'cangakan', 'majapahit', 'mfd', '3313', 'wa pst', 'whatsapp', '089605933133'],
        'biaya' => ['tarif', 'harga', 'gratis', 'bayar', 'pnbp', 'retribusi', 'bayare', 'piro', 'pinten', 'ragad', 'bebas biaya', 'rp 0', 'rp0', 'pp 86 tahun 2021', 'simponi', 'billing', 'kode billing'],
        'pst' => ['pelayanan statistik terpadu', 'layanan pst', 'loket pst', 'front office', 'standar pelayanan', 'maklumat pelayanan', 'pelayanan data', 'ruang pst', 'perpustakaan', 'konsultasi statistik', 'pst bps', 'pst.bps.go.id', 'reservasi pst', 'perka standar pelayanan'],
        'datamikro' => ['data mikro', 'raw data', 'data mentah', 'data individu', 'data rumah tangga', 'sampel susenas', 'sampel sakernas', 'skripsi', 'tesis', 'disertasi', 'penelitian', 'tugas akhir', 'analisis lanjut', 'komitmen penggunaan data'],
        'wilkerstat' => ['peta wilkerstat', 'peta digital', 'wilayah kerja statistik', 'shapefile', 'shp', 'geojson', 'blok sensus', 'spasial', 'peta wilayah'],
        'romantik' => ['rekomendasi statistik', 'survei opd', 'metodologi', 'kegiatan statistik sektoral', 'rekomendasi kegiatan statistik', 'satu data', 'sdi', 'pembina data', 'romantik.bps.go.id', 'surat rekomendasi'],
        'webapi' => ['webapi', 'api bps', 'rest api', 'developer bps', 'json data', 'integrasi data', 'webapi.bps.go.id', 'app id', 'api key'],
        'statinalab' => ['statinalab', 'statistics indonesia data lab', 'data lab', 'komputasi mikro', 'data enclave', 'secure lab', 'statinalab.bps.go.id'],
        'transdata' => ['transdata', 'pertukaran data', 'pks data', 'kerja sama data', 'kementerian lembaga', 'transdata bps'],
        'epss' => ['evaluasi penyelenggaraan statistik sektoral', 'ips', 'indeks pembangunan statistik', 'pembinaan statistik', 'indah'],
        'desacantik' => ['desa cantik', 'desa cinta statistik', 'kelurahan cantik', 'data desa', 'pembinaan statistik desa'],
        'pojokstatistik' => ['pojok statistik', 'kampus', 'universitas', 'perguruan tinggi', 'dosen', 'mahasiswa'],
        'sensus' => ['sensus penduduk', 'sensus pertanian', 'sensus ekonomi', 'sp', 'st', 'se', 'cacah jiwa'],
        'metodologi' => ['definisi', 'konsep', 'rumus', 'cara hitung', 'pengukuran', 'indikator', 'kuesioner', 'teknik sampling', 'margin of error', 'kbli', 'kbji'],
        'aduan' => ['keluhan', 'lapor', 'komplain', 'kritik', 'aspirasi', 'tiket aduan', 'wadul', 'pengaduan3313', 'sp4n lapor'],
        'brs' => ['berita resmi statistik', 'press release', 'rilis pers', 'jadwal rilis', 'arc', 'advance release calendar'],
        'tabeldinamis' => ['query builder', 'tabel dinamis', 'custom tabel', 'buat tabel', 'kustomisasi tabel', 'tabel statistik', 'tabel kustom'],
        'skd' => ['survei kebutuhan data', 'skd3313', 'kepuasan konsumen data', 'evaluasi data', 'kuesioner skd'],
        'ppid' => ['ppid', 'pejabat pengelola informasi', 'informasi publik', 'keterbukaan informasi', 'permohonan informasi publik'],
        'allstat' => ['allstat', 'allstats', 'aplikasi bps', 'mobile bps', 'unduh allstat', 'play store', 'app store'],
        'bpspusat' => ['bps pusat', 'bps ri', 'bps.go.id', 'pusat', 'portal nasional', 'badan pusat statistik ri', 'satu data indonesia', 'sdi', 'sirius', 'simdasi', 'standar nasional', 'regulasi statistik', 'perka bps', 'uu 16 tahun 1997', 'jakarta', 'komparasi nasional'],
        'klasifikasi' => ['kbli', 'kbji', 'klasifikasi baku', 'lapangan usaha', 'jabatan', 'kode kbli', 'kode kbji', 'sirius', 'standar klasifikasi', 'sna 2008'],
    ];

    /**
     * Cari artikel paling relevan berdasarkan teks pertanyaan.
     *
     * @param string $query
     * @param int $limit
     * @return array{bestMatch: ?KnowledgeArticle, candidates: Collection, confidence: float}
     */
    public function search(string $query, int $limit = 5): array
    {
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) {
            return [
                'bestMatch' => null,
                'candidates' => new Collection(),
                'confidence' => 0.0,
            ];
        }

        // Ambil semua artikel aktif dengan kategori aktif
        $articles = KnowledgeArticle::with('category')
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        if ($articles->isEmpty()) {
            return [
                'bestMatch' => null,
                'candidates' => new Collection(),
                'confidence' => 0.0,
            ];
        }

        $queryWords = $this->tokenize($cleanQuery);
        $expandedWords = $this->expandSynonyms($queryWords);

        // Coba generate vektor embedding query untuk Pencarian Semantik
        $queryVector = null;
        if ($this->aiService && $this->aiService->isConfigured()) {
            $queryVector = $this->aiService->generateEmbedding($cleanQuery);
        }

        $scored = $articles->map(function ($article) use ($cleanQuery, $queryWords, $expandedWords, $queryVector) {
            $keywordScore = $this->calculateScore($article, $cleanQuery, $queryWords, $expandedWords);
            $semanticScore = 0.0;

            // Hitung kemiripan kosinus (Cosine Similarity) jika artikel memiliki embedding
            if ($queryVector && !empty($article->embedding) && is_array($article->embedding)) {
                $cosine = AiLlmService::cosineSimilarity($queryVector, $article->embedding);
                if ($cosine > 0.40) {
                    // Skala kemiripan semantik: misal cosine 0.75 -> 75 poin
                    $semanticScore = $cosine * 100.0;
                }
            }

            // Hybrid Scoring: Kombinasi kata kunci + semantik
            $totalScore = $keywordScore + ($semanticScore * 0.85);

            return [
                'article' => $article,
                'score' => $totalScore,
                'keyword_score' => $keywordScore,
                'semantic_score' => $semanticScore,
            ];
        })->filter(fn($item) => $item['score'] > 0)
          ->sortByDesc('score')
          ->values();

        if ($scored->isEmpty()) {
            return ['bestMatch' => null, 'candidates' => new Collection(), 'confidence' => 0.0];
        }

        $topScore = $scored->first()['score'];

        // Ambang batas (threshold) dinaikkan menjadi 35 agar tidak salah mencocokkan artikel yang tidak relevan
        return [
            'bestMatch' => $topScore >= 35 ? $scored->first()['article'] : null,
            'candidates' => $scored->take($limit)->pluck('article'),
            'confidence' => min(1.0, round($topScore / 100, 4)),
        ];
    }

    /**
     * Hitung skor relevansi artikel terhadap query.
     */
    private function calculateScore(KnowledgeArticle $article, string $fullQuery, array $queryWords, array $expandedWords): float
    {
        $score = 0.0;
        $lowerQuery = Str::lower($fullQuery);
        $lowerTitle = Str::lower($article->title);
        $lowerQuestion = Str::lower($article->question);
        $lowerAnswer = Str::lower($article->answer);
        $keywords = array_map(fn($k) => Str::lower($k), (array) ($article->keywords ?? []));

        // 1. Exact match di title atau question
        if (str_contains($lowerTitle, $lowerQuery)) {
            $score += 70;
        }
        if (str_contains($lowerQuestion, $lowerQuery)) {
            $score += 60;
        }

        // 2. Keyword match
        foreach ($keywords as $kw) {
            if (!empty($kw)) {
                if ($kw === $lowerQuery) {
                    $score += 80;
                } elseif (str_contains($lowerQuery, $kw) || str_contains($kw, $lowerQuery)) {
                    $score += 40;
                }
            }
        }

        // 3. Direct Word Matching
        foreach ($queryWords as $word) {
            if (strlen($word) < 3) continue;

            if (str_contains($lowerTitle, $word)) {
                $score += 25;
            }
            if (str_contains($lowerQuestion, $word)) {
                $score += 20;
            }
            if (str_contains($lowerAnswer, $word)) {
                $score += 8;
            }

            foreach ($keywords as $kw) {
                if (str_contains($kw, $word)) {
                    $score += 20;
                }
            }
        }

        // 4. Synonym Expansion Matching
        foreach ($expandedWords as $syn) {
            if (strlen($syn) < 3) continue;
            if (str_contains($lowerTitle, $syn) || str_contains($lowerQuestion, $syn)) {
                $score += 15;
            }
            foreach ($keywords as $kw) {
                if (str_contains($kw, $syn)) {
                    $score += 12;
                }
            }
        }

        return $score;
    }

    /**
     * Ekspansi kata dengan sinonim domain BPS
     */
    private function expandSynonyms(array $words): array
    {
        $expanded = [];
        foreach ($words as $word) {
            $expanded[] = $word;
            foreach ($this->synonymMap as $key => $synonyms) {
                if ($word === $key || in_array($word, $synonyms, true) || str_contains($key, $word) || str_contains($word, $key)) {
                    $expanded[] = $key;
                    $expanded = array_merge($expanded, $synonyms);
                }
            }
        }
        return array_unique($expanded);
    }

    /**
     * Tokenisasi query menjadi array kata-kata bersih.
     */
    private function tokenize(string $text): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', Str::lower($text));
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

        // Abaikan stopwords umum dan kata generik domain agar tidak terjadi false-positive match
        $stopwords = [
            'yang', 'untuk', 'pada', 'ke', 'dari', 'di', 'dan', 'ini', 'itu', 'adalah',
            'apakah', 'bagaimana', 'bisa', 'tolong', 'mohon', 'ada', 'apa', 'saya', 'kami',
            'anda', 'dengan', 'atau', 'saja', 'pun', 'dong', 'sih', 'berikan', 'minta', 'kasih',
            'tampilkan', 'sebutkan', 'berapa', 'mana', 'karanganyar', 'kabupaten', 'bps', 'data',
            'tentang', 'terkait', 'semuanya', 'rujukan', 'halaman', 'bab'
        ];
        return array_values(array_diff($words, $stopwords));
    }
}

