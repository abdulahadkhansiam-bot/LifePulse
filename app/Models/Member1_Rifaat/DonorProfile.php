<?php

namespace App\Models\Member1_Rifaat;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorProfile extends Model
{
    use HasFactory;

    protected $table = 'donor_profiles';

    public const STATUS_ELIGIBLE = 'eligible';
    public const STATUS_TEMPORARILY_DEFERRED = 'temporarily_deferred';
    public const STATUS_PERMANENTLY_DEFERRED = 'permanently_deferred';
    public const STATUS_PENDING = 'pending';

    protected $fillable = [
        'user_id',
        'gender',
        'date_of_birth',
        'age',
        'weight_kg',
        'blood_group',
        'last_donation_date',
        'last_donation_type',
        'has_recent_tattoo',
        'has_recent_surgery',
        'has_fever_infection',
        'has_chronic_illness',
        'is_pregnant_or_breastfeeding',
        'hemoglobin_g_dl',
        'is_eligible',
        'eligibility_status',
        'next_eligible_date',
        'ineligibility_reasons',
        'last_evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'last_donation_date' => 'date',
            'next_eligible_date' => 'date',
            'last_evaluated_at' => 'datetime',
            'is_eligible' => 'boolean',
            'has_recent_tattoo' => 'boolean',
            'has_recent_surgery' => 'boolean',
            'has_fever_infection' => 'boolean',
            'has_chronic_illness' => 'boolean',
            'is_pregnant_or_breastfeeding' => 'boolean',
            'ineligibility_reasons' => 'array',
            'weight_kg' => 'float',
            'hemoglobin_g_dl' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate days remaining until next eligible date.
     */
    public function daysRemaining(): int
    {
        if (! $this->next_eligible_date || Carbon::today()->gte($this->next_eligible_date)) {
            return 0;
        }

        return (int) Carbon::today()->diffInDays($this->next_eligible_date);
    }

    public function scopeEligible($query)
    {
        return $query->where('is_eligible', true);
    }
}
