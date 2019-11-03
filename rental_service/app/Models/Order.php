<?php

namespace App\Models;

use App\Models\Traits\Uuid;
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

    public function getOrderTotal()
    {
        return $this->getItemsTotal() - $this->discount;
    }

    public function getItemsTotal()
    {
        $itemsTotal = 0;
        foreach ($this->items as $item) {
            $itemsTotal += $item->product->price * $item->qty;
        }
        return $itemsTotal;
    }

    public function getPaymentsTotal()
    {
        $paymentsTotal = 0;
        foreach ($this->payments as $payment) {
            $paymentsTotal += $payment->amount;
        }
        return $paymentsTotal;
    }

    public function adjustTotal()
    {
        $this->total = $this->getTotal();
        $this->save();
    }

    public function adjustBalance()
    {
        $this->balance = $this->getOrderTotal() - $this->getPaymentsTotal();
        $this->save();
    }

}
