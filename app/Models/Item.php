<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'store_front_id',
        'item_name',
        'description',
        'image',
        'price',
        'stock_quantity',
        'discount',
        'is_pre_order',
        'is_listed',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function reviews()
    {
        return $this->hasMany(ItemReview::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function getDiscountAmountAttribute(): float
    {
        return round(($this->price * $this->discount) / 100, 2);
    }

    public function getDiscountedPriceAttribute(): float
    {
        return round($this->price - $this->discount_amount, 2);
    }
}