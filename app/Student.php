<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'school_id',
        'teacher_id',
        'name',
        'dob',
        'gender',
        'blood_group',
        'nationality',
        'photo',
        'email',
        'phone_no',
        'father_name',
        'father_phone_no',
        'mother_name',
        'mother_phone_no',
        'present_address',
        'permanent_address',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
