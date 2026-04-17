<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    protected $fillable = [
        'store_front_id',
        'item_id',
        'requested_by',
        'requested_quantity',
        'note',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function storeFront()
    {
        return $this->belongsTo(StoreFront::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}