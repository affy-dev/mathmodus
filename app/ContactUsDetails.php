<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUsDetails extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'full_name',
        'email',
        'messages',
        'created_at',
        'updated_at',
    ];
}
