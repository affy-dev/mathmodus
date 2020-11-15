<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonPreRequisite extends Model
{
    protected $table = 'lessons_pre_requisite';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'lession_id',
        'lessons_pre_requisite_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
