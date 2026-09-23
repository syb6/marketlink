<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'farmer_id',
        'pickup_date',
        'pickup_slot',
        'status',
        'total_amount',
        'notes',
        'cutoff_time',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date',
            'total_amount' => 'decimal:2',
            'cutoff_time' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'placed' => 'warning',
            'accepted' => 'info',
            'declined' => 'danger',
            'ready' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'dark',
            default => 'light',
        };
    }

    public function canBeCancelledByCustomer(): bool
    {
        return in_array($this->status, ['placed', 'accepted'])
            && ($this->cutoff_time === null || now()->lt($this->cutoff_time));
    }
}
