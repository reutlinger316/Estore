<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReview extends Model
{
    protected $fillable = [
        'item_id',
        'customer_id',
        'rating',
        'title',
        'body',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}