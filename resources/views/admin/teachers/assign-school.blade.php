@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.teacher.assign_school_text') }}
    </div>

    <div class="card-body">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form action="{{ route("admin.teachers.assignSchool") }}" method="POST" >
            @csrf
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
            <input name="teacherId" type="hidden" value="{{$teacherId}}" />
                <select name="school_id" id="school_id" class="form-control select2" >
                    <option value='0'>Select School</option>
                    @foreach($allSchools as $key => $value)
                        <option value={{ $value['id'] }} >
                            {{ $value['school_name'] }}
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
@section('scripts')
<script>
    swal("Good job!", "You clicked the button!", "error");
</script>
@endsection
@endsection