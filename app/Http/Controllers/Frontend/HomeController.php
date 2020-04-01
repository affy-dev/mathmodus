<?php

namespace App\Http\Controllers\Frontend;
use App\User;
use App\School;
class HomeController
{
    public function index()
    {
        return view('frontend.cms.home');
    }

    public function howItWorks() {
        return view('frontend.cms.howitworks');
    }

    public function contactUs() {
        return view('frontend.cms.contact-us');
    }
    
}