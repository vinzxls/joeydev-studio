<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'dikerjakan' => 'Dikerjakan',
        'review_revisi' => 'Review / Revisi',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public const PAYMENT_STATUSES = [
        'belum_bayar' => 'Belum Bayar',
        'dp' => 'DP Dibayar',
        'lunas' => 'Lunas',
    ];

    protected $fillable = [
        'order_code', 'client_name', 'client_wa', 'client_email',
        'subject_id', 'title', 'description', 'deadline',
        'base_price', 'scale_label', 'scale_value',
        'complexity_label', 'complexity_value',
        'urgency_label', 'urgency_value',
        'addons_total', 'final_price',
        'status', 'payment_status', 'payment_proof_path', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(OrderAddon::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function paymentStatusLabel(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? $this->payment_status;
    }

    public static function generateCode(): string
    {
        do {
            $code = sprintf('JD-%s-%s', now()->year, strtoupper(Str::random(8)));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
}
