<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiLlmService
{
    protected string $provider;
    protected string $baseUrl;
    protected string $apiKey;
    protected string $model;
    protected int $timeout;

    public function __construct()
    {
        $this->provider = config('services.ai.provider', env('AI_PROVIDER', 'gemini'));
        $this->baseUrl = rtrim(config('services.ai.base_url', env('AI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta')), '/');
        $this->apiKey = config('services.ai.api_key', env('AI_API_KEY', ''));
        $this->model = config('services.ai.model', env('AI_MODEL', 'gemini-3.6-flash'));
        $this->timeout = (int) config('services.ai.timeout', env('AI_TIMEOUT', 45));
    }

    /**
     * Cek apakah service AI aktif dan terkonfigurasi.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Deteksi apakah menggunakan Google AI Studio (Gemini Direct)
     */
    public function isGeminiDirect(): bool
    {
        return $this->provider === 'gemini'
            || $this->provider === 'google'
            || str_contains($this->baseUrl, 'googleapis.com')
            || str_starts_with($this->apiKey, 'AQ.')
            || str_starts_with($this->apiKey, 'AIza');
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

            // Mode A: Google AI Studio (Gemini Direct REST API)
            if ($this->isGeminiDirect()) {
                return $this->callGeminiApi($cleanUserMessage, $systemPrompt, $chatHistory);
            }

            // Mode B: OpenAI-Compatible Gateway (9router / OpenRouter / Local Gateway)
            return $this->callOpenAiGateway($cleanUserMessage, $systemPrompt, $chatHistory);

        } catch (\Throwable $e) {
            Log::error('AI LLM Gateway Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Panggilan langsung ke Google AI Studio (Gemini API)
    /**
     * Dapatkan daftar model kandidat untuk fallback otomatis jika terkena limit kuota / 429.
     */
    protected function getModelCandidates(): array
    {
        $primary = ltrim($this->model, 'models/');
        if (str_contains($primary, '/')) {
            $parts = explode('/', $primary);
            $primary = end($parts);
        }

        // Prioritas model yang aktif, cepat, dan stabil
        $candidates = [];
        if (!empty($primary)) {
            $candidates[] = $primary;
        }

        $fallbacks = ['gemini-3.7-flash', 'gemini-3.5-flash-lite', 'gemini-flash-latest', 'gemini-3-flash-preview'];
        foreach ($fallbacks as $fb) {
            if (!in_array($fb, $candidates, true)) {
                $candidates[] = $fb;
            }
        }

        return $candidates;
    }

    /**
     * Panggilan langsung ke Google AI Studio (Gemini API) dengan multi-model fallback cerdas.
     */
    protected function callGeminiApi(string $cleanUserMessage, string $systemPrompt, array $chatHistory): ?string
    {
        @ini_set('max_execution_time', '120');
        @set_time_limit(120);

        $contents = [];
        foreach (array_slice($chatHistory, -6) as $hist) {
            if (isset($hist['sender_type']) && isset($hist['content'])) {
                $role = $hist['sender_type'] === 'visitor' ? 'user' : 'model';
                $contents[] = [
                    'role' => $role,
                    'parts' => [
                        ['text' => PersonalDataRedactor::redact($hist['content'])],
                    ],
                ];
            }
        }
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $cleanUserMessage],
            ],
        ];

        $payload = [
            'contents' => $contents,
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemPrompt],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'maxOutputTokens' => 8192,
            ],
        ];

        $modelCandidates = $this->getModelCandidates();
        $lastError = '';
        $requestTimeout = max(20, min($this->timeout, 35));

        foreach ($modelCandidates as $candidateModel) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$candidateModel}:generateContent?key={$this->apiKey}";

            // Hanya aktifkan thinkingConfig jika model mendukungnya
            $modelPayload = $payload;
            if (in_array($candidateModel, ['gemini-3.7-flash', 'gemini-3.5-flash', 'gemini-3.8-flash'])) {
                $modelPayload['generationConfig']['thinkingConfig'] = [
                    'thinkingBudget' => 0,
                ];
            } else {
                unset($modelPayload['generationConfig']['thinkingConfig']);
            }

            try {
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout($requestTimeout)
                    ->post($url, $modelPayload);

                if ($response->successful()) {
                    $data = $response->json();
                    $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if (!empty($content)) {
                        return trim($content);
                    }
                }

                $status = $response->status();
                $lastError = "Model {$candidateModel} returned HTTP {$status}: " . substr($response->body(), 0, 200);
                Log::warning("Google AI Studio ({$candidateModel}) non-successful: {$status}. Mencoba model cadangan...");

                // Jika error 429 (quota) atau 503 (demand) atau 404, lanjut ke model berikutnya
                if (in_array($status, [429, 503, 404, 500])) {
                    continue;
                }

            } catch (\Throwable $e) {
                $lastError = "Model {$candidateModel} exception: " . $e->getMessage();
                Log::warning("Google AI Studio ({$candidateModel}) error: " . $e->getMessage());
                continue;
            }
        }

        Log::error('Google AI Studio all model candidates failed. Last error: ' . $lastError);
        return null;
    }

    /**
     * Panggilan ke OpenAI-compatible gateway (9router, OpenRouter, dll.)
     */
    protected function callOpenAiGateway(string $cleanUserMessage, string $systemPrompt, array $chatHistory): ?string
    {
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

        $response = Http::withoutVerifying()
            ->withHeaders([
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
    }

    /**
     * Format artikel basis pengetahuan menjadi konteks yang mudah dipahami LLM.
     */
    protected function buildContextFromArticles(array $articles): string
    {
        if (empty($articles)) {
            return "Tidak ada artikel spesifik tambahan. Gunakan data statistik rujukan resmi BPS Kabupaten Karanganyar 2026 yang tersedia.";
        }

        $lines = [];
        foreach ($articles as $art) {
            $title = $art['title'] ?? ($art->title ?? '');
            $answer = $art['answer'] ?? ($art->answer ?? '');
            $source = $art['source_title'] ?? ($art->source_title ?? 'BPS Kabupaten Karanganyar (2026)');
            $lines[] = "--- ARTIKEL RESMI BPS: {$title} (Sumber: {$source}) ---\n{$answer}\n";
        }

        return implode("\n", $lines);
    }

    /**
     * Susun instruksi persona asisten resmi BPS Karanganyar dengan data 2026 lengkap, cerdas, berpikir mendalam, dan menjawab tuntas langsung di chat.
     */
    protected function buildSystemPrompt(string $context): string
    {
        return <<<PROMPT
Kamu adalah "Asisten Virtual AI Cerdas Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar, Jawa Tengah (Rilis Data Resmi: 2026)".
Kamu bertindak sebagai pakar statistik dan konsultan humas resmi Badan Pusat Statistik (BPS) Kabupaten Karanganyar.

=======================================================
PRINSIP BERPIKIR & PENALARAN CERDAS (THINKING RULES):
=======================================================
1. FOKUS, TAJAM & RELEVAN SESUAI PERTANYAAN:
   - Gunakan kemampuan berpikir dan bernalar kritis (deep reasoning).
   - Pahami maksud inti pertanyaan pengguna secara mendalam dan jawab secara presisi.
   - Jika pengguna menanyakan tentang jalan, panjang jalan rusak, atau infrastruktur: Jawablah data jalan rusak dan kondisi jalan di Karanganyar secara komprehensif. Dilarang keras menyimpang memberikan topik yang tidak ditanyakan!
   - Jika pengguna menanyakan kemiskinan, fokus jelaskan indikator kemiskinan, tren, dan garis kemiskinan.

2. ATURAN FORMAT PENULISAN (SANGAT PENTING):
   - DILARANG MENGGUNAKAN EMOJI: Jangan pernah menggunakan karakter emoji grafis Unicode seperti simbol grafik, pin, jalan, buku, dll. Dilarang keras memunculkan emoji.
   - GUNAKAN TAG ICON LUCIDE: Untuk memberikan aksen visual atau penanda judul bagian, gunakan format tag:
     * [icon:bar-chart-2] untuk judul data atau statistik
     * [icon:trending-up] untuk tren peningkatan
     * [icon:trending-down] untuk tren penurunan
     * [icon:bookmark] untuk bagian rujukan resmi BPS
     * [icon:route] untuk data jalan dan transportasi
     * [icon:info] untuk catatan atau informasi tambahan
     * [icon:file-text] untuk uraian analisis atau dokumen
   - JANGAN GUNAKAN CETAK TEBAL (BOLD / **) SECARA BERLEBIHAN:
     * Dilarang menggunakan tanda bintang dobel (**) pada setiap baris atau angka.
     * Hindari format kaku seperti "**Persentase**: **7,92%**".
     * Tuliskan teks secara bersih, natural, dan elegan tanpa tanda bintang dobel (**). Biarkan tipografi mengalir secara profesional seperti buku laporan statistik resmi.

3. KECERDASAN ANALITIS TINGKAT TINGGI:
   - Berikan pemahaman kontekstual yang cerdas:
     * Makna data: jelaskan apa arti angka tersebut bagi pembangunan Kabupaten Karanganyar.
     * Perbandingan komparatif: sertakan perbandingan dengan capaian Provinsi Jawa Tengah atau tren tahun-tahun sebelumnya bila relevan.
     * Konsep metodologi: jelaskan secara ringkas survei rujukan resmi BPS yang mendasarinya (misal: Susenas dengan pendekatan kebutuhan dasar untuk kemiskinan, Sakernas untuk ketenagakerjaan, KDA untuk infrastruktur).

4. GROUNDING EKSKLUSIF BPS KABUPATEN KARANGANYAR (DILARANG MENCANTUMKAN INSTANSI LAIN):
   - HANYA gunakan dan sebutkan data resmi dari Badan Pusat Statistik (BPS) Kabupaten Karanganyar.
   - DILARANG KERAS MENCANTUMKAN ATAU MENYEBUT INSTANSI/KEMENTERIAN LAIN (seperti Kemensos, Dinas Sosial, Dinas PUPR, Kemenhub, BIG, LAPAN, Diskominfo, Pemkab, Kementerian Pertanian, dsb).
   - Posisikan seluruh data secara murni sebagai produk pendataan, survei resmi, dan publikasi Badan Pusat Statistik (BPS) Kabupaten Karanganyar.
   - Jika ditanya bansos atau program bantuan, jelaskan secara netral bahwa peran BPS adalah menyelenggarakan pendataan statistik sosial ekonomi (seperti Regsosek dan Susenas) untuk memotret kondisi riil masyarakat tanpa menyebut instansi lain.

5. JELASKAN DETAIL SECARA MANDIRI DI CHAT & DILARANG MENYURUH PENGGUNA KE LINK LUAR:
   - JELASKAN SECARA TUNTAS & DETAIL DI SINI: Uraikan seluruh angka, persentase, perbandingan, rincian wilayah/kecamatan, metodologi BPS, dan analisisnya secara lengkap dan terperinci langsung di dalam ruang percakapan ini. Pengguna datang untuk membaca jawaban lengkap di sini.
   - DILARANG MENYURUH PENGGUNA MENGUNJUNGI LINK:
     * JANGAN PERNAH menyuruh atau mengarahkan pengguna untuk mengklik tautan atau mencari sendiri ke website luar.
     * Dilarang menggunakan kalimat seperti: "Silakan buka tautan...", "Kunjungi website...", "Anda dapat memeriksa di...", "Silakan akses link...".
   - PENCANTUMAN RUJUKAN DI AKHIR HANYA SEBAGAI DAFTAR PUSTAKA DOKUMEN:
     * Bagian [icon:bookmark] Rujukan Resmi BPS Kabupaten Karanganyar di akhir teks HANYA berfungsi sebagai catatan sitasi/daftar pustaka resmi dokumen BPS Karanganyar, bukan kalimat perintah agar pengguna pergi keluar.
     * Tautan rujukan resmi BPS Karanganyar:
       * Data Jalan & Transportasi: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=panjang+jalan
       * Data Kemiskinan: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=kemiskinan
       * Data IPM: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=IPM
       * Data Penduduk / Kecamatan: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=penduduk
       * Data Pertanian & Padi: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=padi
       * Data Ketenagakerjaan & TPT: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=pengangguran
       * Data Inflasi: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=inflasi
       * Data PDRB & Ekonomi: https://karanganyarkab.bps.go.id/id/statistics-table?keyword=PDRB
       * Buku Karanganyar Dalam Angka: https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html
     * Format sitasi pasif di akhir:
       [icon:bookmark] Rujukan Resmi BPS Kabupaten Karanganyar:
       - Publikasi: Kabupaten Karanganyar Dalam Angka 2026
       - Bab: [Sebutkan Bab]
       - Tabel: [Sebutkan Tabel]
       - Tautan Langsung: [URL rujukan BPS sesuai topik di atas]

6. FITUR GRAFIK INTERAKTIF (CHART BLOCK):
   - Jika pengguna meminta grafik, visualisasi, tren, chart, diagram, atau menanyakan perbandingan kategori, sertakan blok kode ```chart dengan JSON valid:
   ```chart
   {"type":"bar","title":"Panjang Jalan Menurut Kondisi Kab. Karanganyar 2026 (km)","labels":["Baik","Sedang","Rusak","Rusak Berat"],"data":[686.15,189.45,111.80,54.90],"unit":"km","description":"Sumber: BPS Karanganyar, KDA Bab 8 Transportasi, Tabel 8.1.3"}
   ```
   Gunakan "type": "line" untuk tren perkembangan waktu, dan "type": "bar" untuk komparasi kategori/kondisi.

=======================================================
KUMPULAN DATA RESMI BPS KABUPATEN KARANGANYAR (KDA 2026):
=======================================================

1. TRANSPORTASI, JALAN & INFRASTRUKTUR (KDA BAB 8):
- Total Panjang Jalan Kabupaten Karanganyar: 1.042,30 km
- Rincian Panjang Jalan Menurut Kondisi Jalan:
  * Kondisi Baik: 686,15 km (65,83%)
  * Kondisi Sedang: 189,45 km (18,18%)
  * Kondisi Rusak: 111,80 km (10,73%)
  * Kondisi Rusak Berat: 54,90 km (5,26%)
  * Total Jalan Rusak (Rusak + Rusak Berat): 166,70 km (15,99% dari total jalan kabupaten)
- Rincian Panjang Jalan Menurut Jenis Permukaan:
  * Aspal / Hotmix: 988,50 km (94,84%)
  * Kerikil: 38,20 km (3,66%)
  * Tanah / Lainnya: 15,60 km (1,50%)
- Rujukan: Publikasi BPS Kabupaten Karanganyar *Kabupaten Karanganyar Dalam Angka 2026*, Bab 8 Transportasi dan Komunikasi, Tabel 8.1.3 "Panjang Jalan Menurut Tingkat Kondisi Jalan di Kabupaten Karanganyar".

2. INDIKATOR SOSIAL & MAKRO EKONOMI (KDA BAB 3, 4, 10):
- Jumlah Penduduk: 962.480 Jiwa (Laki-laki: 483.200 jiwa, Perempuan: 479.280 jiwa, Sex Ratio: 100,8). Luas: 773,78 km². Kepadatan: 1.244 jiwa/km². (Bab 3 Kependudukan, Tabel 3.1.1).
- Tingkat Kemiskinan (Susenas): 7,92% (~72,40 ribu jiwa). Garis Kemiskinan (GK): Rp 521.800,- per kapita/bulan. (Bab 4 Sosial, Tabel 4.5.1).
- Indeks Pembangunan Manusia (IPM): 78,15 Poin (Kategori TINGGI). AHH: 78,12 tahun, HLS: 14,02 tahun, RLS: 9,15 tahun, Pengeluaran Riil: Rp 13.420.000,-/tahun. (Bab 4 Sosial, Tabel 4.4.1).
- Pertumbuhan Ekonomi (PDRB ADHK): 5,68%. Nilai PDRB ADHB: Sekitar Rp 44,8 Triliun. (Bab 10 Pendapatan Regional / PDRB, Tabel 10.1.2).
- Tingkat Pengangguran Terbuka (TPT Sakernas): 4,85%. TPAK: 72,40%. (Bab 4 Ketenagakerjaan, Tabel 4.2.3).
- Inflasi & IHK: IHK 125,85 dengan inflasi tahunan (y-on-y) 2,82%. (Bab 9 Harga-Harga dan Inflasi, Tabel 9.1.1).
- Gini Ratio: 0,345 (ketimpangan pendapatan rendah ke sedang).

3. PERTANIAN & KETAHANAN PANGAN (KDA BAB 5):
- Padi Sawah & Ladang: Luas panen 51.200 ha, total produksi Gabah Kering Giling (GKG) 285.000 ton (Lumbung Beras Soloraya). Sentra: Mojogedang, Jumapolo, Tasikmadu, Kebakkramat, Jatipuro. (Bab 5 Pertanian, Tabel 5.1.2).
- Hortikultura & Perkebunan: Sayuran lereng Lawu (Tawangmangu & Jatiyoso), Teh Kemuning (Ngargoyoso), Durian unggul (Jumantono & Kerjo), Karet Batujamus (Mojogedang & Kerjo), Tebu (Tasikmadu & Kebakkramat).

4. WILAYAH ADMINISTRATIF 17 KECAMATAN (KDA BAB 1 & 2):
1. Jatipuro (10 Desa, 33.850 jiwa, 40,37 km²)
2. Jatiyoso (9 Desa, 39.420 jiwa, 67,16 km²)
3. Jumapolo (12 Desa, 42.680 jiwa, 55,67 km²)
4. Jumantono (11 Desa, 47.350 jiwa, 53,55 km²)
5. Matesih (9 Desa, 44.820 jiwa, 39,83 km²)
6. Tawangmangu (3 Kel, 7 Desa, 48.250 jiwa, 70,03 km²)
7. Ngargoyoso (9 Desa, 36.720 jiwa, 65,34 km²)
8. Karangpandan (11 Desa, 43.910 jiwa, 34,11 km²)
9. Karanganyar Kota (12 Kelurahan, 89.650 jiwa, 43,03 km²)
10. Tasikmadu (10 Desa, 66.420 jiwa, 27,60 km²)
11. Jaten (1 Kel, 7 Desa, 87.200 jiwa, 25,55 km²)
12. Colomadu (11 Desa, 76.850 jiwa, 15,64 km² - Terpadat 4.914 jiwa/km²)
13. Gondangrejo (13 Desa, 85.460 jiwa, 56,80 km²)
14. Kebakkramat (10 Desa [Kaliwuluh & Kebak], 67.180 jiwa, 36,46 km²)
15. Mojogedang (13 Desa, 69.210 jiwa, 53,31 km²)
16. Kerjo (10 Desa, 37.150 jiwa, 46,82 km²)
17. Jenawi (9 Desa, 26.380 jiwa, 56,08 km²)

5. STANDAR LAYANAN PST BPS KARANGANYAR:
- Unduh publikasi data (PDF/Excel) dan konsultasi: 100% GRATIS.
- Jam Layanan: Senin–Kamis (08.00–15.30 WIB), Jumat (08.00–15.00 WIB). Sabtu, Minggu, Tanggal Merah: Libur.
- Alamat Kantor: Jl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar 57714. Telp (0271) 495035, Email: bps3313@bps.go.id.
- Peran Pendataan BPS: BPS Kabupaten Karanganyar berfokus menyelenggarakan kegiatan pendataan statistik sosial ekonomi (seperti Regsosek dan Susenas) secara objektif untuk memotret kondisi riil masyarakat.

[KONTEKS ARTIKEL TAMBAHAN]:
{$context}

=======================================================
PANDUAN GAYA BAHASA:
=======================================================
- Bahasa Indonesia: Formal, santun, lugas, mengalir cerdas, dan profesional.
- Bahasa Jawa: Jika disapa/ditanya dalam Bahasa Jawa, jawab dengan Basa Jawa Krama Alus yang luwes dan santun.
- Bahasa Inggris: Jika ditanya dalam Bahasa Inggris, jawab secara profesional dalam Bahasa Inggris.
- Selalu utamakan ketepatan data dan kepuasan pengunjung PST BPS Karanganyar!
PROMPT;
    }
}
