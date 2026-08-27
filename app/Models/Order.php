<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'waiter_id',
        'status',

        'subtotal_net',
        'subtotal_vat',
        'subtotal_gross',

        'discount_percent',
        'discount_reason',
        'discount_amount',

        'total_net',
        'total_vat',
        'total_gross',

        'accepted_at',
        'preparation_started_at',
        'ready_at',
        'collected_at',
        'closed_at',
        'cancelled_at',
    ];

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
