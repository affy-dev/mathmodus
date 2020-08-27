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
        'lessonId',
        'mcqs',
        'correctAnsIds',
        'wrongAnsIds',
        'test_status',
        'wrong_lesson_ids',
        'testFromLessonsTab',
        'wrong_lesson_ids',
        'correct_lesson_ids',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
