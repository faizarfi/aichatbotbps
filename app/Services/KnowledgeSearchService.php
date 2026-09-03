<?php

namespace App\Services;

use App\Models\KnowledgeArticle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class KnowledgeSearchService
{
    /**
     * Peta Sinonim Kata Kunci Bahasa Indonesia untuk domain BPS
     */
    protected array $synonymMap = [
        'penduduk' => ['populasi', 'warga', 'jiwa', 'orang', 'masyarakat', 'demografi', 'sensus', 'kelahiran', 'cacahe', 'tiyang', 'wong', 'pendhudhuk'],
        'kemiskinan' => ['miskin', 'poverty', 'garis kemiskinan', 'bansos', 'dtks', 'desil', 'p0', 'kurang mampu', 'bantuan', 'mlarat', 'kirang mampu', 'boten gadhah', 'kebutuhan dasar', '2100 kkal'],
        'ipm' => ['indeks pembangunan manusia', 'hdi', 'harapan hidup', 'lama sekolah', 'rls', 'hls', 'kualitas sdm', 'pendidikan', 'gesang'],
        'pdrb' => ['ekonomi', 'pertumbuhan ekonomi', 'gdp', 'pendapatan daerah', 'adhk', 'adhb', 'lapangan usaha', 'pangupajiwa'],
        'ketenagakerjaan' => ['pengangguran', 'tpt', 'angkatan kerja', 'bekerja', 'tenaga kerja', 'sakernas', 'loker', 'nyambut damel', 'pedamelan', 'tpak'],
        'inflasi' => ['ihk', 'indeks harga konsumen', 'kenaikan harga', 'daya beli', 'deflasi', 'harga barang', 'rega', 'sbh'],
        'pertanian' => ['panen', 'luas panen', 'padi', 'beras', 'palawija', 'kebun', 'teh', 'durian', 'sayuran', 'peternakan', 'sabin', 'tetanen', 'sensus pertanian'],
        'jadwal' => ['jam buka', 'jam kerja', 'waktu layanan', 'operasional', 'hari kerja', 'buka jam berapa', 'tutup', 'dina', 'jam pira', 'kapan bukak'],
        'lokasi' => ['alamat', 'kantor', 'tempat', 'posisi', 'nomor telepon', 'email', 'kontak', 'maps', 'hubungi', 'pundi', 'kantore'],
        'biaya' => ['tarif', 'harga', 'gratis', 'bayar', 'pnbp', 'retribusi', 'bayare', 'piro', 'pinten', 'ragad', 'bebas biaya', 'rp 0', 'rp0'],
        'pst' => ['pelayanan statistik terpadu', 'layanan pst', 'loket pst', 'front office', 'standar pelayanan', 'maklumat pelayanan', 'pelayanan data', 'ruang pst', 'perpustakaan', 'konsultasi statistik'],
        'datamikro' => ['data mikro', 'raw data', 'data mentah', 'data individu', 'data rumah tangga', 'sampel susenas', 'sampel sakernas', 'skripsi', 'tesis', 'disertasi', 'penelitian', 'tugas akhir', 'analisis lanjut'],
        'wilkerstat' => ['peta wilkerstat', 'peta digital', 'wilayah kerja statistik', 'shapefile', 'shp', 'geojson', 'blok sensus', 'spasial', 'peta wilayah'],
        'romantik' => ['rekomendasi statistik', 'survei opd', 'metodologi', 'kegiatan statistik sektoral', 'rekomendasi kegiatan statistik', 'satu data', 'sdi', 'pembina data'],
        'epss' => ['evaluasi penyelenggaraan statistik sektoral', 'ips', 'indeks pembangunan statistik', 'pembinaan statistik'],
        'desacantik' => ['desa cantik', 'desa cinta statistik', 'kelurahan cantik', 'data desa', 'pembinaan statistik desa'],
        'pojokstatistik' => ['pojok statistik', 'kampus', 'universitas', 'perguruan tinggi', 'dosen', 'mahasiswa'],
        'sensus' => ['sensus penduduk', 'sensus pertanian', 'sensus ekonomi', 'sp', 'st', 'se', 'cacah jiwa'],
        'metodologi' => ['definisi', 'konsep', 'rumus', 'cara hitung', 'pengukuran', 'indikator', 'kuesioner', 'teknik sampling', 'margin of error'],
        'aduan' => ['keluhan', 'lapor', 'komplain', 'kritik', 'aspirasi', 'tiket aduan', 'wadul'],
        'kda' => ['karanganyar dalam angka', 'buku statistik', 'publikasi', 'tahunan', 'pdf'],
        'grafik' => ['chart', 'diagram', 'tren', 'perkembangan', 'grafik', 'visualisasi', 'kurva', 'tabel', 'statistik'],
        'jalan' => ['panjang jalan', 'jalan rusak', 'kondisi jalan', 'aspal', 'rusak', 'rusak berat', 'infrastruktur', 'jembatan', 'transportasi', 'marga'],
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

        $scored = $articles->map(fn($article) => [
            'article' => $article,
            'score' => $this->calculateScore($article, $cleanQuery, $queryWords, $expandedWords),
        ])->filter(fn($item) => $item['score'] > 0)
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

