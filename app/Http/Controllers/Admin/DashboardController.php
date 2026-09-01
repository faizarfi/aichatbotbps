<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotFeedback;
use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\KnowledgeArticle;
use App\Models\Message;
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
        $days = collect(range(6, 0))->map(fn($daysAgo) => now()->subDays($daysAgo));
        $chartLabels = $days->map(fn($d) => $d->isoFormat('D MMM'))->toArray();
        $startDate = $days->first()->startOfDay();

        $convCounts = Conversation::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as dt, COUNT(*) as aggregate')
            ->groupBy('dt')
            ->pluck('aggregate', 'dt');

        $compCounts = Complaint::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as dt, COUNT(*) as aggregate')
            ->groupBy('dt')
            ->pluck('aggregate', 'dt');

        $chartConversations = $days->map(fn($d) => $convCounts->get($d->toDateString(), 0))->toArray();
        $chartComplaints = $days->map(fn($d) => $compCounts->get($d->toDateString(), 0))->toArray();

        // Distribusi Status Aduan
        $statusCounts = Complaint::selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $complaintDistribution = [
            'new' => $statusCounts->get('new', 0),
            'processing' => $statusCounts->get('processing', 0),
            'resolved' => $statusCounts->get('resolved', 0),
        ];

        // 6 Percakapan & Aduan terkini
        $recentConversations = Conversation::with(['assignedOfficer', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->limit(6)
            ->get();

        $recentComplaints = Complaint::with('assignedOfficer')->latest()->limit(6)->get();

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
            'total_articles' => KnowledgeArticle::where('is_active', true)->count(),
            'total_messages' => Message::count(),
        ];
    }
}
