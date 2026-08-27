<?php

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

class TicketNumberService
{
    /**
     * Generate nomor tiket unik dalam transaksi database.
     * Format: ADU-YYYY-NNNNNN
     */
    public static function generate(): string
    {
        $year = now()->year;
        $prefix = "ADU-{$year}-";

        // Ambil nomor terakhir tahun ini di dalam lock
        $lastTicket = Complaint::where('ticket_number', 'like', $prefix . '%')
            ->orderByDesc('ticket_number')
            ->lockForUpdate()
            ->value('ticket_number');

        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
