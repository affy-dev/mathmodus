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

        <div class="card-deck">
            @foreach($courses as $course)
            <div class="card card-custom-css">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;font-size: 18px;color: #D98938;">
                        {{$course->course_name}}</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-5">
                            <a href="{{ route('admin.exams.takeexam', $course->id) }}"
                                class="btn btn-default testBtn-blue">Take Test</a>
                        </div>
                        <div class="col-5">
                            <a href="{{ route('admin.exams.lessonVideos', $course->id) }}"
                                class="btn btn-default courseBtn-red">Course
                                Videos</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection