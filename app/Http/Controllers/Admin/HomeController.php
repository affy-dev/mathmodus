<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\School;
use App\StudentTestResults;
class HomeController
{
    const TEST_STATUS = [
        'PENDING' => 'pending',
        'COMPLETED' => 'completed'
    ];

    public function index()
    {
        $countPrincipal = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_PRINCIPAL', '2')]);})->count();
        $countStudent = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_STUDENTS', '3')]);})->count();
        $countTeacher = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_TEACHER', '4')]);})->count();
        $schoolCount = School::count();
        $countUser = User::count();
        $examsTakes = StudentTestResults::where('user_id', 17)->where('test_status', self::TEST_STATUS['COMPLETED'])->count();
        return view('home', compact('countPrincipal', 'countStudent', 'countTeacher', 'schoolCount', 'countUser', 'examsTakes'));
    }
}
