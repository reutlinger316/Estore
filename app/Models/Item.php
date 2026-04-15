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

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    /*Moinul's Review Feature part*/
    public function reviews()
    {
        return $this->hasMany(ItemReview::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}