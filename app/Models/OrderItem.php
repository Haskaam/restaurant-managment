<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'dish_id',
        'dish_name',
        'quantity',

        'unit_net_price',
        'vat_rate',
        'unit_gross_price',

        'total_net',
        'total_vat',
        'total_gross',

        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
