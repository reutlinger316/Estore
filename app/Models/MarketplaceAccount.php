<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceAccount extends Model
{
    protected $fillable = [
        'user_id',
        'paid_fee',
        'is_eligible',
        'paid_at',
    ];

    protected $casts = [
        'paid_fee' => 'decimal:2',
        'is_eligible' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}