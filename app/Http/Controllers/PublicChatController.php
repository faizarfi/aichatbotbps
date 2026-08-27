<?php

namespace App\Http\Controllers;

use App\Models\BotFeedback;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {}

    /**
     * Tampilkan halaman chat.
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Dapatkan riwayat pesan percakapan (untuk auto-refresh / polling).
     */
    public function messages(Request $request): JsonResponse
    {
        $sessionToken = $request->query('session');
        if (!$sessionToken) {
            return response()->json(['messages' => [], 'status' => 'bot']);
        }

        $conversation = Conversation::with(['messages.feedback', 'assignedOfficer'])
            ->where('visitor_session', $sessionToken)
            ->orWhere('public_id', $sessionToken)
            ->first();

        if (!$conversation) {
            return response()->json(['messages' => [], 'status' => 'bot']);
        }

        $messages = $conversation->messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_type === 'officer' ? ($msg->sender?->name ?? 'Petugas BPS') : ($msg->sender_type === 'bot' ? 'Asisten BPS' : 'Anda'),
                'content' => $msg->content,
                'sources' => $msg->knowledge_sources ?? [],
                'is_fallback' => (bool) $msg->is_fallback,
                'feedback' => $msg->feedback ? $msg->feedback->rating : null,
                'created_at' => $msg->created_at->format('H:i'),
            ];
        });

        return response()->json([
            'conversation_id' => $conversation->public_id,
            'status' => $conversation->status,
            'officer_name' => $conversation->assignedOfficer?->name,
            'messages' => $messages,
        ]);
    }

    /**
     * Kirim pesan baru dari pengunjung.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'session' => ['nullable', 'string', 'max:100'],
            'visitor_name' => ['nullable', 'string', 'max:100'],
        ]);

        $visitorName = $validated['visitor_name'] ?? (auth()->check() ? auth()->user()->name : null);

        $conversation = $this->chatService->getOrCreateConversation(
            $validated['session'] ?? null,
            $visitorName
        );

        $result = $this->chatService->processVisitorMessage($conversation, $validated['message']);

        return response()->json([
            'conversation_id' => $conversation->public_id,
            'session' => $conversation->visitor_session,
            'status' => $result['status'],
            'visitor_message' => [
                'id' => $result['visitor_message']->id,
                'content' => $result['visitor_message']->content,
                'created_at' => $result['visitor_message']->created_at->format('H:i'),
            ],
            'bot_message' => $result['bot_message'] ? [
                'id' => $result['bot_message']->id,
                'content' => $result['bot_message']->content,
                'sources' => $result['sources'],
                'is_fallback' => $result['is_fallback'],
                'created_at' => $result['bot_message']->created_at->format('H:i'),
            ] : null,
            'reply' => [
                'id' => $result['bot_message']?->id,
                'content' => $result['reply'] ?? ($result['bot_message']?->content ?? ''),
                'sources' => $result['sources'] ?? [],
                'is_fallback' => $result['is_fallback'] ?? false,
            ],
            'sources' => $result['sources'],
            'quick_options' => $result['quick_options'],
            'is_fallback' => $result['is_fallback'],
        ]);
    }

    /**
     * Pengunjung meminta pengalihan ke petugas manusia.
     */
    public function requestOfficer(Request $request): JsonResponse
    {
        $sessionToken = $request->input('session');
        if (!$sessionToken) {
            return response()->json(['error' => 'Sesi tidak ditemukan.'], 400);
        }

        $conversation = Conversation::where('visitor_session', $sessionToken)
            ->orWhere('public_id', $sessionToken)
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Percakapan tidak ditemukan.'], 404);
        }

        $conversation->update([
            'status' => 'waiting',
            'last_message_at' => now(),
        ]);

        $botMsg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'content' => 'Permintaan Anda telah kami catat. Anda sedang berada di antrean petugas BPS Kabupaten Karanganyar. Petugas kami akan segera membalas di ruangan chat ini.',
        ]);

        return response()->json([
            'success' => true,
            'status' => 'waiting',
            'message' => [
                'id' => $botMsg->id,
                'sender_type' => 'bot',
                'content' => $botMsg->content,
                'created_at' => $botMsg->created_at->format('H:i'),
            ]
        ]);
    }

    /**
     * Berikan feedback untuk pesan bot (helpful / not_helpful).
     */
    public function feedback(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message_id' => ['required', 'exists:messages,id'],
            'rating' => ['required', 'in:helpful,not_helpful'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $feedback = BotFeedback::updateOrCreate(
            ['message_id' => $validated['message_id']],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return response()->json(['success' => true, 'feedback' => $feedback]);
    }
}
