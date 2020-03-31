<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'lesson_id',
        'question_text',
        'display_order',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
