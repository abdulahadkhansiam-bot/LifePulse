<?php

namespace App\Models\Member1_Rifaat;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    use HasFactory;

    protected $table = 'blood_inventory';

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_TRANSFUSED = 'transfused';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_DISCARDED = 'discarded';

    public const COMPONENT_WHOLE_BLOOD = 'whole_blood';
    public const COMPONENT_RED_CELLS = 'red_cells';
    public const COMPONENT_PLATELETS = 'platelets';
    public const COMPONENT_PLASMA = 'plasma';

    protected $fillable = [
        'bag_serial_number',
        'blood_group',
        'component_type',
        'volume_ml',
        'collection_date',
        'expiry_date',
        'storage_location',
        'status',
        'hospital_name',
    ];

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'expiry_date' => 'datetime',
            'volume_ml' => 'integer',
        ];
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->gte($this->expiry_date);
    }

    public function hoursUntilExpiry(): float
    {
        return Carbon::now()->diffInHours($this->expiry_date, false);
    }
}
