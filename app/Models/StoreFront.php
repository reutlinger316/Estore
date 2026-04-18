<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreFront extends Model
{
    protected $fillable = [
        'merchant_id',
        'store_account_id',
        'name',
        'branch_name',
        'location',
        'delivery_city',
        'inside_delivery_fee',
        'outside_delivery_fee',
        'allow_combos',
        'balance',
        'status',
        'confirmation_status',
        'confirmed_at',
    ];

    protected $casts = [
        'inside_delivery_fee' => 'decimal:2',
        'outside_delivery_fee' => 'decimal:2',
        'balance' => 'decimal:2',
        'allow_combos' => 'boolean',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function storeAccount()
    {
        return $this->belongsTo(User::class, 'store_account_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function restockRequests()
    {
        return $this->hasMany(RestockRequest::class);
    }

    public function customerCombos()
    {
        return $this->hasMany(CustomerCombo::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}