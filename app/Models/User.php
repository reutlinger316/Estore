<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'balance',
        'phone',
        'default_delivery_address',
        'default_delivery_lat',
        'default_delivery_lng',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'balance' => 'decimal:2',
        'default_delivery_lat' => 'decimal:7',
        'default_delivery_lng' => 'decimal:7',
    ];

    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }

    public function marketplaceAccount()
    {
        return $this->hasOne(\App\Models\MarketplaceAccount::class);
    }

    public function marketplaceProducts()
    {
        return $this->hasMany(\App\Models\MarketplaceProduct::class, 'seller_id');
    }

    public function hasMarketplaceEligibility(): bool
    {
        return (bool) optional($this->marketplaceAccount)->is_eligible;
    }

    public function reportsMade()
    {
        return $this->hasMany(\App\Models\UserReport::class, 'reporter_id');
    }

    public function reportsReceived()
    {
        return $this->hasMany(\App\Models\UserReport::class, 'reported_user_id');
    }

    public function marketplaceTradesAsBuyer()
    {
        return $this->hasMany(\App\Models\MarketplaceTrade::class, 'buyer_id');
    }

    public function marketplaceTradesAsSeller()
    {
        return $this->hasMany(\App\Models\MarketplaceTrade::class, 'seller_id');
    }

    public function marketplacePurchases()
    {
        return $this->hasMany(\App\Models\MarketplaceOrder::class, 'buyer_id');
    }

    public function marketplaceSales()
    {
        return $this->hasMany(\App\Models\MarketplaceOrder::class, 'seller_id');
    }
}