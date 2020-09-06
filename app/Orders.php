<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'payer_id',
        'paypal_order_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
