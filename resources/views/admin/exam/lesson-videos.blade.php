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
    <div class="row justify-content-center" style="border-bottom: 1px solid #caccce;margin-bottom: 15px;">
        <div class="col-4">
            <h3>Course Videos</h3>
        </div>
    </div>

    <div class="row">
        @if($topicVideos->count() == 0)
        <div class="alert alert-danger">
            <strong>
                <h4>No Lessons Videos available in this course!</h4>
            </strong>
        </div>
        @endif
        @foreach($topicVideos as $topicDetails)
        <div class="col-sm-3">
            <div class="card text-white bg-default mb-3" style="max-width: 18rem;min-height:243px;">
                <div class="card-header" style="background:#D98938">
                    <h5 class="card-title">{{$topicDetails->topic_name}}</h5>
                </div>
                <div class="card-body" >
                    <div class="btn-group btn-group-justified">
                        <button type="button" style="margin-right:10px; background:#ead38b" class="btn btn-warning video-btn"
                            data-toggle="modal" data-src="{{$topicDetails->video_url}}" data-target="#myModal"
                            data-misc_urls='{{$topicDetails->misc_urls}}'>
                            Click to Study
                        </button>
                        <button type="button" class="btn btn-warning" style="background:#ead38b">
                            <a href="{{ route('admin.exams.takeexam', ['courseId' => $courseId, 'lessonId' => $topicDetails->lession_id]) }}"
                                style="text-decoration: none;color: #000;">Take Test</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <div class="row justify-content-center">
        <div class="col-4">
            {{ $topicVideos->links() }}
        </div>
    </div>
</div>

<!-- Video Modal Popup -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- 16:9 aspect ratio -->
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always"
                        allow="autoplay"></iframe>
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
$(document).ready(function() {
    // Gets the video src from the data-src on each button
    var $videoSrc;
    $('.video-btn').click(function() {
        $videoSrc = $(this).data("src");
        const misc_urls = $(this).data("misc_urls");
        misc_urls.split(',').forEach((value) => {
            $('#miscGroupUrl').append('<a href="' + value +
                '" target="_blank" class="list-group-item list-group-item-action flex-column align-items-start"><p class="mb-1">' +
                (value ? value : 'No further Links attached for this topic') + '</p></a>')
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
        $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })

    // stop playing the youtube video when I close the modal
    $('#myModal').on('hide.bs.modal', function(e) {
        $('#miscGroupUrl').html('');
        // a poor man's stop video
        $("#video").attr('src', $videoSrc);
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