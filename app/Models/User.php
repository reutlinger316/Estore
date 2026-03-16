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
        'status',
        'balance',
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
