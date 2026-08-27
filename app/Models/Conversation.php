<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Conversation extends Model
{
    protected $fillable = [
        'public_id',
        'channel',
        'visitor_session',
        'visitor_name',
        'visitor_contact',
        'status',
        'assigned_to',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'visitor_contact' => 'encrypted',
            'last_message_at' => 'datetime',
        ];
    }

    /**
     * Generate UUID saat membuat percakapan baru.
     */
    protected static function booted(): void
    {
        static::creating(function (Conversation $conversation): void {
            if (empty($conversation->public_id)) {
                $conversation->public_id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Pesan dalam percakapan ini.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Petugas yang menangani percakapan ini.
     */
    public function assignedOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Aduan terkait percakapan ini.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
