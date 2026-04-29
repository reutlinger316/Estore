<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceTrade extends Model
{
    protected $fillable = [
        'marketplace_product_id',
        'buyer_id',
        'seller_id',
        'quantity',
        'original_price',
        'buyer_offer_price',
        'seller_counter_price',
        'final_price',
        'status',
        'buyer_message',
        'seller_message',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'buyer_offer_price' => 'decimal:2',
        'seller_counter_price' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(MarketplaceProduct::class, 'marketplace_product_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function order()
    {
        return $this->hasOne(MarketplaceOrder::class, 'marketplace_trade_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'countered', 'accepted']);
    }
}