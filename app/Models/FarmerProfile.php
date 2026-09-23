<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stall_name',
        'bio',
        'contact_person',
        'market_ids',
        'operating_days',
        'pickup_windows',
        'latitude',
        'longitude',
        'stall_image',
        'is_approved',
        'suspended_at',
    ];

    protected function casts(): array
    {
        return [
            'market_ids' => 'array',
            'operating_days' => 'array',
            'pickup_windows' => 'array',
            'is_approved' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markets()
    {
        if (empty($this->market_ids)) {
            return Market::whereIn('id', [])->get();
        }

        return Market::whereIn('id', $this->market_ids)->get();
    }

    public function getAverageRatingAttribute(): float
    {
        return Review::where('farmer_id', $this->user_id)->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute(): int
    {
        return Review::where('farmer_id', $this->user_id)->count();
    }
}
