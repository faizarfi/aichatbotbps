<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Str;

class ChatService
{
    public function __construct(
        protected KnowledgeSearchService $searchService,
        protected AiLlmService $aiService
    ) {}

    /**
     * Dapatkan atau buat sesi percakapan berdasarkan session ID / UUID pengunjung.
     */
    public function getOrCreateConversation(?string $sessionToken, ?string $visitorName = null): Conversation
    {
        if ($sessionToken) {
            $conversation = Conversation::where('visitor_session', $sessionToken)
                ->orWhere('public_id', $sessionToken)
                ->first();

            if ($conversation) {
                if ($visitorName && empty($conversation->visitor_name)) {
                    $conversation->update(['visitor_name' => $visitorName]);
                }
                return $conversation;
            }
        }

        $newSession = $sessionToken ?: Str::uuid()->toString();

        return Conversation::create([
            'public_id' => Str::uuid()->toString(),
            'channel' => 'web',
            'visitor_session' => $newSession,
            'visitor_name' => $visitorName ?: 'Pengunjung Web',
            'status' => 'bot',
            'last_message_at' => now(),
        ]);
    }

    /**
     * Proses pesan masuk dari pengunjung.
     */
    public function processVisitorMessage(Conversation $conversation, string $rawMessage, ?string $language = 'id'): array
    {
        $messageText = trim($rawMessage);

        // 1. Simpan pesan pengunjung ke database
        $visitorMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'visitor',
            'content' => $messageText,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        // 2. Jika percakapan sedang ditangani oleh petugas manusia, bot tidak menjawab otomatis
        if ($conversation->status === 'handled') {
            return [
                'conversation' => $conversation,
                'visitor_message' => $visitorMessage,
                'bot_message' => null,
                'reply' => null,
                'status' => 'handled',
                'officer_name' => $conversation->assignedOfficer?->name ?? 'Petugas BPS',
                'sources' => [],
                'quick_options' => [],
            ];
        }

        // 3. Deteksi Intent & Generate Jawaban Cerdas
        $replyData = $this->generateBotReply($conversation, $messageText, $language);
        $chart = $this->resolveChartForMessage($messageText, $replyData['reply']);

        // Jika ada grafik data riil dan belum tersemat di reply, sematkan format blok kode ```chart
        if ($chart && !str_contains($replyData['reply'], '```chart')) {
            $replyData['reply'] .= "\n\n```chart\n" . json_encode($chart, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n```";
        }

        // 4. Simpan pesan balasan bot ke database
        $botMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'content' => $replyData['reply'],
            'knowledge_sources' => $replyData['sources'] ?? null,
            'confidence' => $replyData['confidence'] ?? 1.0,
            'is_fallback' => $replyData['is_fallback'] ?? false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => $replyData['conversation_status'] ?? $conversation->status,
        ]);

        return [
            'conversation' => $conversation,
            'visitor_message' => $visitorMessage,
            'bot_message' => $botMessage,
            'reply' => $replyData['reply'],
            'chart' => $chart,
            'status' => $conversation->fresh()->status,
            'sources' => $replyData['sources'] ?? [],
            'quick_options' => !empty($replyData['quick_options']) ? $replyData['quick_options'] : ['Grafik Kemiskinan', 'Grafik IPM', 'Populasi Kecamatan', 'Jadwal PST'],
            'is_fallback' => $replyData['is_fallback'] ?? false,
        ];
    }

    /**
     * Resolusi dan sajikan visualisasi data deret waktu resmi BPS Karanganyar.
     */
    protected function resolveChartForMessage(string $message, string $reply): ?array
    {
        // 1. Cek apakah LLM sudah menghasilkan blok ```chart
        if (preg_match('/```chart\s*(\{.*?\})\s*```/s', $reply, $matches)) {
            $parsed = json_decode($matches[1], true);
            if (is_array($parsed) && isset($parsed['type'], $parsed['labels'], $parsed['data'])) {
                return $parsed;
            }
        }

        $lower = Str::lower($message);

        // 2. Deteksi Indikator Kemiskinan (Data Resmi Susenas BPS Karanganyar)
        if (Str::contains($lower, ['miskin', 'kemiskinan', 'garis kemiskinan', 'p0', 'mlarat']) && 
            (Str::contains($lower, ['tren', 'grafik', 'chart', 'diagram', 'perkembangan', 'deret', 'tahun', 'angka', 'data', 'berapa', 'turun']) || Str::contains($reply, ['7,92', '7.92', 'kemiskinan']))) {
            return [
                'type' => 'line',
                'title' => 'Tren Persentase Penduduk Miskin Kab. Karanganyar 2019–2026 (%)',
                'labels' => ['2019', '2020', '2021', '2022', '2023', '2024', '2025', '2026'],
                'data' => [9.87, 10.28, 10.68, 9.85, 9.23, 8.70, 8.24, 7.92],
                'unit' => '%',
                'description' => 'Sumber: Survei Sosial Ekonomi Nasional (Susenas) BPS Kabupaten Karanganyar'
            ];
        }

        // 3. Deteksi Indeks Pembangunan Manusia (IPM) Karanganyar
        if (Str::contains($lower, ['ipm', 'pembangunan manusia', 'hdi']) && 
            (Str::contains($lower, ['tren', 'grafik', 'chart', 'diagram', 'perkembangan', 'tahun', 'angka', 'berapa', 'komponen']) || Str::contains($reply, ['78,15', '78.15', 'IPM']))) {
            return [
                'type' => 'line',
                'title' => 'Perkembangan Indeks Pembangunan Manusia (IPM) Karanganyar 2020–2026',
                'labels' => ['2020', '2021', '2022', '2023', '2024', '2025', '2026'],
                'data' => [75.89, 76.10, 76.59, 77.34, 77.72, 78.01, 78.15],
                'unit' => 'Poin',
                'description' => 'Sumber: Indikator Pembangunan Manusia BPS Kabupaten Karanganyar 2026'
            ];
        }

        // 4. Deteksi Pertumbuhan Ekonomi / PDRB (Atas Dasar Harga Konstan)
        if (Str::contains($lower, ['pdrb', 'ekonomi', 'pertumbuhan ekonomi', 'lpe', 'gdp']) && 
            (Str::contains($lower, ['tren', 'grafik', 'chart', 'diagram', 'perkembangan', 'tahun', 'angka']) || Str::contains($reply, ['5,68', '5.68', 'PDRB']))) {
            return [
                'type' => 'bar',
                'title' => 'Laju Pertumbuhan Ekonomi (PDRB ADHK) Kab. Karanganyar (%)',
                'labels' => ['2020 (Pandemi)', '2021', '2022', '2023', '2024', '2025', '2026 (Rilis)'],
                'data' => [-1.74, 3.52, 5.48, 5.56, 5.68, 5.72, 5.68],
                'unit' => '%',
                'description' => 'Sumber: Rilis PDRB Atas Dasar Harga Konstan BPS Kabupaten Karanganyar'
            ];
        }

        // 5. Deteksi Populasi Antar-Kecamatan
        if ((Str::contains($lower, ['penduduk', 'populasi', 'warga', 'jiwa', 'cacahe', 'tiyang']) && Str::contains($lower, ['kecamatan', 'terbanyak', 'terpadat', 'grafik', 'chart', 'diagram', 'daftar', 'urutan', 'terbesar'])) ||
            (Str::contains($lower, ['grafik penduduk', 'diagram penduduk', 'chart penduduk', 'grafik kecamatan']))) {
            return [
                'type' => 'bar',
                'title' => '7 Kecamatan dengan Penduduk Terbanyak di Karanganyar 2026 (Jiwa)',
                'labels' => ['Karanganyar', 'Jaten', 'Gondangrejo', 'Colomadu', 'Mojogedang', 'Kebakkramat', 'Tawangmangu'],
                'data' => [89650, 87200, 85460, 76850, 69210, 67180, 48250],
                'unit' => 'Jiwa',
                'description' => 'Sumber: Kabupaten Karanganyar Dalam Angka 2026 (BPS Karanganyar)'
            ];
        }

        // 6. Deteksi Kepadatan Penduduk Tertinggi
        if (Str::contains($lower, ['kepadatan', 'padat']) && (Str::contains($lower, ['grafik', 'chart', 'diagram', 'perbandingan', 'kecamatan', 'tertinggi']))) {
            return [
                'type' => 'bar',
                'title' => 'Kepadatan Penduduk Tertinggi per Kecamatan (Jiwa/km²)',
                'labels' => ['Colomadu', 'Jaten', 'Tasikmadu', 'Karanganyar', 'Kebakkramat', 'Rata-rata Kab.'],
                'data' => [4914, 3413, 2406, 2083, 1843, 1245],
                'unit' => 'Jiwa/km²',
                'description' => 'Sumber: Kabupaten Karanganyar Dalam Angka 2026'
            ];
        }

        // 7. Deteksi Panjang Jalan Menurut Kondisi (Baik, Sedang, Rusak, Rusak Berat)
        if (Str::contains($lower, ['jalan', 'kondisi jalan', 'jalan rusak', 'kerusakan jalan', 'aspal', 'infrastruktur jalan']) &&
            (Str::contains($lower, ['grafik', 'chart', 'diagram', 'panjang', 'rusak', 'kondisi', 'angka', 'berapa', 'semuanya', 'data']) || Str::contains($reply, ['111,80', '111.80', '1.042,30', '1042.30', 'Kondisi Jalan', 'jalan rusak', 'rusak berat']))) {
            return [
                'type' => 'bar',
                'title' => 'Panjang Jalan Menurut Kondisi di Kab. Karanganyar 2026 (km)',
                'labels' => ['Baik', 'Sedang', 'Rusak', 'Rusak Berat'],
                'data' => [686.15, 189.45, 111.80, 54.90],
                'unit' => 'km',
                'description' => 'Sumber: Kabupaten Karanganyar Dalam Angka 2026, Bab 8 Transportasi, Tabel 8.1.3'
            ];
        }

        return null;
    }

    /**
     * Logika respon bot dengan AI LLM Gateway (9router / OpenRouter) & Basis Pengetahuan RAG.
     */
    protected function generateBotReply(Conversation $conversation, string $message, ?string $language = 'id'): array
    {
        $lower = Str::lower($message);

        // A. Permintaan Hubungi Petugas Manusia (Hanya jika benar-benar eksplisit meminta sambung ke staf)
        if (preg_match('/\b(hubungi|bicara\s+dengan|ngobrol\s+dengan|sambungkan\s+ke|chat\s+dengan|minta\s+bantuan)\s+(petugas|operator|admin|cs|manusia)\b/i', $lower) || in_array($lower, ['hubungi petugas', 'petugas', 'operator', 'cs', 'admin', 'bantuan manusia'], true)) {
            return [
                'reply' => "Baik, percakapan Anda dialihkan ke antrean petugas BPS Kabupaten Karanganyar. Petugas kami akan segera membalas di ruangan chat ini saat jam kerja operasional (Senin–Jumat, 08.00–15.30 WIB).",
                'sources' => [
                    ['title' => 'Jam Kerja Resmi BPS Karanganyar', 'url' => 'https://karanganyarkab.bps.go.id']
                ],
                'confidence' => 1.0,
                'is_fallback' => false,
                'conversation_status' => 'waiting',
                'quick_options' => [
                    'Tanyakan hal lain ke Bot',
                    'Buat Aduan Resmi',
                ],
            ];
        }

        // C. Cari data relevan di Basis Pengetahuan (Knowledge Search)
        $searchResult = $this->searchService->search($message);
        $articles = $searchResult['candidates']->all();
        if (empty($articles) && $searchResult['bestMatch']) {
            $articles = [$searchResult['bestMatch']];
        }

        $sources = collect($articles)
            ->filter(fn($a) => !empty($a->source_title))
            ->map(fn($a) => [
                'title' => $a->source_title,
                'url' => $a->source_url ?: 'https://karanganyarkab.bps.go.id',
            ])
            ->unique('title')
            ->values()
            ->all();

        // D. Coba panggil AI LLM Gateway (Google AI Studio Gemini) dengan RAG & Reasoning
        if ($this->aiService->isConfigured()) {
            $history = $conversation->messages()
                ->latest()
                ->take(6)
                ->get()
                ->reverse()
                ->map(fn($m) => ['sender_type' => $m->sender_type, 'content' => $m->content])
                ->values()
                ->all();

            $aiAnswer = $this->aiService->generateAnswer($message, $articles, $history, $language);

            if (!empty($aiAnswer)) {
                $smartSources = $this->resolveSmartSources($message, $aiAnswer, $sources, $language);

                return [
                    'reply' => $aiAnswer,
                    'sources' => $smartSources,
                    'confidence' => 0.98,
                    'is_fallback' => false,
                    'quick_options' => ['Kondisi Jalan Karanganyar', 'Grafik Kemiskinan', 'Grafik IPM', 'Hubungi Petugas'],
                ];
            }
        }

        // E. Fallback 1: Jika AI tidak merespon, gunakan artikel dari pencarian lokal HANYA jika confidence cukup tinggi (>= 0.4)
        if ($searchResult['bestMatch'] && ($searchResult['confidence'] ?? 0) >= 0.40) {
            $smartSources = $this->resolveSmartSources($message, $searchResult['bestMatch']->answer, $sources, $language);

            return [
                'reply' => $searchResult['bestMatch']->answer,
                'sources' => $smartSources,
                'confidence' => $searchResult['confidence'],
                'is_fallback' => false,
                'quick_options' => ['Cara memperoleh data', 'Hubungi Petugas', 'Layanan lainnya'],
            ];
        }

        // F. Fallback 2: Pesan informatif jika AI tidak merespon dan tidak ada artikel yang relevan
        $smartSources = $this->resolveSmartSources($message, '', $sources, $language);

        return [
            'reply' => "Mohon maaf, saat ini saya belum menemukan data yang persis sesuai untuk pertanyaan tersebut dalam basis data lokal.\n\n[icon:info] Topik Data Resmi BPS Karanganyar yang Tersedia:\n- Panjang jalan rusak dan kondisi jalan Karanganyar\n- Jumlah penduduk dan populasi 17 kecamatan\n- Angka kemiskinan dan Indeks Pembangunan Manusia (IPM)\n- Data pertanian, produksi beras/padi, dan industri\n- Jadwal dan tata cara permintaan data di kantor PST BPS\n\nSilakan ajukan pertanyaan dengan topik di atas atau klik Hubungi Petugas untuk terhubung langsung dengan petugas kami.",
            'sources' => $smartSources,
            'confidence' => 0.0,
            'is_fallback' => true,
            'quick_options' => ['Hubungi Petugas', 'Kondisi Jalan Karanganyar', 'Cara memperoleh data'],
        ];
    }

    /**
     * Pastikan semua tautan rujukan dokumen resmi mengarah langsung ke halaman subjek/tabel/publikasi spesifik
     * dan tidak pernah hanya mengarah ke beranda umum (homepage).
     */
    protected function resolveSmartSources(string $userMessage, string $aiReply, array $initialSources = [], ?string $language = 'id'): array
    {
        $haystack = mb_strtolower($userMessage . ' ' . $aiReply);
        $topicSources = [];

        // Prioritaskan topik dari pertanyaan pengguna terlebih dahulu, kemudian jawaban AI
        $queryText = mb_strtolower($userMessage);

        // 1. Data Pertanian, Padi & Beras
        if (str_contains($queryText, 'padi') || str_contains($queryText, 'tani') || str_contains($queryText, 'pertanian') || str_contains($queryText, 'panen') || str_contains($queryText, 'beras')
            || str_contains($haystack, 'produksi padi') || str_contains($haystack, 'sensus pertanian') || str_contains($haystack, 'luas panen')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Luas Panen dan Produksi Padi BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=padi',
            ];
            $topicSources[] = [
                'title' => 'Buku Publikasi: Kabupaten Karanganyar Dalam Angka (Bab 5 Pertanian)',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
            ];
        }

        // 2. Data Jalan & Infrastruktur
        if (str_contains($queryText, 'jalan') || str_contains($queryText, 'aspal') || str_contains($queryText, 'infrastruktur') || str_contains($queryText, 'rusak')
            || str_contains($haystack, 'kondisi jalan') || str_contains($haystack, 'panjang jalan')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Panjang Jalan Menurut Tingkat Kondisi Jalan Kab. Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=panjang+jalan',
            ];
            $topicSources[] = [
                'title' => 'Buku Publikasi: Kabupaten Karanganyar Dalam Angka (Bab 8 Transportasi)',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
            ];
        }

        // 3. Data Kemiskinan & Garis Kemiskinan
        if (str_contains($queryText, 'miskin') || str_contains($queryText, 'kemiskinan') || str_contains($haystack, 'penduduk miskin') || str_contains($haystack, 'garis kemiskinan')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Data Kemiskinan dan Garis Kemiskinan Kab. Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=kemiskinan',
            ];
            $topicSources[] = [
                'title' => 'Buku Publikasi: Kabupaten Karanganyar Dalam Angka (Bab 4 Sosial)',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
            ];
        }

        // 4. Indeks Pembangunan Manusia (IPM)
        if (str_contains($queryText, 'ipm') || str_contains($queryText, 'pembangunan manusia') || str_contains($haystack, 'indeks pembangunan manusia')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Indeks Pembangunan Manusia (IPM) BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=IPM',
            ];
            $topicSources[] = [
                'title' => 'Berita Resmi Statistik: Perkembangan IPM BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/pressrelease?keyword=indeks+pembangunan+manusia',
            ];
        }

        // 5. Kependudukan & Wilayah (Penduduk, Demografi, 17 Kecamatan)
        if (str_contains($queryText, 'penduduk') || str_contains($queryText, 'populasi') || str_contains($queryText, 'demografi') || str_contains($queryText, 'kecamatan') || str_contains($queryText, 'desa')
            || str_contains($haystack, 'jumlah penduduk') || str_contains($haystack, 'daftar kecamatan') || str_contains($haystack, '17 kecamatan')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Jumlah Penduduk Menurut Kecamatan di Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=penduduk',
            ];
            $topicSources[] = [
                'title' => 'Buku Publikasi: Kabupaten Karanganyar Dalam Angka (Bab 3 Kependudukan)',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
            ];
        }

        // 6. Ketenagakerjaan & Pengangguran (TPT)
        if (str_contains($haystack, 'penganggur') || str_contains($haystack, 'tpt') || str_contains($haystack, 'tenaga kerja') || str_contains($haystack, 'sakernas')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Tingkat Pengangguran Terbuka (TPT) BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=pengangguran',
            ];
        }

        // 7. PDRB & Pertumbuhan Ekonomi
        if (str_contains($haystack, 'pdrb') || str_contains($haystack, 'ekonomi') || str_contains($haystack, 'adhb') || str_contains($haystack, 'adhk')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: PDRB dan Pertumbuhan Ekonomi Kab. Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=PDRB',
            ];
        }

        // 8. Inflasi & IHK
        if (str_contains($haystack, 'inflasi') || str_contains($haystack, 'ihk') || str_contains($haystack, 'harga konsumen')) {
            $topicSources[] = [
                'title' => 'Tabel Statistik: Indeks Harga Konsumen dan Inflasi BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table?keyword=inflasi',
            ];
        }

        // 9. Pendataan Sosial & Kesejahteraan Rakyat
        if (str_contains($haystack, 'bansos') || str_contains($haystack, 'bantuan') || str_contains($haystack, 'dtks') || str_contains($haystack, 'regsosek')) {
            $topicSources[] = [
                'title' => 'Publikasi BPS: Analisis Indikator Kesejahteraan Rakyat Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
            ];
        }

        // 10. Prosedur Permohonan Data, Data Mikro, Wilkerstat / PST
        if (str_contains($haystack, 'minta data') || str_contains($haystack, 'unduh data') || str_contains($haystack, 'permohonan') || str_contains($haystack, 'pst')
            || str_contains($haystack, 'data mikro') || str_contains($haystack, 'wilkerstat') || str_contains($haystack, 'skripsi') || str_contains($haystack, 'raw data')) {
            $topicSources[] = [
                'title' => 'Standar Pelayanan Data & Publikasi BPS Kabupaten Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication',
            ];
        }

        // 11. Rekomendasi Kegiatan Statistik (ROMANTIK) & EPSS
        if (str_contains($haystack, 'romantik') || str_contains($haystack, 'rekomendasi') || str_contains($haystack, 'epss') || str_contains($haystack, 'ips') || str_contains($haystack, 'desa cantik')) {
            $topicSources[] = [
                'title' => 'Pedoman Rekomendasi Statistik Sektoral (ROMANTIK) BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/publication',
            ];
        }

        // 12. Tabel Dinamis & Query Builder BPS
        if (str_contains($haystack, 'query builder') || str_contains($haystack, 'tabel dinamis') || str_contains($haystack, 'custom tabel') || str_contains($haystack, 'kustomisasi tabel')) {
            $topicSources[] = [
                'title' => 'Tabel Dinamis / Query Builder Data BPS Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table',
            ];
        }

        // 13. Berita Resmi Statistik (BRS)
        if (str_contains($haystack, 'brs') || str_contains($haystack, 'berita resmi statistik') || str_contains($haystack, 'press release') || str_contains($haystack, 'siaran pers')) {
            $topicSources[] = [
                'title' => 'Berita Resmi Statistik (BRS) BPS Kabupaten Karanganyar',
                'url' => 'https://karanganyarkab.bps.go.id/id/pressrelease',
            ];
        }

        // 14. Survei Kebutuhan Data (SKD)
        if (str_contains($haystack, 'skd') || str_contains($haystack, 'survei kebutuhan data') || str_contains($haystack, 'kepuasan konsumen')) {
            $topicSources[] = [
                'title' => 'Survei Kebutuhan Data (SKD) BPS Kabupaten Karanganyar',
                'url' => 'http://s.bps.go.id/skd3313',
            ];
        }

        // 15. PPID & Keterbukaan Informasi Publik
        if (str_contains($haystack, 'ppid') || str_contains($haystack, 'informasi publik') || str_contains($haystack, 'keterbukaan informasi')) {
            $topicSources[] = [
                'title' => 'Portal PPID BPS Kabupaten Karanganyar',
                'url' => 'https://ppid.bps.go.id/?mfd=3313',
            ];
        }

        // 16. Pengaduan Layanan Resmi
        if (str_contains($haystack, 'pengaduan') || str_contains($haystack, 'aduan') || str_contains($haystack, 'keluhan') || str_contains($haystack, 'lapor')) {
            $topicSources[] = [
                'title' => 'Saluran Pengaduan Resmi BPS Kabupaten Karanganyar',
                'url' => 'http://s.bps.go.id/pengaduan3313',
            ];
        }

        // Bersihkan initial sources dari URL di luar ekosistem resmi bps.go.id
        $cleanedInitial = [];
        foreach ($initialSources as $s) {
            $url = $s['url'] ?? '';
            $title = $s['title'] ?? '';
            if (empty($url) || (!str_contains($url, 'bps.go.id') && !str_starts_with($url, '/')) || in_array(rtrim($url, '/'), ['https://karanganyarkab.bps.go.id', 'http://karanganyarkab.bps.go.id', ''])) {
                $url = 'https://karanganyarkab.bps.go.id/id/publication';
            }
            $cleanedInitial[] = ['title' => $title, 'url' => $url];
        }

        $merged = array_merge($topicSources, $cleanedInitial);

        if (empty($merged)) {
            $merged = [
                [
                    'title' => 'Buku Publikasi: Kabupaten Karanganyar Dalam Angka 2026',
                    'url' => 'https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html',
                ],
                [
                    'title' => 'Portal Tabel Statistik BPS Kabupaten Karanganyar',
                    'url' => 'https://karanganyarkab.bps.go.id/id/statistics-table',
                ],
            ];
        }

        $unique = [];
        $result = [];
        foreach ($merged as $item) {
            $url = $item['url'];
            if ($language === 'en') {
                $url = str_replace('/id/', '/en/', $url);
                $item['url'] = $url;
            }
            if (!isset($unique[$url])) {
                $unique[$url] = true;
                $result[] = $item;
            }
            if (count($result) >= 2) {
                break;
            }
        }

        return $result;
    }

    /**
     * Petugas membalas percakapan.
     */
    public function sendOfficerReply(Conversation $conversation, int $officerUserId, string $content): Message
    {
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'officer',
            'sender_user_id' => $officerUserId,
            'content' => trim($content),
        ]);

        $conversation->update([
            'status' => 'handled',
            'assigned_to' => $officerUserId,
            'last_message_at' => now(),
        ]);

        return $message;
    }
}
