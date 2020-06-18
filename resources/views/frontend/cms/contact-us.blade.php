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

<form action="{{ route("contact-us") }}" method="POST" enctype="multipart/form-data">
@csrf
    <div class="lms_contact_form">
        @if(count($errors))
            <div class="form-group">
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Your Full Name" value="{{ old('full_name', '') }}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Your Email" value="{{ old('email', '') }}">
                </div>
                <!-- <div class="form-group">
                    <input type="url" class="form-control" id="web_site" placeholder="Your Website">
                </div> -->
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <textarea rows="10" class="form-control" id="messages" name="messages" placeholder="Your Message...">{{ old('messages') }}</textarea>
                </div>
            </div>
            <div class="col-lg-12">
            <input class="btn btn-default" type="submit" value="Send">
                <p id="err"></p>
            </div>
        </div>
    </div>
</form>
@endsection