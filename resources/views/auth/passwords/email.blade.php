@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <h1>
                            <div class="login-logo" style="text-align:center">
                                <img alt="Porto" src="{{ asset('frontend/images/new-logo.png') }}">
                            </div>
                        </h1>
                        <p class="text-muted"></p>
                        <div>
                            {{ csrf_field() }}
                            <div class="form-group has-feedback">
                                <input type="email" name="email" class="form-control" required="required"="autofocus" placeholder="{{ trans('global.login_email') }}" style="border: 2px solid #ff9933;border-radius: 4px;">
                                @if($errors->has('email'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </em>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-default btn-block btn-flat">
                                    {{ trans('global.reset_password') }}
                                </button>
                            </div>
                            <div class="col-12 text-right">
                            <a href="{{route('frontend.home')}}">Back to home</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection