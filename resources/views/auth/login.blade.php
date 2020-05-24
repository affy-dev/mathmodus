@extends('frontend.layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    @if(\Session::has('message'))
                    <p class="alert alert-info">
                        {{ \Session::get('message') }}
                    </p>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <h1>
                            <div class="login-logo" style="text-align:center">
                                <img alt="Porto" src="{{ asset('frontend/images/new-logo.png') }}">
                            </div>
                        </h1>
                        <div class="col-lg-12 col-md-offset-3">
                            <h2>Login to your <span class="lms_label">MATHMODUS</span> Account...!</h2>
                        </div>

                        <div class="col-lg-12 col-md-12 col-md-offset-3">
                            <div class="lms_login_window lms_login_light">
                                <h3>Sign In</h3>
                                <div class="lms_login_body">
                                    <div class="form-group">
                                        <label for="login_email">Email address or Username</label>
                                        <input name="identity" type="text" class="form-control"
                                            placeholder="{{ trans('global.login_email_username') }}">
                                        @if($errors->has('identity'))
                                        <em class="invalid-feedback" style="color: #fb0000;">
                                            {{ $errors->first('identity') }}
                                        </em>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="login_pass">Password</label>
                                        <input name="password" type="password" class="form-control"
                                            placeholder="{{ trans('global.login_password') }}">
                                        @if($errors->has('password'))
                                        <em class="invalid-feedback" style="color: #fb0000;">
                                            {{ $errors->first('password') }}
                                        </em>
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" />
                                            Remember Me </label>
                                    </div>
                                    <button type="submit" class="btn btn-default">{{ trans('global.login') }}</button>
                                    <a class="btn btn-link px-0" style="color:#D98938"
                                        href="{{ route('password.request') }}">
                                        {{ trans('global.forgot_password') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection