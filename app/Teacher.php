<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'role_id',
        'school_id',
        'name',
        'designation',
        'qualification',
        'dob',
        'gender',
        'email',
        'phone_no',
        'address',
        'joining_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by'
    ];
}
