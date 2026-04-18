<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceSetting extends Model
{
    protected $fillable = [
        'registration_fee',
    ];

    protected $casts = [
        'registration_fee' => 'decimal:2',
    ];
}