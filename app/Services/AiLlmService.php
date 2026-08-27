<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiLlmService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $model;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ai.base_url', env('AI_BASE_URL', 'http://localhost:20128/v1')), '/');
        $this->apiKey = config('services.ai.api_key', env('AI_API_KEY', ''));
        $this->model = config('services.ai.model', env('AI_MODEL', 'ag/gemini-3-flash'));
        $this->timeout = (int) config('services.ai.timeout', env('AI_TIMEOUT', 45));
    }

    /**
     * Cek apakah service AI aktif dan terkonfigurasi.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->baseUrl);
    }

    /**
     * Generate jawaban cerdas menggunakan LLM dengan teknik RAG (Retrieval-Augmented Generation).
     */
    public function generateAnswer(string $userMessage, array $knowledgeArticles = [], array $chatHistory = []): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            // 1. Sensor data sensitif (NIK, Telepon, Email)
            $cleanUserMessage = PersonalDataRedactor::redact($userMessage);

            // 2. Susun konteks data resmi dari basis pengetahuan BPS
            $contextText = $this->buildContextFromArticles($knowledgeArticles);

            // 3. Susun system prompt resmi
            $systemPrompt = $this->buildSystemPrompt($contextText);

            // 4. Susun riwayat pesan (history)
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            foreach (array_slice($chatHistory, -6) as $hist) {
                if (isset($hist['sender_type']) && isset($hist['content'])) {
                    $role = $hist['sender_type'] === 'visitor' ? 'user' : 'assistant';
                    $messages[] = [
                        'role' => $role,
                        'content' => PersonalDataRedactor::redact($hist['content']),
                    ];
                }
            }

            $messages[] = ['role' => 'user', 'content' => $cleanUserMessage];

            // 5. Kirim request ke gateway LLM dengan kuota token lega untuk reasoning
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.3,
                'max_tokens' => 4096,
                'max_completion_tokens' => 4096,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;

                if (!empty($content)) {
                    return trim($content);
                }
            }

            Log::warning('AI LLM Gateway non-successful response: ' . $response->status() . ' Body: ' . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error('AI LLM Gateway Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format artikel basis pengetahuan menjadi konteks yang mudah dipahami LLM.
     */
    protected function buildContextFromArticles(array $articles): string
    {
        if (empty($articles)) {
            return "Tidak ada artikel spesifik tambahan. Gunakan pengetahuan umum resmi BPS Karanganyar.";
        }

        $lines = [];
        foreach ($articles as $art) {
            $title = $art['title'] ?? ($art->title ?? '');
            $answer = $art['answer'] ?? ($art->answer ?? '');
            $source = $art['source_title'] ?? ($art->source_title ?? 'BPS Kabupaten Karanganyar');
            $lines[] = "--- ARTIKEL RESMI: {$title} (Sumber: {$source}) ---\n{$answer}\n";
        }

        return implode("\n", $lines);
    }

    /**
     * Susun instruksi persona asisten resmi BPS Karanganyar.
     */
    protected function buildSystemPrompt(string $context): string
    {
        return <<<PROMPT
Kamu adalah "Asisten Virtual Resmi Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar, Jawa Tengah".
Tugasmu adalah membantu masyarakat, mahasiswa, akademisi, dan instansi dalam mencari data statistik, konsultasi, jadwal layanan, serta pengaduan.

DATA KUNCI RESMI BPS KABUPATEN KARANGANYAR (Gunakan sebagai rujukan utama):
- Jumlah Penduduk: 953.696 Jiwa (BPS Karanganyar Dalam Angka 2024, 17 Kecamatan)
- Persentase Kemiskinan: 8,48% (sekitar 77,66 ribu jiwa, Susenas BPS)
- Indeks Pembangunan Manusia (IPM): 77,31 Poin (Kategori TINGGI, Metode Baru BPS)
- Laju Pertumbuhan Ekonomi (PDRB): 5,54% (Atas Dasar Harga Konstan)
- Jam Layanan Tatap Muka PST: Senin–Kamis (08.00–15.30 WIB, istirahat 12.00–13.00), Jumat (08.00–15.00 WIB, istirahat 11.30–13.00), Sabtu & Minggu Libur.
- Alamat Kantor BPS: Jl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar, Kab. Karanganyar 57714.
- Kontak Resmi: Telepon (0271) 495035, Email bps3313@bps.go.id, Website https://karanganyarkab.bps.go.id.
- Biaya Layanan: Permintaan data elektronik / softcopy dan konsultasi statistik dasar adalah 100% GRATIS.
- Desil Kesejahteraan / DTKS / Bantuan Sosial: BPS bertugas melakukan pendataan awal (seperti Registrasi Sosial Ekonomi / Regsosek). Penetapan desil, penerima bansos, dan pengelolaan DTKS merupakan wewenang Kementerian Sosial (Kemensos) dan Dinas Sosial. Arahkan pengecekan mandiri ke portal cekbansos.kemensos.go.id atau operator SIKS-NG di Kantor Desa/Kelurahan.

[DATA RESMI TAMBAHAN DARI BASIS PENGETAHUAN BPS]:
{$context}

ATURAN MENJAWAB:
1. Bersikap ramah, sopan, komunikatif, dan profesional layaknya petugas humas resmi pemerintah.
2. Berikan jawaban yang tuntas, lengkap, terstruktur, rapi, dan mudah dipahami (gunakan poin/bullet list).
3. Jika ditanya data statistik resmi Karanganyar, berikan angka riil tersebut dengan percaya diri.
4. Jika pengguna ingin menyampaikan keluhan, kritik, atau aduan, sarankan secara santun untuk mengisi menu "Buat Aduan" di website ini agar mendapatkan Nomor Tiket Resmi.
5. Jika pengguna memerlukan penanganan khusus dari petugas manusia, ingatkan bahwa mereka bisa menekan tombol "Hubungi Petugas" di pojok atas chat.
6. Jawab dalam Bahasa Indonesia yang baik dan santun secara tuntas tanpa terpotong.
PROMPT;
    }
}
