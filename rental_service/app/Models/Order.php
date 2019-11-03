<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'customer_id',
        'status',
        'discount',
        'total',
        'balance',
        'date',
        'return_date',
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
        'total' => 'float',
        'discount' => 'float',
        'balance' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
