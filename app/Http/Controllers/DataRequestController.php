<?php

namespace App\Http\Controllers;

use App\Models\DataRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataRequestController extends Controller
{
    /**
     * Tampilkan formulir pengajuan permintaan data mikro & rekomendasi statistik.
     */
    public function create()
    {
        return view('data_requests.create');
    }

    /**
     * Simpan permohonan data dan upload dokumen legalitas.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:25',
            'applicant_type' => 'required|in:mahasiswa,pemerintah,peneliti,swasta,umum',
            'institution_name' => 'required|string|max:255',
            'research_title' => 'required|string|max:255',
            'data_description' => 'required|string|max:2000',
            'purpose' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Generate Ticket Number e.g. REQ-DATA-202608-001
        $countThisMonth = DataRequest::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $ticketNumber = 'REQ-DATA-' . now()->format('Ym') . '-' . str_pad($countThisMonth + 1, 3, '0', STR_PAD_LEFT);

        $attachmentPath = null;
        $attachmentFilename = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentFilename = $file->getClientOriginalName();
            $attachmentPath = $file->store('attachments/data_requests', 'public');
        }

        $dataRequest = DataRequest::create([
            'ticket_number' => $ticketNumber,
            'user_id' => auth()->id(),
            'applicant_name' => $validated['applicant_name'],
            'applicant_email' => $validated['applicant_email'],
            'applicant_phone' => $validated['applicant_phone'],
            'applicant_type' => $validated['applicant_type'],
            'institution_name' => $validated['institution_name'],
            'research_title' => $validated['research_title'],
            'data_description' => $validated['data_description'],
            'purpose' => $validated['purpose'],
            'attachment_path' => $attachmentPath,
            'attachment_filename' => $attachmentFilename,
            'status' => 'submitted',
        ]);

        return redirect()->route('layanan-data.track', ['ticket' => $dataRequest->ticket_number])
            ->with('success', 'Permohonan data statistik berhasil diajukan dengan nomor registrasi ' . $dataRequest->ticket_number . '. Simpan nomor ini untuk memantau progres data Anda!');
    }

    /**
     * Halaman pelacakan status permohonan data mikro.
     */
    public function track(Request $request)
    {
        $dataRequest = null;
        if ($ticket = $request->get('ticket')) {
            $dataRequest = DataRequest::where('ticket_number', trim($ticket))->first();
        }

        return view('data_requests.track', compact('dataRequest'));
    }

    /**
     * Unduh berkas hasil olahan data yang disediakan oleh BPS.
     */
    public function downloadResult(DataRequest $dataRequest)
    {
        if (!$dataRequest->result_file_path || !Storage::disk('public')->exists($dataRequest->result_file_path)) {
            return redirect()->back()->with('error', 'Berkas hasil data belum tersedia.');
        }

        return Storage::disk('public')->download($dataRequest->result_file_path, $dataRequest->result_filename);
    }
}
