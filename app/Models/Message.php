<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $conversation_id
 * @property string $sender_type
 * @property int|null $sender_user_id
 * @property string $content
 * @property array|null $knowledge_sources
 * @property string|null $ai_model
 * @property float|null $confidence
 * @property bool $is_fallback
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Conversation $conversation
 * @property-read \App\Models\User|null $sender
 * @property-read \App\Models\BotFeedback|null $feedback
 */
class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_user_id',
        'content',
        'knowledge_sources',
        'ai_model',
        'confidence',
        'is_fallback',
    ];

    protected function casts(): array
    {
        return [
            'knowledge_sources' => 'array',
            'confidence' => 'decimal:4',
            'is_fallback' => 'boolean',
        ];
    }

    /**
     * Percakapan pesan ini.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Pengirim pesan (jika petugas).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * Feedback untuk pesan ini.
     */
    public function feedback(): HasOne
    {
        return $this->hasOne(BotFeedback::class);
    }
}
