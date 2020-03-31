<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'lesson_id',
        'topic_name',
        'display_order',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
