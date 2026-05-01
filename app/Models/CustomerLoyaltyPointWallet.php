<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoyaltyPointWallet extends Model
{
    protected $fillable = [
        'customer_id',
        'owner_type',
        'merchant_id',
        'points',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public static function walletFor(int $customerId, string $ownerType, ?int $merchantId = null): self
    {
        return self::firstOrCreate([
            'customer_id' => $customerId,
            'owner_type' => $ownerType,
            'merchant_id' => $merchantId,
        ], [
            'points' => 0,
        ]);
    }
}
