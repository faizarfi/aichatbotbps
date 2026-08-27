<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotFeedback;
use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\KnowledgeArticle;
use App\Models\Message;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman rekap dan filter laporan eksekutif.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $type = $request->get('type', 'all');

        $data = $this->prepareReportData($startDate, $endDate, $type);

        return view('admin.reports.index', array_merge($data, [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
        ]));
    }

    /**
     * Unduh laporan langsung dalam format file PDF.
     */
    public function downloadPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $type = $request->get('type', 'all');

        $data = $this->prepareReportData($startDate, $endDate, $type);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $filename = 'Laporan-Layanan-PST-BPS-Karanganyar-' . Carbon::parse($startDate)->format('dMY') . '-sd-' . Carbon::parse($endDate)->format('dMY') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Buka pratinjau dokumen PDF langsung di tab browser.
     */
    public function previewPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $type = $request->get('type', 'all');

        $data = $this->prepareReportData($startDate, $endDate, $type);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        return $pdf->stream('Pratinjau-Laporan-BPS-Karanganyar.pdf');
    }

    /**
     * Kumpulkan dan olah data laporan berdasarkan filter periode dan jenis.
     */
    private function prepareReportData(string $startDate, string $endDate, string $type): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // 1. Data Pengaduan
        $complaintsQuery = Complaint::with('assignedOfficer')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'asc');

        $complaints = $complaintsQuery->get();

        $complaintStats = [
            'total' => $complaints->count(),
            'new' => $complaints->where('status', 'new')->count(),
            'processing' => $complaints->where('status', 'processing')->count(),
            'resolved' => $complaints->where('status', 'resolved')->count(),
            'rejected' => $complaints->where('status', 'rejected')->count(),
        ];

        // 2. Data Percakapan Konsultasi Chatbot
        $conversationsQuery = Conversation::with(['assignedOfficer', 'messages'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'asc');

        $conversations = $conversationsQuery->get();

        $conversationStats = [
            'total' => $conversations->count(),
            'bot_only' => $conversations->where('status', 'bot')->count(),
            'waiting' => $conversations->where('status', 'waiting')->count(),
            'handled' => $conversations->where('status', 'handled')->count(),
            'closed' => $conversations->where('status', 'closed')->count(),
        ];

        // 3. Tingkat Kepuasan Chatbot
        $feedbackHelpful = BotFeedback::whereBetween('created_at', [$start, $end])
            ->where('rating', 'helpful')
            ->count();
        $feedbackTotal = BotFeedback::whereBetween('created_at', [$start, $end])->count();
        $satisfactionRate = $feedbackTotal > 0 ? round(($feedbackHelpful / $feedbackTotal) * 100) : 100;

        // 4. Informasi Umum
        $totalArticles = KnowledgeArticle::where('is_active', true)->count();
        $totalMessages = Message::whereBetween('created_at', [$start, $end])->count();

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'complaints' => $complaints,
            'complaintStats' => $complaintStats,
            'conversations' => $conversations,
            'conversationStats' => $conversationStats,
            'satisfactionRate' => $satisfactionRate,
            'feedbackHelpful' => $feedbackHelpful,
            'feedbackTotal' => $feedbackTotal,
            'totalArticles' => $totalArticles,
            'totalMessages' => $totalMessages,
            'generatedAt' => now()->translatedFormat('d F Y, H:i') . ' WIB',
            'generatedBy' => auth()->user()->name ?? 'Administrator BPS',
        ];
    }
}
