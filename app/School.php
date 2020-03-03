<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'school_name',
        'school_address',
        'school_pincode',
        'school_phone',
        'created_at',
        'updated_at',
    ];
}
