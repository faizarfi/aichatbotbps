<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Tampilkan formulir reservasi konsultasi tatap muka PST.
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Simpan pengajuan reservasi dan buat tiket digital.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email|max:255',
            'visitor_phone' => 'required|string|max:25',
            'institution' => 'nullable|string|max:255',
            'topic_category' => 'required|string|max:100',
            'consultation_purpose' => 'required|string|max:1000',
            'reservation_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string|in:08.30 - 10.00 WIB,10.30 - 12.00 WIB,13.00 - 14.30 WIB',
        ]);

        // Generate unique booking code e.g. PST-BKG-202608-001
        $countThisMonth = Reservation::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $bookingCode = 'PST-BKG-' . now()->format('Ym') . '-' . str_pad($countThisMonth + 1, 3, '0', STR_PAD_LEFT);

        $reservation = Reservation::create([
            'booking_code' => $bookingCode,
            'user_id' => auth()->id(),
            'visitor_name' => $validated['visitor_name'],
            'visitor_email' => $validated['visitor_email'],
            'visitor_phone' => $validated['visitor_phone'],
            'institution' => $validated['institution'],
            'topic_category' => $validated['topic_category'],
            'consultation_purpose' => $validated['consultation_purpose'],
            'reservation_date' => $validated['reservation_date'],
            'time_slot' => $validated['time_slot'],
            'status' => 'pending',
        ]);

        return redirect()->route('reservasi.ticket', $reservation->booking_code)
            ->with('success', 'Reservasi berhasil diajukan! Simpan atau cetak tiket digital Anda di bawah ini.');
    }

    /**
     * Tampilkan tiket reservasi digital ber-QR Code.
     */
    public function ticket(string $code)
    {
        $reservation = Reservation::where('booking_code', $code)->firstOrFail();
        return view('reservations.ticket', compact('reservation'));
    }

    /**
     * Halaman cek status reservasi.
     */
    public function track(Request $request)
    {
        $reservation = null;
        if ($code = $request->get('code')) {
            $reservation = Reservation::where('booking_code', trim($code))->first();
        }

        return view('reservations.track', compact('reservation'));
    }
}
