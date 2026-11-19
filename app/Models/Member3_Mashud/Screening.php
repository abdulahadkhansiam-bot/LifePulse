<?php

namespace App\Models\Member3_Mashud;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screening extends Model
{
    use HasFactory;

    public const OUTCOME_ACCEPTED = 'accepted';
    public const OUTCOME_DEFERRED = 'deferred';
    public const OUTCOME_REJECTED = 'rejected';

    protected $fillable = [
        'donor_id', 'screened_by', 'appointment_id',
        'weight_kg', 'systolic', 'diastolic', 'pulse',
        'temperature_c', 'hemoglobin_g_dl',
        'outcome', 'reason', 'deferral_until', 'notes', 'screened_at',
    ];

    protected function casts(): array
    {
        return [
            'screened_at'    => 'datetime',
            'deferral_until' => 'date',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function screenedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'screened_by');
    }
}