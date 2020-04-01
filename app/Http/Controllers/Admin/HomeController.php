<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\School;
class HomeController
{
    public function index()
    {
        $countPrincipal = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_PRINCIPAL', '2')]);})->count();
        $countStudent = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_STUDENTS', '3')]);})->count();
        $countTeacher = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_TEACHER', '4')]);})->count();
        $schoolCount = School::count();
        $countUser = User::count();
        return view('home', compact('countPrincipal', 'countStudent', 'countTeacher', 'schoolCount', 'countUser'));
    }
}
