<?php

namespace App\Models\Member4_Reehay;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequest extends Model
{
    use HasFactory;

    protected $table = 'blood_requests';

    // Status constants (Feature R4: Request Tracking)
    public const STATUS_OPEN = 'open';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_FULFILLED = 'fulfilled';
    public const STATUS_EXPIRED = 'expired';

    // Urgency constants
    public const URGENCY_NORMAL = 'normal';
    public const URGENCY_URGENT = 'urgent';
    public const URGENCY_CRITICAL = 'critical';

    // Emergency level constants (Feature R5: Emergency Escalation)
    public const EMERGENCY_SOS_CRITICAL = 'sos_critical';
    public const EMERGENCY_URGENT_ESCALATED = 'urgent_escalated';

    // Radial sweep tiers (km) — escalated every 10 minutes
    public const RADIUS_TIER_1 = 10;
    public const RADIUS_TIER_2 = 25;
    public const RADIUS_TIER_3 = 50;

    // Escalation interval in minutes
    public const ESCALATION_INTERVAL_MINUTES = 10;

    protected $fillable = [
        'user_id',
        'patient_name',
        'blood_group',
        'component_type',
        'units_needed',
        'units_fulfilled',
        'hospital_name',
        'city',
        'latitude',
        'longitude',
        'status',
        'urgency',
        'deadline_at',
        'notes',
        'is_emergency',
        'emergency_level',
        'current_radius_km',
        'escalation_count',
        'last_escalated_at',
        'is_broadcast_pinned',
        'contact_phone',
    ];

    protected function casts(): array
    {
        return [
            'deadline_at' => 'datetime',
            'last_escalated_at' => 'datetime',
            'is_emergency' => 'boolean',
            'is_broadcast_pinned' => 'boolean',
            'units_needed' => 'integer',
            'units_fulfilled' => 'integer',
            'current_radius_km' => 'integer',
            'escalation_count' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Status Progression Logic (Feature R4) ────────────────

    /**
     * Recalculate and persist the correct status based on
     * units_fulfilled vs units_needed and deadline.
     */
    public function recalculateStatus(): void
    {
        if ($this->isExpired()) {
            $this->status = self::STATUS_EXPIRED;
        } elseif ($this->units_fulfilled >= $this->units_needed) {
            $this->status = self::STATUS_FULFILLED;
        } elseif ($this->units_fulfilled > 0) {
            $this->status = self::STATUS_PARTIAL;
        } else {
            $this->status = self::STATUS_OPEN;
        }

        $this->save();
    }

    /**
     * Record a pledge / fulfilled unit and update the status.
     */
    public function fulfill(int $units = 1): void
    {
        $this->units_fulfilled = min(
            $this->units_fulfilled + $units,
            $this->units_needed
        );

        $this->recalculateStatus();
    }

    /**
     * Check if this request has passed its deadline.
     */
    public function isExpired(): bool
    {
        return $this->status !== self::STATUS_FULFILLED
            && Carbon::now()->gte($this->deadline_at);
    }

    /**
     * Hours remaining until deadline.
     */
    public function hoursRemaining(): float
    {
        return max(0, Carbon::now()->diffInHours($this->deadline_at, false));
    }

    /**
     * Percentage of fulfilment (0-100).
     */
    public function fulfilmentPercent(): int
    {
        if ($this->units_needed === 0) {
            return 100;
        }

        return (int) min(100, round(($this->units_fulfilled / $this->units_needed) * 100));
    }

    // ─── Emergency Escalation Logic (Feature R5) ──────────────

    /**
     * Activate emergency mode: pin the broadcast, override throttles,
     * and start radial sweep at tier-1 (10 km).
     */
    public function activateEmergency(string $level = self::EMERGENCY_SOS_CRITICAL): void
    {
        $this->is_emergency = true;
        $this->emergency_level = $level;
        $this->urgency = self::URGENCY_CRITICAL;
        $this->is_broadcast_pinned = true;
        $this->current_radius_km = self::RADIUS_TIER_1;
        $this->escalation_count = 0;
        $this->last_escalated_at = Carbon::now();

        $this->save();
    }

    /**
     * Expand the radial sweep to the next tier.
     * 10 km → 25 km → 50 km (capped).
     */
    public function escalateRadius(): void
    {
        if ($this->current_radius_km < self::RADIUS_TIER_2) {
            $this->current_radius_km = self::RADIUS_TIER_2;
        } elseif ($this->current_radius_km < self::RADIUS_TIER_3) {
            $this->current_radius_km = self::RADIUS_TIER_3;
        }
        // Already at max — stay at 50 km

        $this->escalation_count++;
        $this->last_escalated_at = Carbon::now();
        $this->save();
    }

    /**
     * Whether this request is due for the next radial escalation
     * (every 10 minutes while still open/partial and not at max radius).
     */
    public function needsRadialEscalation(): bool
    {
        if (! $this->is_emergency) {
            return false;
        }

        if (in_array($this->status, [self::STATUS_FULFILLED, self::STATUS_EXPIRED])) {
            return false;
        }

        if ($this->current_radius_km >= self::RADIUS_TIER_3) {
            return false;
        }

        if (! $this->last_escalated_at) {
            return true;
        }

        $minutes = abs(Carbon::now()->diffInMinutes($this->last_escalated_at));

        return $minutes >= self::ESCALATION_INTERVAL_MINUTES;
    }

    /**
     * Deactivate emergency mode (request fulfilled or expired).
     */
    public function deactivateEmergency(): void
    {
        $this->is_emergency = false;
        $this->is_broadcast_pinned = false;
        $this->save();
    }

    // ─── Query Scopes ─────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_PARTIAL]);
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    public function scopePinnedBroadcasts($query)
    {
        return $query->where('is_broadcast_pinned', true)
            ->whereIn('status', [self::STATUS_OPEN, self::STATUS_PARTIAL]);
    }

    public function scopeForBloodGroup($query, string $bloodGroup)
    {
        return $query->where('blood_group', $bloodGroup);
    }

    // ─── Presentation Helpers ─────────────────────────────────

    public function statusBadgeColor(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'red',
            self::STATUS_PARTIAL => 'amber',
            self::STATUS_FULFILLED => 'emerald',
            self::STATUS_EXPIRED => 'slate',
            default => 'slate',
        };
    }

    public function urgencyLabel(): string
    {
        return match ($this->urgency) {
            self::URGENCY_CRITICAL => 'SOS Critical',
            self::URGENCY_URGENT => 'Urgent',
            default => 'Normal',
        };
    }
}
