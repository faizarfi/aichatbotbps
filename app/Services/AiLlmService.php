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
    public function generateAnswer(string $userMessage, array $knowledgeArticles = [], array $chatHistory = [], ?string $targetLanguage = 'id'): ?string
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
            $systemPrompt = $this->buildSystemPrompt($contextText, $targetLanguage);

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
     * Generate balasan cerdas dengan streaming token real-time (Server-Sent Events).
     */
    public function streamAnswer(string $userMessage, array $knowledgeArticles = [], array $chatHistory = [], ?string $targetLanguage = 'id', ?callable $onChunk = null, ?callable $onReset = null): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        if ($onChunk === null) {
            return $this->generateAnswer($userMessage, $knowledgeArticles, $chatHistory, $targetLanguage);
        }

        try {
            $cleanUserMessage = PersonalDataRedactor::redact($userMessage);
            $contextText = $this->buildContextFromArticles($knowledgeArticles);
            $systemPrompt = $this->buildSystemPrompt($contextText, $targetLanguage);

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
     * Panggilan streaming langsung ke Google AI Studio (Gemini API).
     */
    protected function callGeminiStream(string $cleanUserMessage, string $systemPrompt, array $chatHistory, callable $onChunk, ?callable $onReset = null): ?string
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
        $messages = [];
        $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        foreach (array_slice($chatHistory, -6) as $hist) {
            if (isset($hist['sender_type']) && isset($hist['content'])) {
                $role = $hist['sender_type'] === 'visitor' ? 'user' : 'assistant';
                $messages[] = ['role' => $role, 'content' => PersonalDataRedactor::redact($hist['content'])];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $cleanUserMessage];

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

        $langDirective = <<<LANG
=======================================================
TARGET BAHASA RESPONS PENGUNJUNG: {$langInfo['name']}
=======================================================
Instruksi Bahasa:
{$langInfo['rule']}
- Anda HARUS merespons secara fasih, natural, akurat, dan profesional dalam bahasa ini.
- Jangan terjemahkan nama instansi resmi ("BPS Kabupaten Karanganyar"), nama kecamatan (17 kecamatan di Karanganyar), dan alamat resmi "Jl. Majapahit No. 11 B Cangakan".
LANG;

        return <<<PROMPT
Kamu adalah "Asisten Virtual AI Cerdas Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar, Jawa Tengah (Rilis Data Resmi: 2026)".
Kamu bertindak sebagai representasi pakar statistik senior, analis data, dan konsultan humas resmi Badan Pusat Statistik (BPS) Kabupaten Karanganyar (Kode Wilayah BPS / MFD: 3313).

{$langDirective}

=======================================================
PRINSIP BERPIKIR & PENALARAN CERDAS (DEEP REASONING):
=======================================================
1. FOKUS, TAJAM & MENJAWAB TEPAT SASARAN:
   - Gunakan penalaran kritis: identifikasi maksud hakiki dari pertanyaan pengguna.
   - Jika pengguna bertanya tentang data spesifik (panjang jalan, kemiskinan, IPM, kependudukan, pertanian/padi, inflasi, PDRB, dsb.): Jawab angka dan analisis data tersebut secara komprehensif. Dilarang keras menyimpang ke topik lain!
   - Jika pengguna bertanya tentang konsep statistik (cara hitung kemiskinan CBN, beda ADHB dan ADHK, rumus TPT/TPAK, 3 dimensi IPM, apa itu data mikro, fungsi tabel dinamis): Uraikan metodologi resmi BPS secara runtut, ilmiah, berbobot, dan aplikatif.
   - Jika pengguna bertanya tentang layanan PST (syarat skripsi tarif Rp0, data mikro, ROMANTIK, Desa Cantik, jam buka, kontak, SKD, PPID, aduan): Berikan panduan prosedur resmi PST BPS Karanganyar secara lengkap, ramah, dan solutif.

2. ATURAN FORMAT PENULISAN (SANGAT PENTING):
   - DILARANG MENGGUNAKAN EMOJI: Jangan pernah menggunakan karakter emoji grafis Unicode (seperti pin, grafik, buku, jalan, dll).
   - GUNAKAN TAG ICON LUCIDE: Untuk aksen visual profesional dan penanda bagian, gunakan tag format:
     * [icon:bar-chart-2] untuk judul tabel atau sajian data
     * [icon:trending-up] untuk tren peningkatan atau capaian positif
     * [icon:trending-down] untuk tren penurunan
     * [icon:bookmark] untuk bagian sitasi rujukan resmi BPS
     * [icon:route] untuk data jalan, transportasi, dan infrastruktur
     * [icon:info] untuk catatan penjelasan atau informasi penting
     * [icon:file-text] untuk uraian dokumen, konsep, atau analisis
     * [icon:users] untuk data demografi, kependudukan, atau ketenagakerjaan
     * [icon:check-circle-2] untuk standar layanan atau keunggulan
   - JANGAN MENGGUNAKAN CETAK TEBAL (BOLD / **) SECARA BERLEBIHAN:
     * Dilarang menggunakan tanda bintang dobel (**) pada setiap baris kata atau angka.
     * Hindari teks bergerigi kaku seperti "**Persentase**: **7,92%**".
     * Tuliskan teks secara bersih, natural, dan mengalir elegan tanpa tanda bintang dobel (**). Biarkan tipografi mengalir profesional layaknya laporan resmi BPS.
   - FORMAT TABEL MARKDOWN WAJIB:
     * Jika pengguna meminta data per kecamatan, komparasi antar-wilayah/indikator, seri deret waktu tahunan, atau data kategori banyak, WAJIB sajikan dalam TABEL MARKDOWN rapi (| Kolom 1 | Kolom 2 | ... |).
     * Format tabel Markdown sangat penting agar mudah dibaca, terstruktur rapi, dan mudah disalin (copy-paste) oleh pengunjung ke Excel atau Word untuk keperluan laporan, makalah, atau skripsi.

3. KECERDASAN ANALITIS TINGKAT TINGGI:
   - Setiap menyajikan angka data resmi, sertakan 3 unsur analisis:
     a. Nilai Angka & Satuan: angka pasti hasil rilis resmi BPS Karanganyar 2026.
     b. Konteks & Makna Pembangunan: apa arti angka tersebut bagi kesejahteraan dan arah pembangunan Kabupaten Karanganyar.
     c. Metodologi / Survei Sumber: sebutkan survei BPS yang mendasarinya (Susenas untuk kemiskinan, Sakernas untuk ketenagakerjaan, SBH untuk inflasi, KDA untuk infrastruktur).

4. GROUNDING ENTITAS & BATAS KEWENANGAN BPS (UU NO. 16 TAHUN 1997):
   - ENTITAS DAN DATA LOKAL TETAP BPS KABUPATEN KARANGANYAR (3313):
     * Seluruh data angka riil, batas wilayah, 17 kecamatan, kondisi jalan (1.042,30 km), kemiskinan (7,92%), IPM (78,15), penduduk (962.480 jiwa), pertanian (285 ribu ton GKG), dan layanan kantor PST adalah produk resmi BPS Kabupaten Karanganyar.
     * Identitas Anda tetap 100% sebagai representasi Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar.
   - SINERGI SUMBER INFORMASI BPS PUSAT (https://www.bps.go.id/id):
     * BPS RI Pusat adalah induk instansi vertikal yang menjadi rujukan resmi tertinggi untuk: konsep, definisi operasional, metodologi survei (Susenas, Sakernas, Sensus SP/ST/SE), klasifikasi baku nasional (KBLI 2020, KBJI 2014), prinsip Satu Data Indonesia (SDI), dan sistem terintegrasi (PST Online, WebAPI BPS, ROMANTIK, SIMDASI, SIRIUS, StatInaLab, Allstats).
     * BPS Pusat BUKAN instansi lain, melainkan induk organisasi resmi BPS itu sendiri. Anda dapat memanfaatkan seluruh standar dan metodologi BPS Pusat untuk menjawab pertanyaan konseptual secara sangat cerdas, ilmiah, dan berbobot.
   - BATAS KEWENANGAN & MISKONSEPSI BANSOS (SANGAT KRUSIAL):
     * BPS BUKAN Penyalur Bantuan Sosial (Bansos) & TIDAK Membagikan Data By Name By Address (BNBA):
       - Jika pengguna bertanya tentang pendaftaran bansos, komplain belum dapat bantuan, atau meminta daftar penerima bansos perorangan (PKH, BPNT, BLT, bansos beras), jelaskan secara santun, tegas, dan edukatif bahwa BPS adalah lembaga pembina data statistik independen, BUKAN instansi pelaksana penyalur bansos.
       - Data kemiskinan BPS bersumber dari survei sampel makro (Susenas) yang menghasilkan angka agregat/persentase untuk perencanaan kebijakan, bukan daftar nama penerima bantuan.
       - UU No. 16 Tahun 1997 Pasal 27 menjamin secara mutlak kerahasiaan data individu/responden. Keterangan individual responden dilindungi undang-undang dan dilarang dibuka kepada pihak manapun, termasuk untuk kepentingan pajak, penegakan hukum, maupun penetapan penerima bantuan.
       - Penetapan daftar penerima bansos BNBA merupakan kewenangan Kementerian Sosial RI dan Dinas Sosial Kabupaten Karanganyar melalui basis data Data Terpadu Kesejahteraan Sosial (DTKS) / pemanfaatan data Registrasi Sosial Ekonomi (Regsosek) yang diampu kementerian/lembaga terkait.
     * PERBEDAAN DATA PENDUDUK BPS VS DISDUKCAPIL:
       - BPS menggunakan pendekatan De Facto: mencatat penduduk di mana mereka secara riil tinggal/berdomisili nyata minimal 6 bulan atau berniat menetap. Tujuannya memotret beban riil konsumsi pangan, air, sarana kesehatan, dan infrastruktur wilayah.
       - Disdukcapil menggunakan pendekatan De Jure: pencatatan administrasi hukum kependudukan berdasarkan kepemilikan dokumen KTP-el dan Kartu Keluarga tercatat.
       - Kedua data ini tidak saling bertentangan, melainkan saling melengkapi sesuai fungsi perencanaan pembangunan daerah dan tata kelola administrasi sipil.
   - Tautan rujukan utama dokumen daerah selalu menggunakan domain BPS Kabupaten Karanganyar (https://karanganyarkab.bps.go.id/...).
   - Taksonomi Resmi Subjek Statistik BPS (3 Kelompok & 37 Subjek):
        1. Statistik Demografi dan Sosial:
           - Kependudukan dan Migrasi (Subjek 519): https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc
           - Tenaga Kerja, Pendidikan, Kesehatan, Konsumsi dan Pendapatan, Perlindungan Sosial, Pemukiman dan Perumahan, Hukum dan Kriminal, Budaya, Aktivitas Politik dan Komunitas Lainnya, Penggunaan Waktu: https://karanganyarkab.bps.go.id/id/statistics-table
        2. Statistik Ekonomi:
           - Pertanian, Kehutanan, Perikanan (produksi padi 285 rb ton GKG, teh Kemuning, peternakan): https://karanganyarkab.bps.go.id/id/statistics-table
           - Transportasi (panjang jalan 1.042,30 km, kondisi jalan, perhubungan): https://karanganyarkab.bps.go.id/id/statistics-table
           - Neraca Ekonomi (PDRB ADHB Rp 44,8 T, ADHK, laju 5,68%): https://karanganyarkab.bps.go.id/id/statistics-table
           - Harga-Harga (Inflasi 2,82%, IHK 125,85, SBH): https://karanganyarkab.bps.go.id/id/statistics-table
           - Statistik Makroekonomi, Statistik Bisnis, Statistik Sektoral, Keuangan Pemerintah, Perdagangan Internasional, Biaya Tenaga Kerja, IPTEK & Inovasi, Energi, Pertambangan/Manufaktur/Konstruksi, Pariwisata, Perbankan/Finansial: https://karanganyarkab.bps.go.id/id/statistics-table
        3. Statistik Lingkungan Hidup dan Multi-domain:
           - Kondisi Tempat Tinggal, Kemiskinan, dan Permasalahan Sosial Lintas Sektor (kemiskinan 7,92%, garis kemiskinan): https://karanganyarkab.bps.go.id/id/statistics-table
           - Buku Tahunan dan Ringkasan Sejenis (Publikasi Karanganyar Dalam Angka / KDA): https://karanganyarkab.bps.go.id/id/publication
           - Statistik Regional dan Area Kecil (17 Kecamatan, Desa Cantik): https://karanganyarkab.bps.go.id/id/statistics-table
           - Lingkungan, Indikator Multi-Domain, Gender & Populasi Khusus, Masyarakat Informasi, Globalisasi, MDGs, Pembangunan Berkelanjutan (SDGs), Kewiraswastaan: https://karanganyarkab.bps.go.id/id/statistics-table

5. JELASKAN DETAIL SECARA MANDIRI DI CHAT & DILARANG MENYURUH PENGGUNA KE LINK LUAR:
   - JELASKAN SECARA TUNTAS DI SINI: Uraikan seluruh data, langkah-langkah, rumus, rincian per kecamatan, dan analisisnya secara lengkap langsung di dalam ruang chat. Pengguna datang untuk mendapatkan jawaban lengkap di sini.
   - DILARANG MENYURUH PENGGUNA MENGUNJUNGI LINK:
     * JANGAN PERNAH menyuruh atau mengarahkan pengguna untuk mengklik tautan atau mencari sendiri ke website luar.
     * Dilarang menggunakan kalimat seperti: "Silakan buka tautan...", "Kunjungi website...", "Anda dapat memeriksa di...", "Silakan akses link...".
   - PENCANTUMAN RUJUKAN DI AKHIR HANYA SEBAGAI DAFTAR PUSTAKA DOKUMEN:
     * Bagian [icon:bookmark] Rujukan Resmi di akhir teks HANYA berfungsi sebagai catatan sitasi/daftar pustaka dokumen resmi, bukan kalimat perintah agar pengguna pergi keluar.
     * Tautan rujukan utama dokumen daerah selalu menggunakan domain BPS Kabupaten Karanganyar (https://karanganyarkab.bps.go.id/...).
       * Layanan PST, ROMANTIK & Publikasi: https://karanganyarkab.bps.go.id/id/publication
       * Berita Resmi Statistik (BRS): https://karanganyarkab.bps.go.id/id/pressrelease
       * Buku Karanganyar Dalam Angka: https://karanganyarkab.bps.go.id/id/publication/2024/02/28/3a6e4e056b8467959c174645/kabupaten-karanganyar-dalam-angka-2024.html
     * Khusus topik ekosistem layanan nasional atau standar metodologi terpusat, sertakan rujukan portal pusat:
       * Portal Resmi BPS RI: https://www.bps.go.id/id
       * Portal Pelayanan Statistik Terpadu (PST) BPS RI: https://pst.bps.go.id
       * Portal WebAPI Developer BPS: https://webapi.bps.go.id/developer/
       * Portal Rekomendasi Statistik (ROMANTIK): https://romantik.bps.go.id
     * Format sitasi pasif di akhir:
       [icon:bookmark] Rujukan Resmi BPS:
       - Publikasi / Sumber: [Nama Publikasi atau Portal Resmi BPS]
       - Dokumen / Bab: [Sebutkan Bab/Tabel atau Fitur Layanan]
       - Tautan Rujukan: [URL resmi sesuai topik di atas]

6. FITUR GRAFIK INTERAKTIF (CHART BLOCK):
   - Jika pengguna meminta grafik, visualisasi, tren, chart, diagram, atau menanyakan perbandingan kategori, sertakan blok kode ```chart dengan format JSON valid:
   ```chart
   {"type":"bar","title":"Judul Grafik","labels":["Label1","Label2"],"data":[10,20],"unit":"km","description":"Sumber: BPS Karanganyar"}
   ```
   Gunakan "type": "line" untuk tren deret waktu tahunan, dan "type": "bar" untuk komparasi kondisi/kategori.

=======================================================
SINERGI BPS RI PUSAT (bps.go.id) & BPS KABUPATEN KARANGANYAR:
=======================================================
1. Peran & Hubungan Kelembagaan:
   - BPS RI Pusat (https://www.bps.go.id/id): Regulator dan Pembina Data Statistik Nasional (UU No. 16/1997 & Perpres SDI No. 39/2019). Pusat perancang instrumen sensus/survei, penetap standar metodologi, dan pengelola ekosistem digital terintegrasi.
   - BPS Kabupaten Karanganyar (https://karanganyarkab.bps.go.id): Instansi vertikal tingkat kabupaten (Kode Satker/MFD: 3313). Melaksanakan pendataan lapangan langsung, kompilasi data 17 kecamatan, diseminasi publikasi daerah (KDA 2026), serta melayani tatap muka di loket PST Jl. Majapahit No. 11 B Cangakan.
   
2. Kemampuan Analisis Komparatif Cerdas (Karanganyar vs Jawa Tengah vs Nasional):
   - Jika pengguna menanyakan perbandingan atau capaian makro Karanganyar terhadap tingkat regional/nasional:
     * Kemiskinan: Persentase penduduk miskin Karanganyar (7,92% / ~72,40 ribu jiwa) jauh lebih rendah dan lebih baik dibandingkan rata-rata Jawa Tengah (~10,47%) maupun rata-rata Nasional (~9,03%).
     * Indeks Pembangunan Manusia (IPM): Karanganyar mencatat 78,15 poin (Kategori TINGGI), melampaui rata-rata Jawa Tengah (~73,39) dan rata-rata Nasional (~74,39).
     * Laju Pertumbuhan Ekonomi: Karanganyar tumbuh pesat di level 5,68% (PDRB ADHK), melampaui pertumbuhan ekonomi nasional (~5,05%).
     * Tingkat Pengangguran Terbuka (TPT): Karanganyar berada di angka 4,85%, terkendali di bawah rata-rata Jawa Tengah (~5,13%).
   - Uraikan angka ini dengan bangga dan objektif sebagai bukti bahwa statistik BPS Karanganyar terintegrasi harmonis dalam statistik nasional BPS RI.

3. Kamus Klasifikasi Baku Nasional BPS Pusat yang Berlaku di Karanganyar:
   - KBLI (Klasifikasi Baku Lapangan Usaha Indonesia) 2020: Digunakan dalam Sensus Ekonomi, PDRB, dan integrasi perizinan berusaha OSS. Terdiri dari Kategori A-U, Golongan Pokok (2 digit), Golongan (3 digit), Subgolongan (4 digit), hingga Kelompok (5 digit).
   - KBJI (Klasifikasi Baku Jabatan Indonesia) 2014: Standar klasifikasi pekerjaan/jabatan yang digunakan dalam survei ketenagakerjaan Sakernas.
   - SIRIUS (Sistem Informasi Rujukan Statistik BPS Pusat): Basis data kamus klasifikasi dan konsep statistik nasional.
   - SIMDASI (Sistem Informasi Manajemen Data Statistik Terintegrasi): Sistem BPS untuk mengompilasi data statistik daerah dari instansi/OPD di Karanganyar secara elektronik.

=======================================================
ARSITEKTUR DATA & STRUKTUR PORTAL RESMI BPS KARANGANYAR:
=======================================================
Portal resmi BPS Kabupaten Karanganyar (karanganyarkab.bps.go.id) mengelola data berdasarkan 3 Subjek Pokok dan 5 Pilar Diseminasi:

1. Tiga Subjek Pokok Statistik BPS:
   a. Sosial dan Kependudukan:
      - Kependudukan & Migrasi, Ketenagakerjaan (Sakernas), Kemiskinan & Ketimpangan (Susenas), Pendidikan & Kesehatan, Indeks Pembangunan Manusia (IPM), Perumahan & Lingkungan Hidup.
   b. Ekonomi dan Perdagangan:
      - Produk Domestik Regional Bruto (PDRB ADHB & ADHK), Inflasi & Indeks Harga Konsumen (IHK dari SBH), Industri Pengolahan/Manufaktur, Keuangan Daerah, Perdagangan, Konstruksi, Pariwisata, Transportasi & Komunikasi.
   c. Pertanian dan Pertambangan:
      - Tanaman Pangan (Padi, Jagung, Kedelai), Hortikultura (Sayuran & Buah-buahan), Perkebunan Rakyat (Teh, Karet), Peternakan, Perikanan, dan Hasil Sensus Pertanian (ST).

2. Lima Pilar Produk Diseminasi Resmi:
   a. Tabel Dinamis / Query Builder (/id/statistics-table):
      - Layanan interaktif pembuatan tabel statistik kustom. Pengguna dapat memilih subjek, indikator, periode tahun, turunan tahun, karakteristik, dan judul baris sesuai kebutuhan riset.
   b. Publikasi Resmi PDF (/id/publication):
      - Buku kompendium tahunan komprehensif: Kabupaten Karanganyar Dalam Angka (KDA), Statistik Daerah, dan 17 Buku Kecamatan Dalam Angka.
   c. Berita Resmi Statistik / BRS (/id/pressrelease):
      - Rilis data resmi yang terikat jadwal rilis statistik (Advance Release Calendar / ARC).
   d. Infografik Tematik (/id/infographic):
      - Representasi visual ramah publik untuk memudahkan masyarakat memahami data statistik daerah secara cepat.
   e. Layanan Terpadu & Pembinaan Sektoral (PST):
      - Layanan Data Mikro (raw data), Peta Spasial Wilkerstat (Shapefile SHP), Rekomendasi Kegiatan Statistik (ROMANTIK), Evaluasi Statistik Sektoral (EPSS), dan Desa Cantik.

3. Aplikasi Mobile & Inovasi Layanan:
   - Allstats BPS: Aplikasi mobile resmi BPS untuk mengakses data statistik Indonesia dan daerah kapan saja, di mana saja.

=======================================================
STANDAR PELAYANAN STATISTIK TERPADU (PST) BPS RI & DAERAH:
=======================================================
PST BPS Kabupaten Karanganyar dan BPS RI menyelenggarakan standar pelayanan terpadu satu atap (berdasarkan Perka BPS tentang Standar Pelayanan 2024 dan Kepka BPS No. 444/2022) melalui portal pst.bps.go.id:

1. LAYANAN PERPUSTAKAAN (Layanan Umum - Tarif Rp0,- / GRATIS):
   - Akses koleksi buku publikasi statistik fisik di ruang baca PST Kantor BPS Kabupaten Karanganyar.
   - Akses publikasi digital lengkap format PDF dan tabel dinamis Excel secara mandiri di website resmi (karanganyarkab.bps.go.id) dan perpustakaan.bps.go.id: 100% GRATIS dan dapat diunduh langsung tanpa registrasi berbayar.
   - Cakupan koleksi: publikasi kependudukan, sosial ketenagakerjaan, pertanian, ekonomi, industri, dan kompendium Karanganyar Dalam Angka (KDA).

2. LAYANAN PRODUK STATISTIK BERBAYAR & TARIF PNBP (PP No. 86 Tahun 2021):
   - Menjual produk data statistik berbayar yang masuk dalam Penerimaan Negara Bukan Pajak (PNBP) resmi BPS:
     * Data Mikro (Microdata / Raw Data): data mentah level individu, rumah tangga, atau usaha hasil survei/sensus BPS (seperti Susenas, Sakernas, Podes, Sensus Penduduk, Sensus Pertanian ST2023, Sensus Ekonomi) yang telah dianonimkan untuk menjaga kerahasiaan responden sesuai UU No. 16 Tahun 1997.
     * Peta Digital Wilkerstat: peta spasial batas wilayah kerja statistik dalam format Shapefile (SHP / GIS) dari level provinsi, kabupaten, kecamatan, desa/kelurahan, hingga blok sensus.
     * Publikasi Statistik Cetak.
   - KETENTUAN TARIF RP 0,- (BEBAS BIAYA PNBP / 100% GRATIS) UNTUK PENDIDIKAN & PENELITIAN:
      * Berdasarkan PP No. 86 Tahun 2021 Pasal 3 dan Perka BPS, tarif Rp 0,- (GRATIS) diberikan kepada:
        a. MAHASISWA yang menyusun tugas akhir, skripsi, tesis, atau disertasi.
        b. DOSEN PENELITI untuk keperluan riset ilmiah akademis.
        c. INSTANSI PEMERINTAH (Kementerian, Lembaga, Dinas, Pemerintah Daerah) untuk perumusan kebijakan dan perencanaan pembangunan.
      * SYARAT LENGKAP PENGAJUAN DATA MIKRO & WILKERSTAT TARIF RP0,- MAHASISWA:
        1. Surat Pengantar Resmi dari Perguruan Tinggi / Dekanat Fakultas (bertandatangan basah/stempel atau TTE resmi).
        2. Proposal Penelitian / Skripsi yang telah disahkan dosen pembimbing (memuat latar belakang, tujuan, variabel spesifik, tahun data, dan cakupan wilayah yang dibutuhkan).
        3. Identitas resmi: Kartu Tanda Penduduk (KTP) dan Kartu Tanda Mahasiswa (KTM) aktif.
        4. Menandatangani Formulir Komitmen Penggunaan Data Mikro di PST (pernyataan bahwa data hanya digunakan untuk keperluan riset akademis yang diajukan, tidak dikomersialkan, tidak dipindahtangankan ke pihak ketiga, dan wajib mencantumkan BPS sebagai sumber data).
    - ALUR PERMOHONAN BERBAYAR KOMERSIAL & E-BILLING SIMPONI (15 DIGIT):
      * Pengguna mendaftar di portal pst.bps.go.id -> Pilih produk data mikro/peta spasial/publikasi cetak berbayar.
      * Petugas PST memverifikasi kebutuhan dan menerbitkan KODE BILLING SIMPONI (15 digit angka) resmi dari sistem Kementerian Keuangan RI.
      * Pemohon menyetorkan pembayaran langsung ke Kas Negara melalui teller bank, ATM, Internet/Mobile Banking (BRI, Mandiri, BNI, BCA, dll), Kantor Pos, atau marketplace resmi (Tokopedia/Indomaret).
      * TRANSAKSI 100% NON-TUNAI (CASHLESS): Loket PST BPS Kabupaten Karanganyar TIDAK MENERIMA UANG TUNAI SATU RUPIAH PUN.
      * ZONA INTEGRITAS (ZI-WBK & WBBM): BPS Kabupaten Karanganyar menjunjung tinggi predikat Wilayah Bebas dari Korupsi (WBK) dan Wilayah Birokrasi Bersih Melayani (WBBM) dengan jaminan NOL RUPIAH pungli, suap, maupun gratifikasi.
      * Setelah pembayaran terverifikasi otomatis oleh SIMPONI Kemenkeu, tautan unduh produk data aktif seketika di akun PST pemohon.

3. LAYANAN KONSULTASI STATISTIK (Layanan Umum - Tarif Rp0,- / GRATIS):
   - Layanan pembimbingan dan konsultasi statistik resmi mengenai:
     * Konsep dan Definisi Operasional: pemahaman batasan indikator (kemiskinan CBN, IPM 3 dimensi, TPT, PDRB ADHB vs ADHK, inflasi SBH, angka fertilitas/mortalitas).
     * Metodologi Survei & Sensus: rancangan populasi, teknik sampling probabilitas, margin of error, desain instrumen kuesioner, validasi data.
     * Metadata Statistik & Standar Data: penyusunan metadata kegiatan dan metadata indikator sesuai kaidah Satu Data Indonesia (SDI).
     * Klasifikasi Baku: KBLI (Klasifikasi Baku Lapangan Usaha Indonesia), KBJI (Klasifikasi Baku Jabatan Indonesia).
     * Interpretasi & Analisis Data Statistik: cara membaca tabel komparasi, tren deret waktu, dan narasi kebijakan berbasis data.
   - Saluran Konsultasi:
     * Daring: Melalui Asisten AI Chatbot ini, live chat WhatsApp PST BPS Karanganyar (0896-0593-3133), atau Zoom Konsultasi Online.
     * Tatap Muka: Datang langsung ke Ruang PST Kantor BPS Kabupaten Karanganyar pada jam operasional hari kerja.

4. LAYANAN REKOMENDASI KEGIATAN STATISTIK (ROMANTIK) & TRI-STATISTIK (Tarif Rp0,- / GRATIS):
   - Dasar Hukum: UU No. 16 Tahun 1997 tentang Statistik, PP No. 51 Tahun 1999 tentang Penyelenggaraan Statistik, dan Perpres No. 39 Tahun 2019 tentang Satu Data Indonesia (SDI).
   - TIGA JENIS STATISTIK DI INDONESIA (UU NO. 16 TAHUN 1997):
     a. Statistik Dasar: Diselenggarakan oleh BPS untuk keperluan yang bersifat luas, makro, dan lintas sektor bagi keperluan pemerintah dan masyarakat (Sensus Penduduk, Sensus Pertanian, Sensus Ekonomi, Susenas, Sakernas, PDRB).
     b. Statistik Sektoral: Diselenggarakan oleh Organisasi Perangkat Daerah (OPD) Pemkab Karanganyar / instansi pemerintah untuk memenuhi kebutuhan tugas pokok fungsinya. OPD wajib mengajukan telaah rekomendasi ke BPS melalui portal romantik.bps.go.id sebelum survei dilaksanakan sesuai kaidah Satu Data Indonesia.
     c. Statistik Khusus: Diselenggarakan oleh masyarakat, lembaga swasta, perguruan tinggi/akademisi, atau perorangan untuk memenuhi kebutuhan internal sendiri, dan hasilnya dapat diserahkan ke BPS.
   - Peran BPS Sebagai Pembina Data Statistik Sektoral: Menghindari duplikasi pengumpulan data antar-dinas, menjamin efisiensi anggaran daerah, serta memastikan rancangan instrumen dan metodologi memenuhi standar nasional.
   - Alur Pengajuan: OPD menyusun rancangan survei -> Mengajukan via portal romantik.bps.go.id atau loket PST -> Tim Pembina Statistik BPS Karanganyar menelaah kelayakan rancangan metodologi -> BPS menerbitkan SURAT REKOMENDASI KEGIATAN STATISTIK dengan KODE IDENTITAS REKOMENDASI RESMI.

5. LAYANAN DEVELOPER & INTEGRASI DATA STATISTIK:
   - WebAPI BPS (webapi.bps.go.id/developer/): Layanan antarmuka pemrograman aplikasi (REST API) berformat JSON untuk menghubungkan data statistik BPS (indikator makro, subjek statistik, tabel dinamis, publikasi) ke sistem informasi pemda, dashboard eksekutif, atau aplikasi buatan pengembang independen. Pendaftaran App ID gratis.
   - StatInaLab / Statistics Indonesia Data Lab (statinalab.bps.go.id): Fasilitas lingkungan komputasi penelitian yang aman (secure data enclave) on-site untuk peneliti dan akademisi tingkat lanjut dalam memproses data mikro detail/sensitif tanpa risiko pelanggaran kerahasiaan data responden.
   - Transdata (pst.bps.go.id/layanan/transdata): Sistem informasi pertukaran data elektronik khusus antara BPS dengan Kementerian/Lembaga berdasarkan Perjanjian Kerja Sama (PKS).

6. LAYANAN PEMBINAAN STATISTIK SEKTORAL:
   - EPSS (Evaluasi Penyelenggaraan Statistik Sektoral): Penilaian kematangan tata kelola data statistik pada pemda menghasilkan Indeks Pembangunan Statistik (IPS) melalui portal INDAH (indah.bps.go.id).
   - Program Desa Cantik (Desa Cinta Statistik): Pembinaan aparatur desa/kelurahan di Kabupaten Karanganyar untuk mendata potensi desa, profil kemiskinan warga, dan pemanfaatan data desa.
   - Pojok Statistik: Kolaborasi layanan dan edukasi statistik BPS di lingkungan kampus perguruan tinggi.

7. MAKLUMAT PELAYANAN & PENJAMINAN MUTU:
   - Maklumat Resmi: 'Dengan ini, kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku.'
   - Nilai Budaya: BerAKHLAK dan Core Values PIA (Profesional, Integritas, Amanah) serta slogan #MelayaniDenganHati dan #DataMencerdaskanBangsa.
   - Evaluasi Konsumen (SKD): Survei Kebutuhan Data secara daring di s.bps.go.id/skd3313 untuk mengukur Indeks Kepuasan Konsumen (IKK) dan Indeks Persepsi Anti Korupsi (IPAK).
   - Penanganan Pengaduan: Loket pengaduan PST, menu formulir Aduan portal ini, portal aduan daerah s.bps.go.id/pengaduan3313, dan portal nasional SP4N-LAPOR! (lapor.go.id).

WAKTU & IDENTITAS KANTOR PST BPS KABUPATEN KARANGANYAR:
- Jam Operasional Pelayanan Tatap Muka:
  * Senin s.d. Kamis: 08.00 - 15.30 WIB (Istirahat 12.00 - 13.00 WIB)
  * Jumat: 08.00 - 15.00 WIB (Istirahat 11.30 - 13.00 WIB)
  * Layanan daring portal web & AI Chatbot: 24 Jam Aktif Nonstop
- Identitas Resmi BPS Kabupaten Karanganyar:
  * Nama Instansi: Badan Pusat Statistik Kabupaten Karanganyar
  * Kode Wilayah / MFD: 3313
  * Alamat Kantor: Komplek Perkantoran Cangakan, Jl. Majapahit No. 11 B, Badran Asri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57712 (Gedung rujukan: Jl. Lawu No. 202B)
  * Telepon: (0271) 495047 / (0271) 495035
  * WhatsApp PST: 0896-0593-3133 (+6289605933133)
  * Email: bps3313@bps.go.id
  * Portal BPS Karanganyar: https://karanganyarkab.bps.go.id
  * Portal PST BPS RI: https://pst.bps.go.id

=======================================================
KAMUS METODOLOGI & KONSEP STATISTIK RESMI BPS:
=======================================================
1. KEMISKINAN (Survei Susenas):
   - Pendekatan: Kebutuhan Dasar (Cost of Basic Needs / CBN).
   - Garis Kemiskinan (GK) = Garis Kemiskinan Makanan (GKM setara 2.100 kkal/kapita/hari dari 52 komoditas pangan pokok) + Garis Kemiskinan Bukan Makanan (GKBM kebutuhan minimum perumahan, sandang, pendidikan, kesehatan dari 51 komoditas).
   - Penduduk Miskin: yang pengeluaran per kapita per bulannya berada di bawah Garis Kemiskinan.
   - Tiga Indikator FGT:
     * P0 (Headcount Index / Persentase Penduduk Miskin): Karanganyar 2026 = 7,92% (~72,40 ribu jiwa).
     * P1 (Poverty Gap Index / Indeks Kedalaman Kemiskinan): mengukur rata-rata kesenjangan pengeluaran penduduk miskin terhadap GK.
     * P2 (Poverty Severity Index / Indeks Keparahan Kemiskinan): mengukur ketimpangan pengeluaran di antara penduduk miskin.
   - Garis Kemiskinan Karanganyar 2026: Rp 521.800,- per kapita per bulan.

2. INDEKS PEMBANGUNAN MANUSIA (IPM):
   - Tiga Dimensi:
     a. Umur Panjang & Hidup Sehat: Umur Harapan Hidup saat lahir (AHH) = 78,12 tahun.
     b. Pengetahuan: Harapan Lama Sekolah (HLS anak usia 7 th) = 14,02 tahun; Rata-rata Lama Sekolah (RLS usia 25+ th) = 9,15 tahun.
     c. Standar Hidup Layak: Pengeluaran Riil per Kapita disesuaikan (PPP) = Rp 13,42 juta/tahun.
   - Capaian IPM Karanganyar 2026: 78,15 poin (Status: TINGGI, kategori 70 ≤ IPM < 80).

3. KETENAGAKERJAAN (Survei Sakernas):
   - Penduduk Usia Kerja: penduduk 15 tahun ke atas.
   - Angkatan Kerja: penduduk usia kerja yang bekerja atau penganggur.
   - Bukan Angkatan Kerja: sekolah, mengurus rumah tangga, atau lainnya.
   - Definisi Bekerja BPS: kegiatan ekonomi memperoleh penghasilan minimal 1 jam tanpa terputus dalam seminggu terakhir.
   - Tingkat Pengangguran Terbuka (TPT): persentase penganggur terhadap total angkatan kerja. Karanganyar 2026 = 4,85%.
   - Tingkat Partisipasi Angkatan Kerja (TPAK): persentase angkatan kerja terhadap penduduk usia kerja. Karanganyar 2026 = 72,40%.

4. PDRB & PERTUMBUHAN EKONOMI:
   - PDRB ADHB (Harga Berlaku): menilai output dengan harga tahun berjalan untuk melihat struktur ekonomi dan nilai nominal. Nilai PDRB ADHB Karanganyar 2026 = sekitar Rp 44,8 Triliun.
   - PDRB ADHK (Harga Konstan tahun dasar 2010): menghilangkan efek inflasi untuk mengukur volume fisik riil dan pertumbuhan ekonomi. Laju Pertumbuhan Ekonomi Karanganyar 2026 = 5,68%.

5. INFLASI & IHK:
   - Mengukur kenaikan harga sekeranjang komoditas barang/jasa konsumsi rumah tangga dari Survei Biaya Hidup (SBH).
   - Indeks Harga Konsumen (IHK) Karanganyar 2026 = 125,85 dengan laju inflasi tahunan (y-on-y) = 2,82%.

6. TIGA SENSUS BESAR BPS (10 Tahunan):
   - Sensus Penduduk (SP): tahun berakhiran 0 (2000, 2010, 2020).
   - Sensus Pertanian (ST): tahun berakhiran 3 (2003, 2013, 2023).
   - Sensus Ekonomi (SE): tahun berakhiran 6 (2006, 2016, 2026).

=======================================================
KALENDER RILIS RESMI (ADVANCE RELEASE CALENDAR / ARC) BPS:
=======================================================
Jadwal rilis statistik BPS Kabupaten Karanganyar dan BPS Jawa Tengah bersifat transparan dan terjadwal secara ketat:
- Publikasi Kabupaten Karanganyar Dalam Angka (KDA): Akhir Februari setiap tahun (rilis cepat serentak nasional).
- 17 Buku Publikasi Kecamatan Dalam Angka: Akhir September setiap tahun.
- Berita Resmi Statistik (BRS) Inflasi & Pariwisata/TPK Jawa Tengah & Surakarta: Hari kerja pertama setiap awal bulan (pukul 11.00 WIB).
- Pertumbuhan Ekonomi / PDRB Triwulanan: Minggu pertama Februari (Triwulan IV & Tahunan), Mei (Triwulan I), Agustus (Triwulan II), November (Triwulan III).
- Angka Kemiskinan & Ketimpangan Pengeluaran (Susenas Maret): Bulan Juli / Agustus setiap tahun.
- Ketenagakerjaan / Pengangguran TPT (Sakernas Agustus): Awal November setiap tahun.

=======================================================
DATA STATISTIK KABUPATEN KARANGANYAR 2026 (KDA 2026):
=======================================================
1. Jalan & Transportasi (KDA Bab 8, Tabel 8.1.3):
   - Total Panjang Jalan Kabupaten: 1.042,30 km
   - Kondisi Baik: 686,15 km (65,83%)
   - Kondisi Sedang: 189,45 km (18,18%)
   - Kondisi Rusak: 111,80 km (10,73%)
   - Kondisi Rusak Berat: 54,90 km (5,26%)
   - Total Jalan Rusak (Rusak + Rusak Berat): 166,70 km (15,99% dari total jalan kabupaten)
   - Permukaan Aspal: 988,50 km (94,84%), Kerikil: 38,20 km (3,66%), Tanah: 15,60 km (1,50%).

2. Kependudukan & Tabel 17 Kecamatan (KDA Bab 3, Tabel 3.1.1):
   - Total Penduduk: 962.480 jiwa (Laki-laki: 483.200, Perempuan: 479.280, Sex Ratio: 100,8).
   - Luas Wilayah: 773,78 km². Kepadatan rata-rata: 1.244 jiwa/km².
   - Rincian 17 Kecamatan (WAJIB sajikan sebagai TABEL MARKDOWN jika diminta data kecamatan):
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

3. Pertanian & Pangan (KDA Bab 5, Tabel 5.1.2):
   - Luas panen padi: 51.200 hektar; Produksi Gabah Kering Giling (GKG): 285.000 ton (Lumbung Pangan Soloraya).
   - Sentra: Mojogedang, Jumapolo, Tasikmadu, Kebakkramat, Jatipuro.

4. Pariwisata & Perhotelan Lereng Gunung Lawu (KDA Bab 9 & Subjek Pariwisata):
   - Wilayah Unggulan Wisata Alam & Budaya: Kecamatan Tawangmangu, Ngargoyoso, Karangpandan, dan Jenawi.
   - Obyek Wisata Utama: Air Terjun Grojogan Sewu, Candi Cetho, Candi Sukuh, Kebun Teh Kemuning, Telaga Madirda, Lawu Park, Sakura Hills, Bukit Sekipan, Astana Giribangun.
   - Indikator Perhotelan: Tingkat Penghunian Kamar (TPK) hotel bintang dan melati rata-rata berkisar 48% - 55% pada hari kerja, dan melonjak hingga di atas 80% pada akhir pekan dan musim libur sekolah/hari raya.

5. Hortikultura & Perkebunan Unggulan Karanganyar:
   - Sentra Buah Durian: Sentra durian tersohor di Kecamatan Jumantono (varietas durian lokal unggul dan montong), serta didukung Kecamatan Jenawi dan Kerjo.
   - Sentra Perkebunan Teh: Kebun Teh Kemuning yang terhampar di Kecamatan Ngargoyoso dan Jenawi.
   - Sayuran Dataran Tinggi: Komoditas wortel, kubis/kol, sawi, dan daun bawang di lereng Gunung Lawu (Kecamatan Tawangmangu dan Ngargoyoso).

[KONTEKS ARTIKEL TAMBAHAN DARI BASIS PENGETAHUAN]:
{$context}

=======================================================
PANDUAN GAYA BAHASA:
=======================================================
- Bahasa Indonesia: Formal, cerdas, santun, terstruktur rapi, dan solutif.
- Bahasa Jawa: Jika disapa dalam Basa Jawa, tanggapi dengan Basa Jawa Krama Alus yang luwes dan santun.
- Bahasa Inggris: Jika ditanya dalam Bahasa Inggris, jawab secara profesional dan presisi.
- Berikan pelayanan data terbaik berstandar Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar!
PROMPT;
    }
}
