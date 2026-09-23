<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'operating_days',
        'opening_time',
        'closing_time',
        'description',
        'image',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'operating_days' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getOperatingDaysTextAttribute(): string
    {
        return is_array($this->operating_days) ? implode(', ', $this->operating_days) : '';
    }
}
