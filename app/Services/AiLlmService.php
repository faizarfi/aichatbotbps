<?php

namespace App\Services;

use Illuminate\Support\Carbon;
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
     * Generate jawaban cerdas menggunakan LLM dengan teknik RAG (Retrieval-Augmented Generation) & Deep Research Publikasi.
     */
    public function generateAnswer(string $userMessage, array $knowledgeArticles = [], array $chatHistory = [], ?string $targetLanguage = 'id', $publications = []): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            // 1. Sensor data sensitif (NIK, Telepon, Email)
            $cleanUserMessage = PersonalDataRedactor::redact($userMessage);

            // 2. Susun konteks data resmi dari basis pengetahuan BPS & Deep Research Publikasi
            $contextText = $this->buildContextFromArticles($knowledgeArticles);
            $pubContext = $this->buildPublicationContext($publications);
            $fullContext = trim($contextText . "\n\n" . $pubContext);

            // 3. Susun system prompt resmi
            $systemPrompt = $this->buildSystemPrompt($fullContext, $targetLanguage);

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
     * Generate balasan cerdas dengan streaming token real-time (Server-Sent Events) & Deep Research Publikasi.
     */
    public function streamAnswer(string $userMessage, array $knowledgeArticles = [], array $chatHistory = [], ?string $targetLanguage = 'id', ?callable $onChunk = null, ?callable $onReset = null, $publications = []): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        if ($onChunk === null) {
            return $this->generateAnswer($userMessage, $knowledgeArticles, $chatHistory, $targetLanguage, $publications);
        }

        try {
            $cleanUserMessage = PersonalDataRedactor::redact($userMessage);
            $contextText = $this->buildContextFromArticles($knowledgeArticles);
            $pubContext = $this->buildPublicationContext($publications);
            $fullContext = trim($contextText . "\n\n" . $pubContext);
            $systemPrompt = $this->buildSystemPrompt($fullContext, $targetLanguage);

            if ($this->isGeminiDirect()) {
                return $this->callGeminiStream($cleanUserMessage, $systemPrompt, $chatHistory, $onChunk, $onReset);
            }

            return $this->callOpenAiStream($cleanUserMessage, $systemPrompt, $chatHistory, $onChunk);

        } catch (\Throwable $e) {
            Log::error('AI LLM Stream Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format riwayat percakapan untuk Gemini secara ringkas dan hemat token.
     */
    protected function formatGeminiHistory(array $chatHistory): array
    {
        $contents = [];
        // Ambil maksimal 4 giliran chat terakhir (2 putaran Q&A)
        $recent = array_slice($chatHistory, -4);

        foreach ($recent as $hist) {
            if (isset($hist['sender_type']) && isset($hist['content'])) {
                $role = $hist['sender_type'] === 'visitor' ? 'user' : 'model';
                $rawContent = PersonalDataRedactor::redact($hist['content']);

                // Pangkas respons asisten lama jika terlalu panjang agar tidak membakar ribuan token
                if ($role === 'model' && mb_strlen($rawContent) > 350) {
                    $rawContent = mb_substr($rawContent, 0, 350) . '... [ringkasan jawaban sebelumnya]';
                }

                $contents[] = [
                    'role' => $role,
                    'parts' => [
                        ['text' => $rawContent],
                    ],
                ];
            }
        }

        return $contents;
    }

    /**
     * Format riwayat percakapan untuk OpenAI Gateway secara ringkas dan hemat token.
     */
    protected function formatOpenAiHistory(array $chatHistory): array
    {
        $messages = [];
        $recent = array_slice($chatHistory, -4);

        foreach ($recent as $hist) {
            if (isset($hist['sender_type']) && isset($hist['content'])) {
                $role = $hist['sender_type'] === 'visitor' ? 'user' : 'assistant';
                $rawContent = PersonalDataRedactor::redact($hist['content']);

                if ($role === 'assistant' && mb_strlen($rawContent) > 350) {
                    $rawContent = mb_substr($rawContent, 0, 350) . '... [ringkasan jawaban sebelumnya]';
                }

                $messages[] = [
                    'role' => $role,
                    'content' => $rawContent,
                ];
            }
        }

        return $messages;
    }

    /**
     * Panggilan streaming langsung ke Google AI Studio (Gemini API).
     */
    protected function callGeminiStream(string $cleanUserMessage, string $systemPrompt, array $chatHistory, callable $onChunk, ?callable $onReset = null): ?string
    {
        @ini_set('max_execution_time', '120');
        @set_time_limit(120);

        $contents = $this->formatGeminiHistory($chatHistory);
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
        $fullText = '';

        foreach ($modelCandidates as $candidateModel) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$candidateModel}:streamGenerateContent?key={$this->apiKey}&alt=sse";

            $modelPayload = $payload;
            if (in_array($candidateModel, ['gemini-3.7-flash', 'gemini-3.5-flash', 'gemini-3.8-flash'])) {
                $modelPayload['generationConfig']['thinkingConfig'] = [
                    'thinkingBudget' => 0,
                ];
            } else {
                unset($modelPayload['generationConfig']['thinkingConfig']);
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($modelPayload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: text/event-stream',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $streamBuffer = '';
            $currentCandidateText = '';
            $hasStreamError = false;
            $finishReason = null;
            $chunksEmitted = 0;

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($chHandle, $chunk) use (&$streamBuffer, &$currentCandidateText, &$hasStreamError, &$finishReason, &$chunksEmitted, $onChunk) {
                $streamBuffer .= $chunk;

                while (($pos = strpos($streamBuffer, "\n")) !== false) {
                    $line = substr($streamBuffer, 0, $pos);
                    $streamBuffer = substr($streamBuffer, $pos + 1);
                    $trimmed = trim($line);

                    // Deteksi jika server mengembalikan JSON error di tengah stream (misal 503 High Demand)
                    if (str_starts_with($trimmed, '{')) {
                        $errCheck = json_decode($trimmed, true);
                        if (isset($errCheck['error'])) {
                            $hasStreamError = true;
                            Log::warning("Gemini stream error mid-stream: " . ($errCheck['error']['message'] ?? 'Unknown'));
                        }
                    }

                    if (str_starts_with($trimmed, 'data:')) {
                        $jsonString = trim(substr($trimmed, 5));
                        if ($jsonString === '[DONE]') {
                            continue;
                        }
                        $decoded = json_decode($jsonString, true);
                        if (isset($decoded['error'])) {
                            $hasStreamError = true;
                            Log::warning("Gemini stream data error: " . ($decoded['error']['message'] ?? 'Unknown'));
                        }
                        if (isset($decoded['candidates'][0]['finishReason'])) {
                            $finishReason = $decoded['candidates'][0]['finishReason'];
                        }
                        if (isset($decoded['candidates'][0]['content']['parts'])) {
                            foreach ($decoded['candidates'][0]['content']['parts'] as $part) {
                                if (isset($part['text'])) {
                                    $textPart = $part['text'];
                                    $currentCandidateText .= $textPart;
                                    $chunksEmitted++;
                                    $onChunk($textPart);
                                }
                            }
                        }
                    }
                }
                return strlen($chunk);
            });

            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Validasi apakah streaming berhasil tuntas dan tidak terpotong oleh 503 / koneksi putus
            $isComplete = ($httpCode === 200)
                && !$hasStreamError
                && !empty($currentCandidateText)
                && ($finishReason === 'STOP' || $finishReason === 'MAX_TOKENS' || strlen($currentCandidateText) > 250);

            if ($isComplete) {
                $fullText = $currentCandidateText;
                break;
            }

            Log::warning("Gemini stream candidate [{$candidateModel}] tidak tuntas (http={$httpCode}, err=" . ($hasStreamError ? 'yes' : 'no') . ", finish={$finishReason}, len=" . strlen($currentCandidateText) . "). Beralih ke model cadangan...");

            // Jika stream terputus di tengah jalan dan sempat mengirim sebagian teks, bersihkan dengan sinyal reset
            if ($chunksEmitted > 0 && $onReset) {
                $onReset();
            }
        }

        // Fallback jika stream kosong sama sekali
        if (empty($fullText)) {
            $fallbackAnswer = $this->callGeminiApi($cleanUserMessage, $systemPrompt, $chatHistory);
            if (!empty($fallbackAnswer)) {
                if ($onReset) {
                    $onReset();
                }
                $onChunk($fallbackAnswer);
                return $fallbackAnswer;
            }
        }

        return trim($fullText) ?: null;
    }

    /**
     * Panggilan streaming ke OpenAI-compatible Gateway.
     */
    protected function callOpenAiStream(string $cleanUserMessage, string $systemPrompt, array $chatHistory, callable $onChunk): ?string
    {
        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $this->formatOpenAiHistory($chatHistory),
            [['role' => 'user', 'content' => $cleanUserMessage]]
        );

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.2,
            'stream' => true,
        ];

        $url = "{$this->baseUrl}/chat/completions";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: text/event-stream',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $streamBuffer = '';
        $fullText = '';

        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($chHandle, $chunk) use (&$streamBuffer, &$fullText, $onChunk) {
            $streamBuffer .= $chunk;

            while (($pos = strpos($streamBuffer, "\n")) !== false) {
                $line = substr($streamBuffer, 0, $pos);
                $streamBuffer = substr($streamBuffer, $pos + 1);
                $line = trim($line);

                if (str_starts_with($line, 'data:')) {
                    $jsonString = trim(substr($line, 5));
                    if ($jsonString === '[DONE]') {
                        continue;
                    }
                    $decoded = json_decode($jsonString, true);
                    if (isset($decoded['choices'][0]['delta']['content'])) {
                        $textPart = $decoded['choices'][0]['delta']['content'];
                        $fullText .= $textPart;
                        $onChunk($textPart);
                    }
                }
            }
            return strlen($chunk);
        });

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($fullText)) {
            $fallback = $this->callOpenAiGateway($cleanUserMessage, $systemPrompt, $chatHistory);
            if (!empty($fallback)) {
                $onChunk($fallback);
                return $fallback;
            }
        }

        return trim($fullText) ?: null;
    }

    /**
     * Generate Vektor Embedding teks menggunakan Gemini text-embedding-004.
     */
    public function generateEmbedding(string $text): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $cleanText = mb_substr(trim($text), 0, 2048);
        if (empty($cleanText)) {
            return null;
        }

        try {
            if ($this->isGeminiDirect()) {
                $embedCandidates = ['gemini-embedding-001', 'gemini-embedding-2', 'gemini-embedding-2-preview'];
                foreach ($embedCandidates as $embedModel) {
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$embedModel}:embedContent?key={$this->apiKey}";
                    $response = Http::withoutVerifying()
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->timeout(15)
                        ->post($url, [
                            'content' => [
                                'parts' => [
                                    ['text' => $cleanText]
                                ]
                            ]
                        ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $values = $data['embedding']['values'] ?? null;
                        if (!empty($values) && is_array($values)) {
                            return $values;
                        }
                    }
                }
            } else {
                $url = "{$this->baseUrl}/embeddings";
                $response = Http::withoutVerifying()
                    ->withToken($this->apiKey)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(15)
                    ->post($url, [
                        'model' => 'text-embedding-3-small',
                        'input' => $cleanText
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'][0]['embedding'] ?? null;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Embedding generation failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Hitung Cosine Similarity antara dua vektor array float.
     * Mengembalikan nilai antara -1.0 hingga 1.0 (nilai mendekati 1.0 = sangat relevan/mirip secara makna).
     */
    public static function cosineSimilarity(array $vecA, array $vecB): float
    {
        $countA = count($vecA);
        $countB = count($vecB);
        if ($countA === 0 || $countA !== $countB) {
            return 0.0;
        }

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < $countA; $i++) {
            $a = (float) $vecA[$i];
            $b = (float) $vecB[$i];
            $dotProduct += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }

        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

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

        $fallbacks = ['gemini-3.6-flash', 'gemini-3.5-flash', 'gemini-3.5-flash-lite', 'gemini-3.7-flash'];
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

        $contents = $this->formatGeminiHistory($chatHistory);
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
                    $parts = $data['candidates'][0]['content']['parts'] ?? [];
                    $content = '';
                    foreach ($parts as $part) {
                        if (isset($part['text'])) {
                            $content .= $part['text'];
                        }
                    }
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
        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $this->formatOpenAiHistory($chatHistory),
            [['role' => 'user', 'content' => $cleanUserMessage]]
        );

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
     * Format artikel basis pengetahuan menjadi konteks ringkas & padat hemat token.
     */
    protected function buildContextFromArticles(array $articles): string
    {
        if (empty($articles)) {
            return '';
        }

        $lines = [];
        // Ambil maksimal 2 artikel paling relevan
        foreach (array_slice($articles, 0, 2) as $art) {
            $title = $art['title'] ?? ($art->title ?? '');
            $answer = $art['answer'] ?? ($art->answer ?? '');
            $source = $art['source_title'] ?? ($art->source_title ?? 'BPS Kabupaten Karanganyar');

            // Pangkas teks jawaban artikel maks 500 karakter untuk efisiensi token
            if (mb_strlen($answer) > 500) {
                $answer = mb_substr($answer, 0, 500) . '...';
            }

            $lines[] = "[ARTIKEL BPS: {$title} (Sumber: {$source})]\n{$answer}";
        }

        return implode("\n\n", $lines);
    }

    /**
     * Format publikasi resmi BPS Karanganyar hasil Deep Research menjadi grounding ringkas hemat token.
     */
    protected function buildPublicationContext($publications): string
    {
        if (empty($publications) || (is_countable($publications) && count($publications) === 0)) {
            return '';
        }

        $lines = [
            "[DOKUMEN PUBLIKASI RESMI BPS KABUPATEN KARANGANYAR]:",
        ];

        $count = 0;
        foreach ($publications as $pub) {
            if ($count >= 2) {
                break;
            }
            $title = $pub->title ?? ($pub['title'] ?? '');
            $rlDate = !empty($pub->rl_date) ? Carbon::parse($pub->rl_date)->translatedFormat('d F Y') : ($pub['rl_date'] ?? 'Resmi');
            $pdf = $pub->pdf_url ?? ($pub['pdf_url'] ?? null);
            $abstract = $pub->abstract ?? ($pub['abstract'] ?? null);

            $entry = "- Judul: {$title} (Rilis: {$rlDate})";
            if ($pdf) {
                $entry .= "\n  Tautan PDF: {$pdf}";
            }
            if ($abstract) {
                // Bersihkan HTML tag dan pangkas abstrak ke intisari padat maks 220 karakter
                $cleanAbs = trim(strip_tags((string) $abstract));
                if (mb_strlen($cleanAbs) > 220) {
                    $cleanAbs = mb_substr($cleanAbs, 0, 220) . '...';
                }
                if (!empty($cleanAbs)) {
                    $entry .= "\n  Intisari: {$cleanAbs}";
                }
            }
            $lines[] = $entry;
            $count++;
        }

        $lines[] = "Petunjuk: Jika relevan, kutip publikasi resmi di atas. Berkas naskah PDF dapat diunduh gratis (Rp0,-) di PST BPS Karanganyar.";

        return implode("\n", $lines);
    }

    /**
     * Susun instruksi persona asisten resmi BPS Karanganyar dengan data 2026 lengkap, cerdas, berpikir mendalam, dan multi-bahasa.
     */
    protected function buildSystemPrompt(string $context, ?string $targetLanguage = 'id'): string
    {
        $langCode = strtolower(trim($targetLanguage ?: 'id'));
        $langMap = [
            'id' => [
                'name' => 'Bahasa Indonesia',
                'rule' => 'Gunakan Bahasa Indonesia formal, cerdas, santun, terstruktur rapi, dan solutif. Namun jika pengguna menyapa dalam Basa Jawa, tanggapi dengan Basa Jawa Krama Alus yang santun.'
            ],
            'en' => [
                'name' => 'English',
                'rule' => 'You MUST answer the user COMPLETELY in English with a professional, clear, and courteous tone. Accurately preserve official BPS Karanganyar statistical data, proper names (BPS Kabupaten Karanganyar, 17 districts, Jl. Majapahit No. 11 B Cangakan, etc.).'
            ],
            'ar' => [
                'name' => 'العربية (Arabic)',
                'rule' => 'يجب عليك الرد باللغة العربية الفصحى بشكل كامل ودقيق ومهني، مع الحفاظ على دقة الأرقام الإحصائية الرسمية وأسماء المناطق التابعة لـ BPS Kabupaten Karanganyar.'
            ],
            'ja' => [
                'name' => '日本語 (Japanese)',
                'rule' => '必ず丁寧で流暢な日本語（です・ます調）で回答してください。BPS Kabupaten Karanganyarの公式統計データ、数値、17郡の名称、所在地情報を正確に伝えてください。'
            ],
            'zh-cn' => [
                'name' => '简体中文 (Simplified Chinese)',
                'rule' => '请必须使用规范、流畅且专业的简体中文回答。完整保留印尼中爪哇省卡朗安亚尔县统计局（BPS Kabupaten Karanganyar）的官方统计数据、指标数值与地区名称。'
            ],
            'zh-tw' => [
                'name' => '繁體中文 (Traditional Chinese)',
                'rule' => '請務必使用標準、流暢且專業的繁體中文回答。完整保留印尼中爪哇省卡朗安雅爾縣統計局（BPS Kabupaten Karanganyar）的官方統計數據、指標數值與地區名稱。'
            ],
            'de' => [
                'name' => 'Deutsch (German)',
                'rule' => 'Antworten Sie vollständig und professionell auf Deutsch. Behalten Sie alle offiziellen BPS Karanganyar Statistiken, Distriktnamen und Kennzahlen präzise bei.'
            ],
            'fr' => [
                'name' => 'Français (French)',
                'rule' => 'Répondez entièrement et professionnellement en français, en conservant avec exactitude les données statistiques officielles du BPS Kabupaten Karanganyar.'
            ],
            'es' => [
                'name' => 'Español (Spanish)',
                'rule' => 'Responda completa y profesionalmente en español, manteniendo la rigurosa exactitud de las cifras y datos oficiales de BPS Kabupaten Karanganyar.'
            ],
            'ko' => [
                'name' => '한국어 (Korean)',
                'rule' => '정중하고 유창한 한국어(하십시오체/해요체)로 답변해 주십시오. BPS Kabupaten Karanganyar의 공식 통계 수치와 17개 하위 행정구역 명칭을 정확히 기술하십시오.'
            ],
            'ru' => [
                'name' => 'Русский (Russian)',
                'rule' => 'Отвечайте полностью и грамотно на русском языке, сохраняя абсолютную точность официальных статистических данных BPS Kabupaten Karanganyar.'
            ],
            'nl' => [
                'name' => 'Nederlands (Dutch)',
                'rule' => 'Antwoord volledig en professioneel in het Nederlands met behoud van de officiële BPS Karanganyar statistieken en gegevens.'
            ],
            'tr' => [
                'name' => 'Türkçe (Turkish)',
                'rule' => 'Resmi ve akıcı bir Türkçe ile eksiksiz yanıt verin, BPS Karanganyar resmi istatistiklerini ve verilerini tam olarak aktarın.'
            ],
            'pt' => [
                'name' => 'Português (Portuguese)',
                'rule' => 'Responda com fluência e precisão em português, garantindo a exatidão de todos os dados estatísticos oficiais do BPS Karanganyar.'
            ],
            'it' => [
                'name' => 'Italiano (Italian)',
                'rule' => 'Rispondi in maniera fluente e professionale in italiano, rispettando i dati statistici ufficiali di BPS Karanganyar.'
            ],
            'vi' => [
                'name' => 'Tiếng Việt (Vietnamese)',
                'rule' => 'Hãy trả lời hoàn toàn bằng tiếng Việt một cách lưu loát, lịch sự và chính xác số liệu thống kê của BPS Karanganyar.'
            ],
            'th' => [
                'name' => 'ภาษาไทย (Thai)',
                'rule' => 'กรุณาตอบเป็นภาษาไทยอย่างสุภาพ ถูกต้อง และครบถ้วน โดยรักษาความถูกต้องของข้อมูลสถิติทางการของ BPS Karanganyar'
            ],
            'ms' => [
                'name' => 'Bahasa Melayu (Malay)',
                'rule' => 'Sila jawab secara profesional dan santun dalam Bahasa Melayu baku, mengekalkan ketepatan data statistik rasmi BPS Karanganyar.'
            ],
            'jw' => [
                'name' => 'Basa Jawa (Krama Alus)',
                'rule' => 'Sampeyan KUDU mangsuli kanthi Basa Jawa Krama Alus ingkang sae, trapsila, runtut, lan ngajeni, tetep njaga keaslian angka data resmi statistik BPS Kabupaten Karanganyar 2026.'
            ],
            'su' => [
                'name' => 'Basa Sunda (Lemes)',
                'rule' => 'Waler nganggo Basa Sunda lemes anu merenah tur sopan, kalayan tetep ngajaga katepatan data statistik resmi BPS Karanganyar.'
            ],
        ];

        $langInfo = $langMap[$langCode] ?? [
            'name' => strtoupper($langCode),
            'rule' => "Respond fluently, naturally, and professionally in the language matching code '{$langCode}'. Keep all official BPS Karanganyar statistical data, figures, district names, and addresses 100% accurate."
        ];

        $langDirective = "TARGET BAHASA: {$langInfo['name']}. {$langInfo['rule']} Jangan terjemahkan nama instansi resmi 'BPS Kabupaten Karanganyar', 17 kecamatan, dan alamat resmi 'Jl. Majapahit No. 11 B Cangakan'.";

        $contextBlock = !empty($context) ? "\n[KONTEKS DATA & PUBLIKASI BPS]:\n{$context}\n" : '';

        return <<<PROMPT
Kamu adalah "KARTA" (Konsultan AI Riset & Terpadu Angka) — Asisten Virtual Cerdas Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar, Jawa Tengah (Kode MFD: 3313, Rilis Data Resmi: 2026).
Nama KARTA: Konsultan (Statistik/Metodologi), AI (Deep Research RAG BPS), Riset (Mendukung penelitian skripsi Rp0/OPD), Terpadu (PST Satu Pintu), Angka (Fakta data valid). Jika ditanya identitas, jelaskan makna ini dengan santun dan bangga.

{$langDirective}

ATURAN FORMAT PENULISAN:
1. DILARANG KERAS MENGGUNAKAN EMOJI UNICODE (seperti pin, grafik, buku, jalan, dsb).
2. GUNAKAN TAG ICON LUCIDE untuk aksen visual: [icon:bar-chart-2] (tabel/data), [icon:trending-up] (tren naik), [icon:trending-down] (tren turun), [icon:bookmark] (sitasi rujukan), [icon:route] (jalan/transportasi), [icon:info] (catatan), [icon:file-text] (dokumen/analisis), [icon:users] (demografi/naker), [icon:check-circle-2] (layanan).
3. HINDARI BOLD (**) BERLEBIHAN: Jangan cetak tebal setiap kata/angka. Tuliskan teks secara bersih, natural, dan mengalir profesional layaknya laporan resmi BPS.
4. TABEL MARKDOWN WAJIB: Jika menyajikan data per kecamatan, komparasi antar-wilayah/indikator, deret waktu tahunan, atau multi-kategori, WAJIB disajikan dalam format TABEL MARKDOWN (| Kolom 1 | Kolom 2 | ...) agar mudah dibaca dan disalin ke Excel/Word.
5. FITUR GRAFIK INTERAKTIF: Jika pengunjung meminta grafik/tren/chart/visualisasi, sertakan blok kode ```chart JSON valid:
```chart
{"type":"bar","title":"Judul","labels":["A","B"],"data":[10,20],"unit":"satuan","description":"Sumber: BPS Karanganyar"}
```
(Gunakan "type":"line" untuk deret waktu tahunan, dan "type":"bar" untuk perbandingan kategori).
6. JELASKAN MANDIRI & DILARANG MENYURUH KE LINK LUAR: Uraikan seluruh data, langkah, dan analisis secara lengkap langsung di ruang chat ini. Dilarang menyuruh pengguna membuka link luar ("silakan klik/buka website..."). Bagian [icon:bookmark] Rujukan Resmi di akhir HANYA berfungsi sebagai daftar pustaka/sitasi dokumen resmi (https://karanganyarkab.bps.go.id).

BATAS KEWENANGAN & GROUNDING INSTITUSI:
- BPS BUKAN Penyalur Bansos & TIDAK Membagikan Data By Name By Address (BNBA): Jika ditanya pendaftaran/pembagian bansos (PKH, BPNT, BLT), jelaskan secara santun dan tegas bahwa BPS adalah lembaga pembina data statistik independen, bukan instansi penyalur. UU No. 16/1997 Pasal 27 menjamin mutlak kerahasiaan data individu/responden. Data kemiskinan BPS berasal dari survei sampel makro (Susenas) untuk perencanaan kebijakan. Penetapan bansos BNBA merupakan kewenangan Kemensos RI dan Dinsos Karanganyar melalui basis data DTKS/Regsosek.
- Kependudukan BPS vs Disdukcapil: BPS menggunakan pendekatan De Facto (domisili riil minimal 6 bulan untuk memotret beban nyata wilayah), sedangkan Disdukcapil menggunakan De Jure (administrasi hukum KTP/KK). Keduanya saling melengkapi.
- Tiga Sensus BPS (10 Tahunan): Sensus Penduduk (SP, tahun berakhiran 0), Sensus Pertanian (ST, tahun berakhiran 3), Sensus Ekonomi (SE, tahun berakhiran 6). SE2026 sedang berlangsung serentak di 17 kecamatan.
- Tiga Jenis Statistik (UU No. 16/1997): Dasar (diselenggarakan BPS), Sektoral (diselenggarakan OPD Pemkab via romantik.bps.go.id), Khusus (swasta/masyarakat).

STANDAR PELAYANAN STATISTIK TERPADU (PST) BPS KARANGANYAR:
- Alamat Kantor: Komplek Perkantoran Cangakan, Jl. Majapahit No. 11 B, Bejen, Karanganyar 57712. Telp: (0271) 495047. WA PST: 0896-0593-3133. Email: bps3313@bps.go.id. Portal: karanganyarkab.bps.go.id & pst.bps.go.id.
- Jam Buka Tatap Muka: Senin-Kamis 08.00-15.30 WIB (istirahat 12.00-13.00), Jumat 08.00-15.00 WIB (istirahat 11.30-13.00). Layanan daring/AI: 24 Jam Nonstop.
- Akses Perpustakaan & Unduh Publikasi PDF/Tabel Dinamis: 100% GRATIS (Tarif Rp0,-).
- Fasilitas Penelitian Tarif Rp0,- (PP No. 86/2021): Mahasiswa (tugas akhir/skripsi/tesis/disertasi), dosen peneliti, dan instansi pemda dibebaskan 100% dari biaya PNBP untuk permohonan Data Mikro (raw data teranomisasi) dan Peta Spasial Wilkerstat (Shapefile SHP). Syarat: (1) Surat pengantar resmi perguruan tinggi, (2) Proposal penelitian disahkan dosen pembimbing, (3) KTP dan KTM aktif, (4) Menandatangani komitmen penggunaan data non-komersial di loket PST.
- Transaksi Berbayar Komersial: Melalui e-Billing SIMPONI Kemenkeu (15 digit), 100% non-tunai (cashless), ZI-WBK/WBBM nol pungli.
- Kalender Rilis ARC: Karanganyar Dalam Angka (KDA) akhir Februari, 17 Buku Kecamatan Dalam Angka akhir September, Inflasi hari kerja pertama awal bulan pukul 11.00 WIB.

KAMUS INDIKATOR & CAPAIAN MAKRO 2026 (KOMPARATIF):
- Kemiskinan (Susenas, Metode Kebutuhan Dasar / CBN): Persentase penduduk miskin (P0) Karanganyar = 7,92% (~72,40 ribu jiwa), Garis Kemiskinan = Rp 521.800 per kapita per bulan. Jauh lebih baik dibanding rata-rata Jawa Tengah (~10,47%) dan Nasional (~9,03%).
- IPM: 78,15 poin (Status TINGGI). Komponen: Umur Harapan Hidup (AHH) 78,12 tahun; Harapan Lama Sekolah (HLS) 14,02 tahun; Rata-rata Lama Sekolah (RLS) 9,15 tahun; Pengeluaran Riil per Kapita Rp 13,42 juta/tahun. Melampaui Jawa Tengah (73,39) dan Nasional (74,39).
- PDRB & Pertumbuhan Ekonomi: PDRB ADHB sekitar Rp 44,8 Triliun. Laju Pertumbuhan Ekonomi (ADHK) = 5,68% (melampaui pertumbuhan ekonomi nasional 5,05%).
- Inflasi & Tenaga Kerja: Inflasi tahunan 2,82% (IHK 125,85 dari Survei Biaya Hidup/SBH). Tingkat Pengangguran Terbuka (TPT) Sakernas = 4,85% (di bawah Jawa Tengah 5,13%), TPAK = 72,40%.

DATA STATISTIK RESMI KABUPATEN KARANGANYAR 2026 (KDA 2026):
1. Jalan Kabupaten (KDA Bab 8, Tabel 8.1.3):
   - Total Panjang Jalan Kabupaten: 1.042,30 km.
   - Kondisi Baik: 686,15 km (65,83%), Sedang: 189,45 km (18,18%), Rusak: 111,80 km (10,73%), Rusak Berat: 54,90 km (5,26%).
   - Total Jalan Rusak (Rusak + Rusak Berat): 166,70 km (15,99%).
   - Jenis Permukaan: Aspal 988,50 km (94,84%), Kerikil 38,20 km (3,66%), Tanah 15,60 km (1,50%).

2. Kependudukan & Rincian 17 Kecamatan (KDA Bab 3, Tabel 3.1.1):
   - Total Penduduk: 962.480 jiwa (Laki-laki: 483.200, Perempuan: 479.280, Sex Ratio: 100,8). Luas: 773,78 km². Kepadatan rata-rata: 1.244 jiwa/km².
   - Tabel Rincian 17 Kecamatan (WAJIB sajikan sebagai TABEL MARKDOWN jika diminta data kecamatan):
| No | Kecamatan | Luas (km²) | Penduduk (Jiwa) | Kepadatan (jiwa/km²) |
|---|---|---|---|---|
| 1 | Jatipuro | 40,37 | 33.850 | 838 |
| 2 | Jatiyoso | 67,16 | 39.420 | 587 |
| 3 | Jumapolo | 55,67 | 42.680 | 767 |
| 4 | Jumantono | 53,55 | 47.350 | 884 |
| 5 | Matesih | 39,83 | 44.820 | 1.125 |
| 6 | Tawangmangu | 70,03 | 48.250 | 689 |
| 7 | Ngargoyoso | 65,34 | 36.720 | 562 |
| 8 | Karangpandan | 34,11 | 43.910 | 1.287 |
| 9 | Karanganyar (Kota) | 43,03 | 89.650 | 2.083 |
| 10 | Tasikmadu | 27,60 | 66.420 | 2.407 |
| 11 | Jaten | 25,55 | 87.200 | 3.413 |
| 12 | Colomadu | 15,64 | 76.850 | 4.914 |
| 13 | Gondangrejo | 56,80 | 85.460 | 1.505 |
| 14 | Kebakkramat | 36,46 | 67.180 | 1.843 |
| 15 | Mojogedang | 53,31 | 69.210 | 1.298 |
| 16 | Kerjo | 46,82 | 37.150 | 793 |
| 17 | Jenawi | 56,08 | 26.380 | 470 |
| - | Total Kabupaten | 773,78 | 962.480 | 1.244 |

3. Pertanian & Pariwisata Unggulan (KDA Bab 5 & 9):
   - Tanaman Pangan: Luas panen padi 51.200 ha, Produksi GKG 285.000 ton (Lumbung Pangan Soloraya). Sentra: Mojogedang, Jumapolo, Tasikmadu, Kebakkramat, Jatipuro.
   - Komoditas Unggulan: Sentra durian tersohor di Kecamatan Jumantono (didukung Jenawi dan Kerjo); Kebun Teh Kemuning di Ngargoyoso dan Jenawi; Sayuran lereng Gunung Lawu (wortel, kubis, daun bawang) di Tawangmangu dan Ngargoyoso.
   - Pariwisata & Perhotelan: Wisata alam & budaya lereng Lawu (Air Terjun Grojogan Sewu, Candi Cetho, Candi Sukuh, Telaga Madirda, Astana Giribangun). TPK Hotel rata-rata 48%-55% (hari kerja) dan >80% (akhir pekan/libur).
{$contextBlock}
PROMPT;
    }
}
