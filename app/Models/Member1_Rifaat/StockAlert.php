<?php

namespace App\Models\Member1_Rifaat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $table = 'stock_alerts';

    public const TYPE_NEAR_EXPIRY = 'near_expiry';
    public const TYPE_CRITICAL_EXPIRY = 'critical_expiry';
    public const TYPE_LOW_STOCK = 'low_stock';
    public const TYPE_STOCKOUT = 'stockout';
    public const TYPE_EXPIRED = 'expired';

    public const SEVERITY_CRITICAL = 'critical';
    public const SEVERITY_WARNING = 'warning';
    public const SEVERITY_INFO = 'info';

    protected $fillable = [
        'alert_type',
        'severity',
        'blood_group',
        'component_type',
        'message',
        'details',
        'is_resolved',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_resolved', false);
    }
}
