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
            'status' => $conversation->fresh()->status,
            'sources' => $replyData['sources'] ?? [],
            'quick_options' => $replyData['quick_options'] ?? [],
            'is_fallback' => $replyData['is_fallback'] ?? false,
        ];
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
            'reply' => "Mohon maaf, saya belum menemukan jawaban yang tepat untuk pertanyaan tersebut dalam rujukan saat ini.\n\n💡 **Saran:** Anda dapat menanyakan data resmi seperti:\n- *Jumlah penduduk Karanganyar 2026*\n- *Angka kemiskinan atau IPM Karanganyar*\n- *Data 17 Kecamatan (misal: Tawangmangu, Colomadu)*\n- *Jadwal buka dan jam layanan kantor PST BPS*\n\nAtau klik tombol **Hubungi Petugas** di atas untuk berkomunikasi langsung dengan petugas kami.",
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
