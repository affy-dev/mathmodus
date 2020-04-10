@extends('layouts.admin')
@section('content')
        <div class="container">
            <div class="row justify-content-start">
                <div class="col-12">
                @if ($showBackBtn)
                    <a href="{{ url()->previous() }}" class="btn btn-default">Back</a> 
                @endif
                @if (!$showBackBtn)
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-primary">Take another Test</a> 
                @endif
                </div>
            </div>
            
            <h1 style="text-align:center">Test Analysis</h1>
            
            <div class="row" style="margin-bottom:15px">
                <div class="col-12">
                <div class="card-group">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="mb-0"><strong>{{count($correctQuestionDetails) + count($wrongQuestionDetails)}}</strong></h4>
                            <small class="text-muted-light">TOTAL</small>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-success mb-0"><strong>{{count($correctQuestionDetails)}}</strong></h4>
                            <small class="text-muted-light">CORECT</small>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-danger mb-0"><strong>{{count($wrongQuestionDetails)}}</strong></h4>
                            <small class="text-muted-light">WRONG</small>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            @if(count($wrongQuestionDetails) > 0)
            <div class="row">
                <div class="col-sm" style="background: #cbd2ce;padding: 15px;border-radius: 15px;">
                    <h4>Wrong Answers</h4>
                    @foreach($wrongQuestionDetails as $wrongDetails)
                        <div class="wrong-ans" role="alert">
                            {{$wrongDetails['question_text']}} <span style="float:right"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                        </div>
                        <div style="width:100%;margin-bottom: 4%;">
                            <button type="button" class="btn btn-default video-btn" data-toggle="modal" data-src="{{$wrongDetails['video_url']}}" data-target="#myModal">
                                Topic Video
                            </button>
                            <button type="button" class="btn btn-default prerequisiteBtn" data-toggle="modal" data-text="{{$wrongDetails['topic_pre_requisite']}}" data-target="#exampleModal">
                                Pre-Requisuite Topic
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(count($correctQuestionDetails) > 0)
            <div class="row">
                <div class="col-sm" style="background: #cbd2ce;padding: 15px;border-radius: 15px;margin-top: 2%;">
                    <h4>Right Answers</h4>
                    @foreach($correctQuestionDetails as $correctDetails)
                        <div class="correct-ans" role="alert">
                            {{$correctDetails['question_text']}} <span style="float:right"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                        </div>
                        <div style="width:100%;margin-bottom: 4%;">
                            <button type="button" class="btn btn-default video-btn" data-toggle="modal" data-src="{{$correctDetails['video_url']}}" data-target="#myModal">
                            Topic Video
                            </button>
                            <button type="button" class="btn btn-default prerequisiteBtn" data-toggle="modal" data-text="{{$correctDetails['topic_pre_requisite']}}" data-target="#exampleModal">
                                Pre-Requisuite Topic
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
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
                            <iframe class="embed-responsive-item" src="" id="video"  allowscriptaccess="always" allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!-- Ends here Video Modal Popup -->

        <!--Content Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pre-Requisite Topics</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                ...
            </div>
            </div>
        </div>
        </div>

@section('scripts')  
<script>
    $(document).ready(function() {
    // Gets the video src from the data-src on each button
    var $videoSrc;  
    $('.video-btn').click(function() {
        $videoSrc = $(this).data( "src" );
        if($videoSrc == 'not_available') {
            alert('Video Not Available')
            return false;
        }
    });

    let content;
    $('.prerequisiteBtn').click(function() {
        content = $(this).data( "text" );
        const contentArr = content ? content.split(',') : false;
        let finalContent = '';
        if(contentArr) {
            contentArr.forEach((item, index) => {
                finalContent+='<p>'+item+'</p>';
            });
        } else {
            finalContent = 'Not available'
        }
         
        const modalContent = $('#modalContent');
        modalContent.html(finalContent);
    });

    
    // when the modal is opened autoplay it  
    $('#myModal').on('shown.bs.modal', function (e) {
        
    // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
    $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" ); 
    })
    
    // stop playing the youtube video when I close the modal
    $('#myModal').on('hide.bs.modal', function (e) {
        // a poor man's stop video
        $("#video").attr('src',$videoSrc); 
    }) 
    
    // document ready  
    });
</script>
@endsection
@endsection