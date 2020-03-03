@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('global.school.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.schools.update", [$school->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('school_name') ? 'has-error' : '' }}">
                <label for="school_name">{{ trans('global.school.fields.school_name') }}*</label>
                <input type="text" id="school_name" name="school_name" class="form-control" value="{{ old('school_name', isset($school) ? $school->school_name : '') }}">
                @if($errors->has('school_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('school_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.school.fields.school_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('school_address') ? 'has-error' : '' }}">
                <label for="school_address">{{ trans('global.school.fields.school_address') }}</label>
                <textarea id="school_address" name="school_address" class="form-control ">{{ old('school_address', isset($school) ? $school->school_address : '') }}</textarea>
                @if($errors->has('school_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('school_address') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.school.fields.school_address_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('school_pincode') ? 'has-error' : '' }}">
                <label for="school_pincode">{{ trans('global.school.fields.school_pincode') }}</label>
                <input type="text" id="school_pincode" name="school_pincode" class="form-control" value="{{ old('school_pincode', isset($school) ? $school->school_pincode : '') }}" >
                @if($errors->has('school_pincode'))
                    <em class="invalid-feedback">
                        {{ $errors->first('school_pincode') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.school.fields.school_pincode_helper') }}
                </p>
            </div>
            <div>
            
            <div class="form-group {{ $errors->has('school_phone') ? 'has-error' : '' }}">
                <label for="school_phone">{{ trans('global.school.fields.school_phone') }}</label>
                <input type="text" id="school_phone" name="school_phone" class="form-control" value="{{ old('school_phone', isset($school) ? $school->school_phone : '') }}" >
                @if($errors->has('school_phone'))
                    <em class="invalid-feedback">
                        {{ $errors->first('school_phone') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.school.fields.school_phone_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection