<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'store_front_id',
        'receipt_number',
        'receipt_generated_at',
        'total_amount',
        'subtotal_before_points',
        'points_redeemed',
        'points_discount_amount',
        'points_discount_percent',
        'points_owner_type',
        'points_merchant_id',
        'global_points_earned',
        'merchant_points_earned',
        'status',
        'paid_at',
        'type',
        'delivery_zone',
        'delivery_fee',
        'delivery_phone',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'receipt_generated_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'subtotal_before_points' => 'decimal:2',
            'points_discount_amount' => 'decimal:2',
            'points_discount_percent' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function loyaltyPointTransactions()
    {
        return $this->hasMany(LoyaltyPointTransaction::class);
    }

    public function pointsMerchant()
    {
        return $this->belongsTo(User::class, 'points_merchant_id');
    }

    public function getItemsSubtotalAttribute(): float
    {
        return (float) $this->orderItems->sum(function ($orderItem) {
            return $orderItem->price * $orderItem->quantity;
        });
    }
}
