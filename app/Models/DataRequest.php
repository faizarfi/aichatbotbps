<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'applicant_type',
        'institution_name',
        'research_title',
        'data_description',
        'purpose',
        'attachment_path',
        'attachment_filename',
        'result_file_path',
        'result_filename',
        'status',
        'officer_notes',
        'assigned_to',
    ];

    public function assignedOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'reviewing' => 'Sedang Ditelaah',
            'approved' => 'Disetujui',
            'ready' => 'Data Siap Diunduh',
            'rejected' => 'Permohonan Ditolak',
            default => 'Pengajuan Diterima',
        };
    }
}
