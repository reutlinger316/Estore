<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    protected $fillable = [
        'marketplace_trade_id',
        'marketplace_product_id',
        'buyer_id',
        'seller_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function trade()
    {
        return $this->belongsTo(MarketplaceTrade::class, 'marketplace_trade_id');
    }

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
}