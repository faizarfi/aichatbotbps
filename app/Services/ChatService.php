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

        // A. Permintaan Hubungi Petugas Manusia
        if (preg_match('/(hubungi|bicara|ngobrol|chat|telepon|sambungkan)?\s*(dengan\s*)?(petugas|operator|admin|orang|manusia|cs)\b/i', $lower)) {
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

        // B. Niat Pengaduan / Keluhan Spesifik
        if (preg_match('/(mau|ingin|cara)?\s*(buat|kirim|lapor|adukan|ajukan)\s*(aduan|keluhan|komplain|laporan)/i', $lower) && !str_contains($lower, 'status')) {
            return [
                'reply' => "Untuk menyampaikan aduan atau aspirasi resmi terkait pelayanan BPS Kabupaten Karanganyar, silakan mengisi Formulir Aduan melalui menu: <a href='/aduan' class='text-blue-600 font-bold underline'>Buat Aduan Layanan</a>.\n\nSetiap laporan akan mendapatkan Nomor Tiket Resmi untuk pemantauan tindak lanjut berkala.",
                'sources' => [
                    ['title' => 'Formulir Pengaduan Masyarakat', 'url' => '/aduan']
                ],
                'confidence' => 1.0,
                'is_fallback' => false,
                'quick_options' => [
                    'Cek Status Aduan',
                    'Jadwal layanan PST',
                ],
            ];
        }

        // C. Cari data relevan di Basis Pengetahuan (Knowledge Search)
        $searchResult = $this->searchService->search($message);
        $articles = [];
        $sources = [];

        if ($searchResult['bestMatch']) {
            $articles[] = $searchResult['bestMatch'];
            if ($searchResult['bestMatch']->source_title) {
                $sources[] = [
                    'title' => $searchResult['bestMatch']->source_title,
                    'url' => $searchResult['bestMatch']->source_url ?: 'https://karanganyarkab.bps.go.id',
                ];
            }
        }

        // D. Coba panggil AI LLM Gateway (9router / OpenRouter) dengan RAG
        if ($this->aiService->isConfigured()) {
            $history = $conversation->messages()
                ->latest()
                ->take(6)
                ->get()
                ->reverse()
                ->map(fn($m) => ['sender_type' => $m->sender_type, 'content' => $m->content])
                ->toArray();

            $aiAnswer = $this->aiService->generateAnswer($message, $articles, $history);

            if (!empty($aiAnswer)) {
                if (empty($sources)) {
                    $sources[] = [
                        'title' => 'Basis Data Resmi BPS Kabupaten Karanganyar',
                        'url' => 'https://karanganyarkab.bps.go.id',
                    ];
                }

                return [
                    'reply' => $aiAnswer,
                    'sources' => $sources,
                    'confidence' => 0.95,
                    'is_fallback' => false,
                    'quick_options' => [
                        'Cara memperoleh data',
                        'Jadwal layanan PST',
                        'Hubungi Petugas',
                    ],
                ];
            }
        }

        // E. Fallback 1: Jika AI tidak merespon, gunakan artikel dari pencarian lokal jika ada
        if ($searchResult['bestMatch']) {
            return [
                'reply' => $searchResult['bestMatch']->answer,
                'sources' => $sources,
                'confidence' => $searchResult['confidence'],
                'is_fallback' => false,
                'quick_options' => [
                    'Cara memperoleh data',
                    'Hubungi Petugas',
                    'Layanan lainnya',
                ],
            ];
        }

        // F. Fallback 2: Pesan ramah jika tidak ditemukan
        $fallbackReply = "Mohon maaf, saya belum menemukan informasi spesifik mengenai pertanyaan Anda dalam basis data BPS Karanganyar.\n\nSilakan mencoba kata kunci lain (misalnya: *jumlah penduduk*, *kemiskinan*, *PDRB*, atau *jadwal PST*), atau klik tombol **Hubungi Petugas** untuk terhubung langsung dengan petugas Pelayanan Statistik Terpadu.";

        return [
            'reply' => $fallbackReply,
            'sources' => [
                ['title' => 'Portal BPS Karanganyar', 'url' => 'https://karanganyarkab.bps.go.id']
            ],
            'confidence' => 0.0,
            'is_fallback' => true,
            'quick_options' => [
                'Hubungi Petugas',
                'Cara memperoleh data',
                'Buat Aduan',
            ],
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
