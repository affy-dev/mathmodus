<?php

namespace App\Http\Controllers\Frontend;
use App\ContactUsDetails;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

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

    public function postMessage(Request $request) {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'messages' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('contact-us')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'messages' => $request->input('messages'),
        ];
        ContactUsDetails::create($data);
        return redirect()->route('frontend.contactus')->with('message', 'Message sent successfully. We will revert you shortly!');
    }
    
}