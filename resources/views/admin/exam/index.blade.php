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
                    <a href="{{ route('admin.exams.takeexam', $course->id) }}"
                        class="btn btn-default testBtn-blue btn-block">Diagnostic Test</a>
                    <a href="{{ route('admin.exams.lessonVideos', $course->id) }}"
                        class="btn btn-default courseBtn-red btn-block">Start Learning</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- <script type="text/javascript">
$('.btnprn').();
</script> -->
@endsection