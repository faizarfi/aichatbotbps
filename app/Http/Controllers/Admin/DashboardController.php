<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotFeedback;
use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\DataRequest;
use App\Models\KnowledgeArticle;
use App\Models\Message;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik lengkap, grafik analitik, dan aktivitas terkini.
     */
    public function index()
    {
        $stats = $this->collectStats();

        // 7 Hari Terakhir untuk Grafik Tren
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo);
        });

        $chartLabels = $days->map(fn($d) => $d->isoFormat('D MMM'))->toArray();

        $chartConversations = $days->map(function ($d) {
            return Conversation::whereDate('created_at', $d->toDateString())->count();
        })->toArray();

        $chartComplaints = $days->map(function ($d) {
            return Complaint::whereDate('created_at', $d->toDateString())->count();
        })->toArray();

        // Distribusi Status Aduan
        $complaintDistribution = [
            'new' => Complaint::where('status', 'new')->count(),
            'processing' => Complaint::where('status', 'processing')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];

        // 6 Percakapan terkini
        $recentConversations = Conversation::with(['assignedOfficer', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])
        ->orderByDesc('last_message_at')
        ->limit(6)
        ->get();

        // 6 Aduan terkini
        $recentComplaints = Complaint::with('assignedOfficer')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // Feedback kepuasan
        $feedbackHelpful = BotFeedback::where('rating', 'helpful')->count();
        $feedbackTotal = BotFeedback::count();
        $satisfactionRate = $feedbackTotal > 0 ? round(($feedbackHelpful / $feedbackTotal) * 100) : 100;

        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'chartConversations',
            'chartComplaints',
            'complaintDistribution',
            'recentConversations',
            'recentComplaints',
            'feedbackHelpful',
            'feedbackTotal',
            'satisfactionRate'
        ));
    }

    /**
     * Endpoint JSON untuk real-time polling live counter pada dashboard.
     */
    public function getLiveStats(): JsonResponse
    {
        return response()->json($this->collectStats());
    }

    /**
     * Hitung statistik ringkas.
     */
    private function collectStats(): array
    {
        return [
            'conversations_today' => Conversation::whereDate('created_at', today())->count(),
            'conversations_waiting' => Conversation::where('status', 'waiting')->count(),
            'conversations_handled' => Conversation::where('status', 'handled')->count(),
            'complaints_new' => Complaint::where('status', 'new')->count(),
            'complaints_processing' => Complaint::where('status', 'processing')->count(),
            'complaints_resolved' => Complaint::where('status', 'resolved')->count(),
            'reservations_pending' => Reservation::where('status', 'pending')->count(),
            'data_requests_new' => DataRequest::where('status', 'submitted')->count(),
            'total_articles' => KnowledgeArticle::where('is_active', true)->count(),
            'total_messages' => Message::count(),
        ];
    }
}
