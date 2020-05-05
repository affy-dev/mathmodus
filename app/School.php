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
        'principal_id',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
