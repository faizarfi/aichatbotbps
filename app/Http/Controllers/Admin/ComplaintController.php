<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintStatusLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Daftar seluruh aduan masuk dengan filter.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['assignedOfficer', 'attachments']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('reporter_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $complaints = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $statusCounts = Complaint::selectRaw('status, COUNT(*) as aggregate')->groupBy('status')->pluck('aggregate', 'status');
        $counts = [
            'all' => $statusCounts->sum(),
            'new' => $statusCounts->get('new', 0),
            'verified' => $statusCounts->get('verified', 0),
            'processing' => $statusCounts->get('processing', 0),
            'resolved' => $statusCounts->get('resolved', 0),
            'rejected' => $statusCounts->get('rejected', 0),
        ];

        return view('admin.complaints.index', compact('complaints', 'counts'));
    }

    /**
     * Detail aduan dan audit trail status log.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['attachments', 'statusLogs.changedByUser', 'assignedOfficer']);
        $officers = User::where('is_active', true)->orderBy('name')->get();

        return view('admin.complaints.show', compact('complaint', 'officers'));
    }

    /**
     * Perbarui status aduan dan catat audit log.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,verified,processing,resolved,rejected'],
            'priority' => ['nullable', 'in:low,normal,high'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $complaint->status;
        $newStatus = $validated['status'];

        $updateData = [
            'status' => $newStatus,
        ];

        if (isset($validated['priority'])) {
            $updateData['priority'] = $validated['priority'];
        }

        if (array_key_exists('assigned_to', $validated)) {
            $updateData['assigned_to'] = $validated['assigned_to'] ?: null;
        }

        if ($newStatus === 'resolved' && !$complaint->resolved_at) {
            $updateData['resolved_at'] = now();
        }

        $complaint->update($updateData);

        // Catat di status logs
        ComplaintStatusLog::create([
            'complaint_id' => $complaint->id,
            'status' => $newStatus,
            'note' => $validated['note'] ?: ($oldStatus !== $newStatus ? "Status diubah dari {$oldStatus} ke {$newStatus}" : "Pembaruan data aduan"),
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Status aduan berhasil diperbarui.');
    }

    /**
     * Unduh lampiran secara aman.
     */
    public function downloadAttachment(ComplaintAttachment $attachment)
    {
        if (!Storage::disk('local')->exists($attachment->stored_path)) {
            return back()->with('error', 'File lampiran tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('local')->download($attachment->stored_path, $attachment->original_name);
    }
}
