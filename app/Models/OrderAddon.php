<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddon extends Model
{
    protected $fillable = ['order_id', 'addon_id', 'name', 'price'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
