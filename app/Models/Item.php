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
        'low_stock_threshold',
        'discount',
        'is_pre_order',
        'pre_order_available_on',
        'pre_order_note',
        'is_listed',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'is_pre_order' => 'boolean',
        'pre_order_available_on' => 'date',
    ];

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function reviews()
    {
        return $this->hasMany(ItemReview::class);
    }

    public function restockRequests()
    {
        return $this->hasMany(RestockRequest::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function isLowStock(): bool
    {
        return !$this->is_pre_order && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function canBeOrdered(int $quantity = 1): bool
    {
        return $this->is_pre_order || $this->stock_quantity >= $quantity;
    }

    public function getAvailabilityLabelAttribute(): string
    {
        if ($this->is_pre_order) {
            return $this->pre_order_available_on
                ? 'Pre-order · Available ' . $this->pre_order_available_on->format('M d, Y')
                : 'Pre-order';
        }

        return $this->stock_quantity > 0 ? 'In stock' : 'Out of stock';
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
