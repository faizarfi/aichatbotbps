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
    public function processVisitorMessage(Conversation $conversation, string $rawMessage): array
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
        $replyData = $this->generateBotReply($conversation, $messageText);
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

        return null;
    }

    /**
     * Logika respon bot dengan AI LLM Gateway (9router / OpenRouter) & Basis Pengetahuan RAG.
     */
    protected function generateBotReply(Conversation $conversation, string $message): array
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

        // D. Coba panggil AI LLM Gateway (9router / OpenRouter) dengan RAG
        if ($this->aiService->isConfigured()) {
            $history = $conversation->messages()
                ->latest()
                ->take(6)
                ->get()
                ->reverse()
                ->map(fn($m) => ['sender_type' => $m->sender_type, 'content' => $m->content])
                ->values()
                ->all();

            $aiAnswer = $this->aiService->generateAnswer($message, $articles, $history);

            if (!empty($aiAnswer)) {
                return [
                    'reply' => $aiAnswer,
                    'sources' => $sources ?: [['title' => 'Publikasi BPS Karanganyar Dalam Angka 2026', 'url' => 'https://karanganyarkab.bps.go.id']],
                    'confidence' => 0.95,
                    'is_fallback' => false,
                    'quick_options' => ['Cara memperoleh data', 'Jadwal layanan PST', 'Hubungi Petugas'],
                ];
            }
        }

        // E. Fallback 1: Jika AI tidak merespon, gunakan artikel dari pencarian lokal jika ada
        if ($searchResult['bestMatch']) {
            return [
                'reply' => $searchResult['bestMatch']->answer,
                'sources' => $sources ?: [['title' => 'BPS Kabupaten Karanganyar Dalam Angka 2026', 'url' => 'https://karanganyarkab.bps.go.id']],
                'confidence' => $searchResult['confidence'],
                'is_fallback' => false,
                'quick_options' => ['Cara memperoleh data', 'Hubungi Petugas', 'Layanan lainnya'],
            ];
        }

        // F. Fallback 2: Pesan cerdas jika tidak ditemukan artikel spesifik
        return [
            'reply' => "Mohon maaf, saya belum menemukan rujukan yang sesuai untuk pertanyaan tersebut dalam basis data saat ini.\n\n**Saran Topik Data:**\n- *Jumlah penduduk Karanganyar 2026*\n- *Angka kemiskinan atau IPM Karanganyar*\n- *Data 17 Kecamatan (misal: Tawangmangu, Colomadu)*\n- *Jadwal buka dan jam layanan kantor PST BPS*\n\nAnda juga dapat memilih tombol **Hubungi Petugas** untuk berkonsultasi langsung dengan petugas kami.",
            'sources' => [['title' => 'Portal Resmi BPS Karanganyar 2026', 'url' => 'https://karanganyarkab.bps.go.id']],
            'confidence' => 0.0,
            'is_fallback' => true,
            'quick_options' => ['Hubungi Petugas', 'Cara memperoleh data', 'Buat Aduan'],
        ];
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
