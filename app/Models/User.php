<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_DONOR = 'donor';
    public const ROLE_REQUESTER = 'requester';
    public const ROLE_HOSPITAL = 'hospital';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_DONOR => 'Voluntary Blood Donor',
        self::ROLE_REQUESTER => 'Emergency Requester (Patient/Relative)',
        self::ROLE_HOSPITAL => 'Hospital / Blood Bank Staff',
        self::ROLE_ADMIN => 'System Administrator',
    ];

    public const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'blood_group',
        'status',
        'city',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isDonor(): bool
    {
        return $this->role === self::ROLE_DONOR;
    }

    public function isRequester(): bool
    {
        return $this->role === self::ROLE_REQUESTER;
    }

    public function isHospital(): bool
    {
        return $this->role === self::ROLE_HOSPITAL;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function donorProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\Member1_Rifaat\DonorProfile::class);
    }
}
