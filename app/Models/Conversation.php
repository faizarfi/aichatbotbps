<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $public_id
 * @property string $channel
 * @property string|null $visitor_session
 * @property string|null $visitor_name
 * @property string|null $visitor_contact
 * @property string $status
 * @property int|null $assigned_to
 * @property \Illuminate\Support\Carbon|null $last_message_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $assignedOfficer
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Message> $messages
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Complaint> $complaints
 * @property-read int|null $messages_count
 */
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
