<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditcards extends Model
{
    use HasFactory;

    protected $fillable = [
        'cardNo',
        'cvv',
        'expDate',
        'balance',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
