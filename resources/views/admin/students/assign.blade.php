@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.school.assign_principal_text') }}
    </div>

    <div class="card-body">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form action="{{ route("admin.schools.assignTeachersPost") }}" method="POST" >
            @csrf
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
            <input name="schoolId" type="hidden" value="{{$schoolId}}" />
                <select name="user_id" id="user_id" class="form-control select2" >
                    <option value='0'>Select Principal</option>
                    @foreach($allPrincipals as $key => $value)
                        <option value={{ $value['id'] }} >
                            {{ $value['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input class="btn btn-default" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection