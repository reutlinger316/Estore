<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance',
        'phone',
        'default_delivery_address',
        'default_delivery_lat',
        'default_delivery_lng',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }
}
