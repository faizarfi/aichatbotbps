<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotFeedback extends Model
{
    protected $table = 'bot_feedback';

    protected $fillable = [
        'message_id',
        'rating',
        'comment',
    ];

    /**
     * Pesan yang diberi feedback.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
