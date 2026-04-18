<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCombo extends Model
{
    protected $fillable = [
        'customer_id',
        'store_front_id',
        'name',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function comboItems()
    {
        return $this->hasMany(CustomerComboItem::class);
    }

    public function getCalculatedTotalAttribute(): float
    {
        return (float) $this->comboItems->sum(function ($comboItem) {
            if (!$comboItem->item) {
                return 0;
            }

            $price = (float) $comboItem->item->discounted_price;
            return $price * $comboItem->quantity;
        });
    }
}