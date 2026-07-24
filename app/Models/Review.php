<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['order_id', 'rating', 'comment', 'is_approved'];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /** Nama klien disamarkan untuk tampilan publik (privasi). */
    public function displayName(): string
    {
        $name = trim($this->order->client_name ?? '');
        if ($name === '') {
            return 'Klien';
        }
        $parts = preg_split('/\s+/', $name);
        $first = array_shift($parts);
        $initials = collect($parts)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)).'.')->implode(' ');

        return trim($first.' '.$initials);
    }
}
