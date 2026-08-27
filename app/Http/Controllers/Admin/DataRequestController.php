<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataRequestController extends Controller
{
    /**
     * Tampilkan daftar pengajuan permohonan data mikro & rekomendasi statistik.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = DataRequest::with('assignedOfficer')->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(15);

        $counts = [
            'all' => DataRequest::count(),
            'submitted' => DataRequest::where('status', 'submitted')->count(),
            'reviewing' => DataRequest::where('status', 'reviewing')->count(),
            'ready' => DataRequest::where('status', 'ready')->count(),
            'rejected' => DataRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.data_requests.index', compact('requests', 'status', 'counts'));
    }

    /**
     * Tampilkan detail permohonan data dan berkas proposal.
     */
    public function show(DataRequest $dataRequest)
    {
        return view('admin.data_requests.show', compact('dataRequest'));
    }

    /**
     * Unduh surat pengantar pemohon.
     */
    public function downloadAttachment(DataRequest $dataRequest)
    {
        if (!$dataRequest->attachment_path || !Storage::disk('public')->exists($dataRequest->attachment_path)) {
            return redirect()->back()->with('error', 'Berkas lampiran tidak ditemukan.');
        }

        return Storage::disk('public')->download($dataRequest->attachment_path, $dataRequest->attachment_filename);
    }

    /**
     * Update status telaah data dan unggah berkas dataset hasil olahan BPS.
     */
    public function updateStatus(Request $request, DataRequest $dataRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:submitted,reviewing,approved,ready,rejected',
            'officer_notes' => 'nullable|string|max:1000',
            'result_file' => 'nullable|file|mimes:pdf,xlsx,xls,csv,zip,rar|max:20480',
        ]);

        $data = [
            'status' => $validated['status'],
            'officer_notes' => $validated['officer_notes'],
            'assigned_to' => auth()->id(),
        ];

        if ($request->hasFile('result_file')) {
            $file = $request->file('result_file');
            $data['result_filename'] = $file->getClientOriginalName();
            $data['result_file_path'] = $file->store('results/data_requests', 'public');
            $data['status'] = 'ready'; // Otomatis set siap jika file diunggah
        }

        $dataRequest->update($data);

        return redirect()->back()->with('success', 'Status permohonan data berhasil diperbarui!');
    }
}
