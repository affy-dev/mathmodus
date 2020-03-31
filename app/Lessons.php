<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'course_id',
        'lesson_name',
        'display_order',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
