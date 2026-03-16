<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreFront extends Model
{
    protected $fillable = [
        'merchant_id',
        'store_account_id',
        'name',
        'branch_name',
        'location',
        'balance',
        'status',
        'confirmation_status',
        'confirmed_at',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function storeAccount()
    {
        return $this->belongsTo(User::class, 'store_account_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
