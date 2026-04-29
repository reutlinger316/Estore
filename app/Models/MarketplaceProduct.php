<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceProduct extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function trades()
    {
        return $this->hasMany(MarketplaceTrade::class, 'marketplace_product_id');
    }

    public function orders()
    {
        return $this->hasMany(MarketplaceOrder::class, 'marketplace_product_id');
    }

    public function activeTrade()
    {
        return $this->hasOne(MarketplaceTrade::class, 'marketplace_product_id')
            ->whereIn('status', ['pending', 'countered', 'accepted'])
            ->latestOfMany();
    }
}