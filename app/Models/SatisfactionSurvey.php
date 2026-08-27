<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatisfactionSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'respondent_name',
        'respondent_email',
        'respondent_phone',
        'respondent_type',
        'service_used',
        'quality_score',
        'speed_score',
        'friendliness_score',
        'facility_score',
        'overall_score',
        'feedback_text',
    ];

    protected function casts(): array
    {
        return [
            'quality_score' => 'integer',
            'speed_score' => 'integer',
            'friendliness_score' => 'integer',
            'facility_score' => 'integer',
            'overall_score' => 'decimal:2',
        ];
    }

    public function getIkmScoreAttribute(): float
    {
        // Skala 25 - 100 standar KemenPAN-RB
        return round(($this->overall_score / 5) * 100, 2);
    }

    public function getServiceGradeAttribute(): string
    {
        $ikm = $this->ikm_score;
        if ($ikm >= 88.31) return 'A (Sangat Baik)';
        if ($ikm >= 76.61) return 'B (Baik)';
        if ($ikm >= 65.00) return 'C (Kurang Baik)';
        return 'D (Tidak Baik)';
    }
}
