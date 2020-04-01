<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allPrincipals = User::whereHas('roles', function($q){$q->whereIn('title', [env('USER_ROLES_PRINCIPAL', 'Principal')]);})->get();
        dd($allPrincipals);
        return view('home');
    }
}
