<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingMultiplier extends Model
{
    public const TYPES = [
        'scale' => 'Skala Pekerjaan',
        'complexity' => 'Kompleksitas Teknis',
        'urgency' => 'Urgensi Deadline',
    ];

    protected $fillable = ['type', 'label', 'description', 'value', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
