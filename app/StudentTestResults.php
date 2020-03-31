<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTestResults extends Model
{
    protected $table = 'student_test_results';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'courseId',
        'correctAnsIds',
        'wrongAnsIds',
        'test_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
