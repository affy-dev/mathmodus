@extends('layouts.admin')
@section('content')
<div class="privew">
    <div class="container">
        <div class="col-sm">
            @if($errors->any())
            <div class="alert alert-danger">
                <strong>
                    <h4>{{$errors->first()}}</h4>
                </strong>
            </div>
            @endif
        </div>
        <div class="row">
            @foreach($courses as $course)
            <div class="col-sm">
                <div class="card" style="width: 18rem;background:#ef78008c; color:#fff">
                    <div class="card-body">
                        <h5 class="card-title">{{$course->course_name}}</h5>
                        <a href="{{ route('admin.exams.takeexam', $course->id) }}" class="btn btn-default" style="background:#ead38b;color:#000">Take Test</a>
                        <a href="{{ route('admin.exams.lessonVideos', $course->id) }}" class="btn btn-default" style="background:#ead38b;color:#000">Course
                            Videos</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection