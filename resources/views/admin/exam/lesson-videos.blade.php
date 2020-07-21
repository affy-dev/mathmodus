@extends('layouts.admin')
@section('content')
<div class="container">
    @if ($showBackBtn)
    <div class="row justify-content-start">
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
        </div>
    </div>
    @endif
    <div class="row headingBox" style="margin-top: 10px;">
        <div class="col-sm-12">
            <h3>Learning Videos : [ {{$courseName->course_name}} ]</h3>
        </div>
    </div>

    @if($lessonVideos->count() == 0)
    <div class="alert alert-danger">
        <strong>
            <h4>No Lessons Videos available in this course!</h4>
        </strong>
    </div>
    @endif
    <div class="row">
        <?php 
            $counter = 0;
        ?>
        @foreach($lessonVideos as $key => $lessonDetails)
        <div class="col-lg-4 col-sm-12 col-md-12">
            <?php $counter++; ?>
            <div class="card card-custom-css">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;font-size: 18px;color: #D98938;">
                    ({{ ($lessonVideos->currentpage()-1) * $lessonVideos->perpage() + $key + 1 }}) {{$lessonDetails->lesson_name}}</h5>
                </div>
                <div class="card-footer">
                    <?php
                    if( strpos($lessonDetails->video_url, ',') !== false ) {
                    ?>
                    <button type="button" class="btn btn-primary testBtn-blue launch-video-popup btn-block" 
                        data-toggle="modal"
                        data-target="#exampleModal" 
                        data-lesson_name='{{$lessonDetails->lesson_name}}'
                        data-vidoes="{{$lessonDetails->video_url}}"
                        data-lessonId='{{$lessonDetails->id}}'
                        data-courseId='{{$courseId}}'
                    >
                        <i class="fas fa-play"></i> Watch
                    </button>
                    <?php 
                        } else {
                    ?>
                    <button type="button" class="btn btn-warning video-btn testBtn-blue btn-block" 
                        data-toggle="modal"
                        data-src="{{$lessonDetails->video_url}}"
                        data-target="#myModal"
                        data-misc_urls='{{$lessonDetails->misc_urls}}'
                        data-lessonId='{{$lessonDetails->id}}'
                        data-courseId='{{$courseId}}'
                        >
                        <i class="fas fa-play"></i> Watch
                    </button>
                    <?php 
                        }
                    ?>
                    <a href="{{ route('admin.exams.takeexam', ['courseId' => $courseId, 'lessonId' => $lessonDetails->id]) }}"
                        class="btn btn-default courseBtn-red btn-block">Take Test</a>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <div class="row justify-content-center">
        <div class="col-4">
            {{ $lessonVideos->links() }}
        </div>
    </div>
</div>

<!--Select Video Modal Popup -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lessonTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group" id="videoList">

                </div>
            </div>
        </div>
    </div>
</div>


<!--Launch Video Modal Popup -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- 16:9 aspect ratio -->
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always" allow="autoplay"
                        allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen"
                        msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen"
                        webkitallowfullscreen="webkitallowfullscreen"></iframe>
                </div>
                <div class="list-group" id="miscGroupUrl" style="margin-top:20px">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Ends here Video Modal Popup -->

@section('scripts')
<script>
function openMultipleVideos(obj) {
    const videoSrc = 'https://www.youtube.com/embed/' + obj.getAttribute('data-src');
    $('#myModal').on('shown.bs.modal', function(e) {

        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src', videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })

    // stop playing the youtube video when I close the modal
    $('#myModal').on('hide.bs.modal', function(e) {
        $('#miscGroupUrl').html('');
        // a poor man's stop video
        $("#video").attr('src', videoSrc);
    })
    // $("#video").attr('src', videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
}

$(document).ready(function() {

    $('.launch-video-popup').click(function() {
        $('#videoList').html('');
        
        const lesson_name = $(this).data("lesson_name");
        $('#lessonTitle').html(lesson_name);
        const vidoes = $(this).data("vidoes");
        vidoes.split(',').forEach((value, index) => {
            index++;
            $('#videoList').append(
                '<button type="button" class="btn btn-warning video-btn testBtn-blue" onClick="openMultipleVideos(this)" data-toggle="modal" data-src="' + value + '" data-target="#myModal" style="margin-bottom: 10px;"><i class="fas fa-play"></i> Play '+lesson_name+' '+index+'</button>')
        })

    })

    // Gets the video src from the data-src on each button
    let videoSrc;
    $('.video-btn').click(function() {
        if ($(this).data("src") === '') {
            swal({
                title: "Error!",
                text: "Video Not Available!",
                icon: "error",
                buttons: false,
            });
            return false;
        }

        videoSrc = 'https://www.youtube.com/embed/' + $(this).data("src");
        const misc_urls = $(this).data("misc_urls");
        const lessonId = $(this).data("lessonid");
        const courseId = $(this).data("courseid");
        const routeUrl = "{{ route('admin.exams.takeexam') }}/"+courseId+"/"+lessonId;
        misc_urls.split(',').forEach((value) => {
            $('#miscGroupUrl').append('<a href="' + value +
                '" target="_blank" class="list-group-item list-group-item-action flex-column align-items-start"><p class="mb-1">' +
                (value ? value : 'No further Links attached to this lesson') + '</p><a class="btn btn-warning" href="'+routeUrl+'" role="button">Take Test ?</a></a>')
        })
    });

    let content;
    $('.prerequisiteBtn').click(function() {
        content = $(this).data("text");
        const contentArr = content.split(',');
        let finalContent = '';
        contentArr.forEach((item, index) => {
            finalContent += '<p>' + item + '</p>';
        });
        const modalContent = $('#modalContent');
        modalContent.html(finalContent);
    });

    // when the modal is opened autoplay it  
    $('#myModal').on('shown.bs.modal', function(e) {
        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src', videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })

    // stop playing the youtube video when I close the modal
    $('#myModal').on('hide.bs.modal', function(e) {
        $('#miscGroupUrl').html('');
        // a poor man's stop video
        $("#video").attr('src', videoSrc);
    })

    // document ready  
});

window.onbeforeunload = function() {
    myUnloadEvent();
}

function myUnloadEvent() {

    window.open("www.google.com");

}
</script>
@endsection
@endsection