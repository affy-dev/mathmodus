<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicPreRequisite extends Model
{
    protected $table = 'topic_pre_requisite';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'topic_id',
        'pre_requisite_topic_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
