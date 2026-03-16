<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_no',
        'cvv',
        'exp_date',
        'balance',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'exp_date' => 'date',
            'balance' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
