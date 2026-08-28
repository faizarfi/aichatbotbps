<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'conversation_id',
        'category',
        'reporter_name',
        'reporter_contact',
        'description',
        'status',
        'priority',
        'assigned_to',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'reporter_contact' => 'encrypted',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Pengguna (masyarakat) yang menyampaikan aduan ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Percakapan terkait aduan ini.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Petugas yang menangani aduan ini.
     */
    public function assignedOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Lampiran aduan.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    /**
     * Log perubahan status aduan.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(ComplaintStatusLog::class);
    }
}
