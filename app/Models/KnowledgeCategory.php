<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KnowledgeCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Artikel dalam kategori ini.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(KnowledgeArticle::class);
    }

    /**
     * Artikel aktif dalam kategori ini.
     */
    public function activeArticles(): HasMany
    {
        return $this->hasMany(KnowledgeArticle::class)->where('is_active', true);
    }
}
