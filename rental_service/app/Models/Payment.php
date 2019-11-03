<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'order_id',
        'type',
        'amount',
        'description',
        'date',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
