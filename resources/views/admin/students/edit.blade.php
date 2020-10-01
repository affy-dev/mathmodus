@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.edit') }} {{ trans('global.student.title_singular') }} Details
    </div>
    
    <div class="card-body">
        <form action="{{ route("admin.students.update", [$studentId]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('global.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control"
                value="{{ old('name', isset($name) ? $name : '') }}">
                @if($errors->has('name'))
                <em class="invalid-feedback" style="display:block">
                    {{ $errors->first('name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.user.fields.name_helper') }}
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
                <label for="email">{{ trans('global.user.fields.email') }}*</label>
                <input type="email" id="email" name="email" class="form-control"
                value="{{ old('email', isset($emailId) ? $emailId : '') }}">
                @if($errors->has('email'))
                <em class="invalid-feedback" style="display:block">
                    {{ $errors->first('email') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.user.fields.email_helper') }}
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

            <div class="form-group {{ $errors->has('teacher_id') ? 'has-error' : '' }}">
                <label for="teacher_id">Select Teacher*</label>
                <select id="teacher_id" name="teacher_id" class="form-control">
                    <option value="">--- Select Teacher ---</option>
                    @foreach ($allTeacher as $key => $value)
                        <option value="{{ $value->user_id }}" {{ (old('teacher_id') == $value->user_id) || ($teacher_id == $value->user_id)  ? 'selected' : ''}}>{{ $value->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('teacher_id'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('teacher_id') }}
                    </em>
                @endif
            </div>

            <!-- <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
                <label for="dob">{{ trans('global.student.fields.dob') }}*</label>
                <input type="text" id="dob" name="dob" class="form-control date" value="{{ old('dob', isset($studentDOB) ? $studentDOB : '') }}">
                @if($errors->has('dob'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('dob') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.dob_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                <label for="gender">{{ trans('global.student.fields.gender') }}*</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="">--- Select Gender ---</option>
                    @foreach ($gender as $key => $value)
                        <option value="{{ $key }}" {{ (old('gender') == $key) || ($studentGender == $key) ? 'selected' : ''}}>{{ $value }}</option>
                    @endforeach
                </select>
                @if($errors->has('gender'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('gender') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.gender_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('blood_group') ? 'has-error' : '' }}">
                <label for="blood_group">{{ trans('global.student.fields.blood_group') }}</label>
                <select id="blood_group" name="blood_group" class="form-control">
                    <option value="">--- Select Blood Group ---</option>
                    @foreach ($blood_group as $key => $value)
                        <option value="{{ $key }}" {{ (old('blood_group') == $key) || ($studentBloodGroup == $key) ? 'selected' : ''}}>{{ $value }}</option>
                    @endforeach
                </select>
                @if($errors->has('blood_group'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('blood_group') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.blood_group_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">
                <label for="phone_no">{{ trans('global.student.fields.phone_no') }}</label>
                <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($studentPhoneNo) ? $studentPhoneNo : '') }}">
                @if($errors->has('phone_no'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('phone_no') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.phone_no_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('father_name') ? 'has-error' : '' }}">
                <label for="father_name">{{ trans('global.student.fields.father_name') }}*</label>
                <input type="text" id="father_name" name="father_name" class="form-control" value="{{ old('father_name', isset($fatherName) ? $fatherName : '') }}">
                @if($errors->has('father_name'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('father_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.father_name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('father_phone_no') ? 'has-error' : '' }}">
                <label for="father_phone_no">{{ trans('global.student.fields.father_phone_no') }}*</label>
                <input type="text" id="father_phone_no" name="father_phone_no" class="form-control" value="{{ old('father_phone_no', isset($fatherPhone) ? $fatherPhone : '') }}">
                @if($errors->has('father_phone_no'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('father_phone_no') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.father_phone_no_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('mother_name') ? 'has-error' : '' }}">
                <label for="mother_name">{{ trans('global.student.fields.mother_name') }}*</label>
                <input type="text" id="mother_name" name="mother_name" class="form-control" value="{{ old('mother_name', isset($studentMothenName) ? $studentMothenName : '') }}">
                @if($errors->has('mother_name'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('mother_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.mother_name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('mother_phone_no') ? 'has-error' : '' }}">
                <label for="mother_phone_no">{{ trans('global.student.fields.mother_phone_no') }}</label>
                <input type="text" id="mother_phone_no" name="mother_phone_no" class="form-control" value="{{ old('mother_phone_no', isset($studentMotherPhoneNo) ? $studentMotherPhoneNo : '') }}">
                @if($errors->has('mother_phone_no'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('mother_phone_no') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.mother_phone_no_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('present_address') ? 'has-error' : '' }}">
                <label for="present_address">{{ trans('global.student.fields.present_address') }}*</label>
                <textarea id="present_address" name="present_address" class="form-control "><?php echo e(old('present_address', isset($present_address) ? $present_address : '')); ?></textarea>
                @if($errors->has('present_address'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('present_address') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.present_address_helper') }}
                </p>
            </div>


            <div class="form-group {{ $errors->has('permanent_address') ? 'has-error' : '' }}">
                <label for="permanent_address">{{ trans('global.student.fields.permanent_address') }}*</label>
                <textarea id="permanent_address" name="permanent_address" class="form-control "><?php echo e(old('permanent_address', isset($permanent_address) ? $permanent_address : '')); ?></textarea>
                @if($errors->has('permanent_address'))
                    <em class="invalid-feedback" style="display:block">
                        {{ $errors->first('permanent_address') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.student.fields.permanent_address_helper') }}
                </p>
            </div> -->
            <input type="hidden" name="userId" value={{$userId}}>
            <div>
                <input class="btn btn-default" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection