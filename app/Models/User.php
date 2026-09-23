<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── Role helpers ─────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function getDashboardRouteAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'farmer' => 'farmer.dashboard',
            default => 'customer.dashboard',
        };
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function farmerProfile(): HasOne
    {
        return $this->hasOne(FarmerProfile::class);
    }

    /** Products listed by this farmer */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    /** Orders placed by this customer */
    public function ordersAsCustomer(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /** Orders received by this farmer */
    public function ordersAsFarmer(): HasMany
    {
        return $this->hasMany(Order::class, 'farmer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo
            ? asset('storage/'.$this->profile_photo)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=2d6a4f&color=fff&size=128';
    }
}
