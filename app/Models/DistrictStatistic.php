<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capital_name',
        'area_sqkm',
        'population',
        'density',
        'villages_count',
        'featured_sector',
        'description',
        'color_code',
    ];

    protected function casts(): array
    {
        return [
            'area_sqkm' => 'decimal:2',
            'population' => 'integer',
            'density' => 'integer',
            'villages_count' => 'integer',
        ];
    }
}
