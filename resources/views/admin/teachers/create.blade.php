@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        Add Teacher Details
    </div>

    <div class="card-body">
        <form action="{{ route("admin.teachers.store") }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <label for="user_id">Select Teacher*</label>
                <select id="user_id" name="user_id" class="form-control">
                    <option value="">--- Select Teacher ---</option>
                    @foreach ($users as $key => $value)
                        <option value="{{ $value->id }}" {{ old('user_id') == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_id'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('user_id') }}
                    </em>
                @endif
               
            </div>

            <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
                <label for="dob">DOB*</label>
                <input type="text" id="dob" name="dob" class="form-control date" value="{{ old('dob', isset($dob) ? $teacher->dob : '') }}">
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
                        <option value="{{ $key }}" {{ old('gender') == $key ? 'selected' : ''}}>{{ $value }}</option>
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

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">{{ trans('global.teacher.fields.email') }}*</label>
                <input type="text" id="email" name="email" class="form-control" value="{{ old('email', isset($email) ? $teacher->email : '') }}">
                @if($errors->has('email'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.email_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">
                <label for="phone_no">{{ trans('global.teacher.fields.phone_no') }}*</label>
                <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($phone_no) ? $teacher->phone_no : '') }}">
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
                <textarea id="address" name="address" class="form-control "><?php echo e(old('address', isset($teacher) ? $teacher->address : '')); ?></textarea>
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
                <input type="text" id="designation" name="designation" class="form-control" value="{{ old('designation', isset($designation) ? $teacher->designation : '') }}">
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
                <input type="text" id="qualification" name="qualification" class="form-control" value="{{ old('qualification', isset($qualification) ? $teacher->qualification : '') }}">
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
                <input type="text" id="joining_date" name="joining_date" class="form-control date" value="{{ old('joining_date', isset($joining_date) ? $teacher->joining_date : '') }}">
                @if($errors->has('joining_date'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('joining_date') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.teacher.fields.joining_date_helper') }}
                </p>
            </div>

            <div>
                <input class="btn btn-default" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection