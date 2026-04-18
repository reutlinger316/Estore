<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerComboItem extends Model
{
    protected $fillable = [
        'customer_combo_id',
        'item_id',
        'quantity',
    ];

    public function combo()
    {
        return $this->belongsTo(CustomerCombo::class, 'customer_combo_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}