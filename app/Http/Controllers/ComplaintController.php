<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintStatusLog;
use App\Services\TicketNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    /**
     * Form buat aduan.
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Simpan aduan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => ['required', 'string', 'max:255'],
            'reporter_contact' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:pelayanan,data,website,sdm,lainnya'],
            'description' => ['required', 'string', 'max:5000'],
            'consent' => ['required', 'accepted'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [
            'reporter_name.required' => 'Nama lengkap wajib diisi.',
            'reporter_contact.required' => 'Email atau nomor telepon wajib diisi.',
            'category.required' => 'Pilih kategori aduan.',
            'category.in' => 'Kategori tidak valid.',
            'description.required' => 'Uraian aduan wajib diisi.',
            'description.max' => 'Uraian aduan maksimal 5.000 karakter.',
            'consent.accepted' => 'Anda harus menyetujui pemrosesan data.',
            'attachments.*.mimes' => 'Format file harus PDF, JPG, atau PNG.',
            'attachments.*.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        $complaint = DB::transaction(function () use ($validated, $request) {
            // Generate nomor tiket
            $ticketNumber = TicketNumberService::generate();

            // Simpan aduan
            $complaint = Complaint::create([
                'ticket_number' => $ticketNumber,
                'user_id' => auth()->id(),
                'reporter_name' => $validated['reporter_name'],
                'reporter_contact' => $validated['reporter_contact'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'status' => 'new',
                'priority' => 'normal',
            ]);

            // Simpan lampiran
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $storedPath = $file->store('complaints/' . $complaint->id, 'local');

                    ComplaintAttachment::create([
                        'complaint_id' => $complaint->id,
                        'original_name' => $file->getClientOriginalName(),
                        'stored_path' => $storedPath,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            return $complaint;
        });

        return redirect()->route('aduan.create')
            ->with('success', true)
            ->with('ticket_number', $complaint->ticket_number);
    }

    /**
     * Cek status aduan berdasarkan nomor tiket.
     */
    public function status(Request $request)
    {
        $complaint = null;

        if ($request->filled('ticket')) {
            $complaint = Complaint::with('statusLogs')
                ->where('ticket_number', $request->ticket)
                ->first();
        }

        return view('complaints.status', compact('complaint'));
    }
}
