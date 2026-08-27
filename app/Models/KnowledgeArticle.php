<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeArticle extends Model
{
    protected $fillable = [
        'knowledge_category_id',
        'title',
        'question',
        'answer',
        'keywords',
        'source_title',
        'source_url',
        'published_at',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'published_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Kategori artikel ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(KnowledgeCategory::class, 'knowledge_category_id');
    }

    /**
     * Pembuat artikel ini.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
