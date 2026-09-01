<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {}

    /**
     * Tampilkan daftar percakapan dengan filter status dan live badge.
     */
    public function index(Request $request)
    {
        $query = Conversation::with(['assignedOfficer', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->withCount('messages');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                  ->orWhere('public_id', 'like', "%{$search}%");
            });
        }

        $conversations = $query->orderByDesc('last_message_at')->paginate(15)->withQueryString();

        $statusCounts = Conversation::selectRaw('status, COUNT(*) as aggregate')->groupBy('status')->pluck('aggregate', 'status');
        $counts = [
            'all' => $statusCounts->sum(),
            'waiting' => $statusCounts->get('waiting', 0),
            'handled' => $statusCounts->get('handled', 0),
            'bot' => $statusCounts->get('bot', 0),
            'closed' => $statusCounts->get('closed', 0),
        ];

        return view('admin.conversations.index', compact('conversations', 'counts'));
    }

    /**
     * Tampilkan ruang obrolan / detail percakapan.
     */
    public function show(Conversation $conversation)
    {
        $conversation->load(['messages.sender', 'messages.feedback', 'assignedOfficer']);
        return view('admin.conversations.show', compact('conversation'));
    }

    /**
     * Endpoint JSON untuk real-time message polling di dashboard admin.
     */
    public function getMessages(Conversation $conversation): JsonResponse
    {
        $messages = $conversation->messages()->with('sender')->get()->map(function (Message $msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_type === 'officer' ? ($msg->sender?->name ?? 'Petugas') : ($msg->sender_type === 'bot' ? 'Bot' : 'Pengunjung'),
                'content' => $msg->content,
                'sources' => $msg->knowledge_sources ?? [],
                'is_fallback' => (bool) $msg->is_fallback,
                'created_at' => $msg->created_at->format('H:i:s'),
                'created_at_full' => $msg->created_at->format('d M Y, H:i'),
            ];
        });

        return response()->json([
            'status' => $conversation->status,
            'assigned_to' => $conversation->assigned_to,
            'officer_name' => $conversation->assignedOfficer?->name,
            'messages' => $messages,
        ]);
    }

    /**
     * Petugas mengirim balasan ke pengunjung.
     */
    public function reply(Request $request, Conversation $conversation): JsonResponse
    {
        $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $message = $this->chatService->sendOfficerReply(
            $conversation,
            $user->id,
            $request->input('content')
        );

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => 'officer',
                'sender_name' => $user->name,
                'content' => $message->content,
                'created_at' => $message->created_at->format('H:i:s'),
            ],
            'status' => $conversation->fresh()->status,
        ]);
    }

    /**
     * Petugas mengambil alih percakapan (Take Over).
     */
    public function takeOver(Conversation $conversation)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $conversation->update([
            'status' => 'handled',
            'assigned_to' => $user->id,
            'last_message_at' => now(),
        ]);

        // Buat sistem message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'officer',
            'sender_user_id' => $user->id,
            'content' => 'Halo, saya ' . $user->name . ' (Petugas BPS Karanganyar). Ada yang bisa saya bantu secara langsung?',
        ]);

        return back()->with('success', 'Percakapan berhasil diambil alih.');
    }

    /**
     * Tutup percakapan.
     */
    public function close(Conversation $conversation)
    {
        $conversation->update(['status' => 'closed']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'content' => 'Sesi percakapan telah ditutup oleh petugas. Terima kasih telah menghubungi Layanan BPS Kabupaten Karanganyar.',
        ]);

        return back()->with('success', 'Percakapan telah ditutup.');
    }
}
