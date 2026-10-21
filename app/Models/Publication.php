<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'pub_id',
        'title',
        'slug',
        'issn',
        'kat_no',
        'pub_no',
        'id_subject_csa',
        'subject_csa',
        'sch_date',
        'rl_date',
        'updt_date',
        'cover_url',
        'pdf_url',
        'size',
        'abstract',
        'is_featured',
    ];

    protected $casts = [
        'sch_date' => 'date',
        'rl_date' => 'date',
        'updt_date' => 'date',
        'is_featured' => 'boolean',
    ];
}
