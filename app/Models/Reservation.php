<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'institution',
        'topic_category',
        'consultation_purpose',
        'reservation_date',
        'time_slot',
        'status',
        'officer_notes',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
        ];
    }

    /**
     * Pengguna (masyarakat) yang mengajukan reservasi ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'Disetujui',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu Konfirmasi',
        };
    }
}
