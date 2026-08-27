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

        $counts = [
            'all' => Conversation::count(),
            'waiting' => Conversation::where('status', 'waiting')->count(),
            'handled' => Conversation::where('status', 'handled')->count(),
            'bot' => Conversation::where('status', 'bot')->count(),
            'closed' => Conversation::where('status', 'closed')->count(),
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
        $messages = $conversation->messages()->with('sender')->get()->map(function ($msg) {
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

        $message = $this->chatService->sendOfficerReply(
            $conversation,
            auth()->id(),
            $request->input('content')
        );

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => 'officer',
                'sender_name' => auth()->user()->name,
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
        $conversation->update([
            'status' => 'handled',
            'assigned_to' => auth()->id(),
            'last_message_at' => now(),
        ]);

        // Buat sistem message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'officer',
            'sender_user_id' => auth()->id(),
            'content' => 'Halo, saya ' . auth()->user()->name . ' (Petugas BPS Karanganyar). Ada yang bisa saya bantu secara langsung?',
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
