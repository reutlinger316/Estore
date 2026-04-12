<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'store_front_id',
        'customer_id',
        'rating',
        'title',
        'body',
    ];

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
