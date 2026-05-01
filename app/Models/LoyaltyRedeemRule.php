<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyRedeemRule extends Model
{
    protected $fillable = [
        'owner_type',
        'merchant_id',
        'points_required',
        'discount_percent',
        'is_active',
    ];

    protected $casts = [
        'points_required' => 'integer',
        'discount_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function scopeGlobal($query)
    {
        return $query->where('owner_type', 'admin')->whereNull('merchant_id');
    }

    public function scopeForMerchant($query, int $merchantId)
    {
        return $query->where('owner_type', 'merchant')->where('merchant_id', $merchantId);
    }
}
