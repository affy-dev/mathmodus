@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.edit') }} {{ trans('global.teacher.title_singular') }} Details
    </div>
    <div class="card-body">
        <form action="{{ route("admin.teachers.update", [$teacherId]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('global.teacher.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($name) ? $name : '') }}">
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <label for="username">{{ trans('global.user.fields.username') }}*</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="{{ old('username', isset($userName) ? $userName : '') }}">
                @if($errors->has('username'))
                <em class="invalid-feedback" style="display:block">
                    {{ $errors->first('username') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.user.fields.username_helper') }}
                </p>
            </div>
            
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">{{ trans('global.teacher.fields.email') }}*</label>
                <input type="text" id="email" name="email" class="form-control" value="{{ old('email', isset($email) ? $email : '') }}">
                @if($errors->has('email'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.email_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('global.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control">
                @if($errors->has('password'))
                <em class="invalid-feedback" style="display:block">
                    {{ $errors->first('password') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.user.fields.password_helper') }}
                </p>
            </div>
            <!-- <div class="form-group {{ $errors->has('school_id') ? 'has-error' : '' }}">
                <label for="school_id">Select School*</label>
                <select id="school_id" name="school_id" class="form-control">
                    <option value="">--- Select School ---</option>
                    @foreach ($allSchools as $key => $value)
                        <option value="{{ $value->id }}" {{ old('school_id') == $value->id || ($schoolId == $value->id) ? 'selected' : ''}}>{{ $value->school_name }}</option>
                    @endforeach
                </select>
                @if($errors->has('school_id'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('school_id') }}
                    </em>
                @endif
            </div> -->

            <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
                <label for="dob">{{ trans('global.teacher.fields.dob') }}*</label>
                <input type="text" id="dob" name="dob" class="form-control date" value="{{ old('dob', isset($dob) ? $dob : '') }}">
                @if($errors->has('dob'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('dob') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.dob_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                <label for="gender">{{ trans('global.teacher.fields.gender') }}*</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="">--- Select Gender ---</option>
                    @foreach ($gender as $key => $value)
                        <option value="{{ $key }}" {{ (old('gender') == $key) || ($genderValue == $key) ? 'selected' : ''}}>{{ $value }}</option>
                    @endforeach
                </select>
                @if($errors->has('gender'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('gender') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.gender_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">
                <label for="phone_no">{{ trans('global.teacher.fields.phone_no') }}</label>
                <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($phone_no) ? $phone_no : '') }}">
                @if($errors->has('phone_no'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('phone_no') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.phone_no_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">{{ trans('global.teacher.fields.address') }}*</label>
                <textarea id="address" name="address" class="form-control "><?php echo e(old('address', isset($address) ? $address : '')); ?></textarea>
                @if($errors->has('address'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('address') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.address_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                <label for="designation">{{ trans('global.teacher.fields.designation') }}</label>
                <input type="text" id="designation" name="designation" class="form-control" value="{{ old('designation', isset($designation) ? $designation : '') }}">
                @if($errors->has('designation'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('designation') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.designation_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('qualification') ? 'has-error' : '' }}">
                <label for="qualification">{{ trans('global.teacher.fields.qualification') }}</label>
                <input type="text" id="qualification" name="qualification" class="form-control" value="{{ old('qualification', isset($qualification) ? $qualification : '') }}">
                @if($errors->has('qualification'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('qualification') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.qualification_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('joining_date') ? 'has-error' : '' }}">
                <label for="joining_date">{{ trans('global.teacher.fields.joining_date') }}</label>
                <input type="text" id="joining_date" name="joining_date" class="form-control date" value="{{ old('joining_date', isset($joining_date) ? $joining_date : '') }}">
                @if($errors->has('joining_date'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('joining_date') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.joining_date_helper') }}
                </p>
            </div>
            <input type="hidden" name="userId" value={{$userId}}>
            <div>
                <input class="btn btn-default" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection