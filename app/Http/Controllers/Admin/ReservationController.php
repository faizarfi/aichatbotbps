<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Tampilkan daftar antrean dan reservasi konsultasi PST.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Reservation::with('assignedOfficer')->orderByDesc('reservation_date');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query->paginate(15);

        $counts = [
            'all' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'status', 'counts'));
    }

    /**
     * Tampilkan detail permohonan reservasi.
     */
    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Perbarui status dan catatan tindak lanjut reservasi.
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,completed,cancelled',
            'officer_notes' => 'nullable|string|max:1000',
        ]);

        $reservation->update([
            'status' => $validated['status'],
            'officer_notes' => $validated['officer_notes'],
            'assigned_to' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui menjadi ' . $reservation->status_label . '!');
    }
}
