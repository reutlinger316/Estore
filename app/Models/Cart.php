<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'store_front_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
