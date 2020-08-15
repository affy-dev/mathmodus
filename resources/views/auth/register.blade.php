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
                    <form method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <h1>
                            <div class="login-logo" style="text-align:center">
                                <img alt="Porto" src="{{ asset('frontend/images/new-logo.png') }}">
                            </div>
                        </h1>
                        <!-- <div class="col-lg-12 col-md-offset-3">
                            <h2>Register</h2>
                        </div> -->

                        <div class="col-lg-12 col-md-12 col-md-offset-3">
                            <div class="lms_login_window lms_login_light">
                                <h3>Register</h3>
                                <div class="lms_login_body">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label for="name">{{ trans('global.user.fields.name') }}*</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ old('name', isset($user) ? $user->name : '') }}">
                                        @if($errors->has('name'))
                                        <em class="invalid-feedback" style="color:#e40505">
                                            {{ $errors->first('name') }}
                                        </em>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                        <label for="username">{{ trans('global.user.fields.username') }}*</label>
                                        <input type="text" id="username" name="username" class="form-control"
                                            value="{{ old('username', isset($user) ? $user->username : '') }}">
                                        @if($errors->has('username'))
                                        <em class="invalid-feedback" style="color:#e40505">
                                            {{ $errors->first('username') }}
                                        </em>
                                        @endif
                                        <p class="helper-block">
                                            {{ trans('global.user.fields.username_helper') }}
                                        </p>
                                    </div>
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="email">{{ trans('global.user.fields.email') }}*</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ old('email', isset($user) ? $user->email : '') }}">
                                        @if($errors->has('email'))
                                        <em class="invalid-feedback" style="color:#e40505">
                                            {{ $errors->first('email') }}
                                        </em>
                                        @endif
                                        <p class="helper-block">
                                            {{ trans('global.user.fields.email_helper') }}
                                        </p>
                                    </div>
                                    <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                        <label for="roles">{{ trans('global.user.fields.roles') }}*
                                            <select name="roles" id="roles" class="form-control select2">
                                                <option value="">--Select--</option>
                                                @foreach($roles as $id => $roles)
                                                <option value="{{ $id }}"
                                                    {{ (isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>
                                                    {{ $roles }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('roles'))
                                            <em class="invalid-feedback" style="color:#e40505">
                                                {{ $errors->first('roles') }}
                                            </em>
                                            @endif
                                            <p class="helper-block">
                                                {{ trans('global.user.fields.roles_helper') }}
                                            </p>
                                    </div>
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label for="password">{{ trans('global.user.fields.password') }}</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                        @if($errors->has('password'))
                                        <em class="invalid-feedback" style="color:#e40505">
                                            {{ $errors->first('password') }}
                                        </em>
                                        @endif
                                        <p class="helper-block">
                                            {{ trans('global.user.fields.password_helper') }}
                                        </p>
                                    </div>
                                    <button type="submit" class="btn btn-default">Sign Up</button>
                                    <!-- <div id="paypal-button-container"></div> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <!-- <script>
        paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '9'
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
          console.log('sssssssss',details);
        // This function shows a transaction success message to your buyer.
        alert('Transaction completed by ' + details.payer.name.given_name);
      });
    }
  }).render('#paypal-button-container');
    </script> -->
@endsection
@endsection
