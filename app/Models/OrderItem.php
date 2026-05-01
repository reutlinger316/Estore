<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'price',
        'is_pre_order',
        'pre_order_available_on',
        'pre_order_note',
        'pre_order_status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_pre_order' => 'boolean',
        'pre_order_available_on' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
