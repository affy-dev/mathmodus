@extends('frontend.layouts.app')
@section('content')
<div class="row" style="margin-top:5%">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="lms_title_center">
            <div class="lms_heading_1">
                <h2 class="lms_heading_title">LMS - Get In Touch with Us !</h2>
            </div>
            <!-- <p>Duis tortor lacus, dictum nec augue a, euismod sagittis eros. Aliquam id ligula </p> -->
        </div>
    </div>
</div>
<div class="lms_contact_form">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control" id="uname" placeholder="Your Full Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="uemail" placeholder="Your Email">
            </div>
            <div class="form-group">
                <input type="url" class="form-control" id="web_site" placeholder="Your Website">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <textarea rows="10" class="form-control" id="message" placeholder="Your Message..."></textarea>
            </div>
        </div>
        <div class="col-lg-12">
            <a id="em_sub" class="btn btn-default pull-right">Send</a>
            <p id="err"></p>
        </div>
    </div>
</div>
@endsection