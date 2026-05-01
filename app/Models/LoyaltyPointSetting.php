<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPointSetting extends Model
{
    protected $fillable = [
        'owner_type',
        'merchant_id',
        'amount_per_point',
        'is_active',
    ];

    protected $casts = [
        'amount_per_point' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public static function globalSetting(): ?self
    {
        return self::where('owner_type', 'admin')
            ->whereNull('merchant_id')
            ->where('is_active', true)
            ->first();
    }

    public static function merchantSetting(int $merchantId): ?self
    {
        return self::where('owner_type', 'merchant')
            ->where('merchant_id', $merchantId)
            ->where('is_active', true)
            ->first();
    }

    public function calculatePoints(float $amount): int
    {
        if (!$this->is_active || (float) $this->amount_per_point <= 0) {
            return 0;
        }

        return (int) floor($amount / (float) $this->amount_per_point);
    }
}
