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

    <div class="row headingBox" style="margin-top: 10px;">
        <div class="col-sm-12">
            <h3>Test Analysis</h3>
        </div>
    </div>
    <div id="container"></div>
    <div class="row" style="margin-bottom:15px">
        <div class="col-12">
            <div class="card-group exam-score">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-0">
                            <strong>{{count($correctQuestionDetails) + count($wrongQuestionDetails)}}</strong></h4>
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
                        <small class="text-muted-light">INCORRECT</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="accordion">
        @if(count($wrongQuestionDetails) > 0)
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                        aria-controls="collapseOne" style="font-weight:bold" title="Click to see the details">
                        Incorrect Answers
                    </button>
                    <span style="float: right;margin-top: 6px;cursor:pointer;color: #D98938;" data-toggle="collapse"
                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"
                        title="Click to see the details"><i class="fas fa-arrow-down"></i></span>
                </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    @foreach($wrongQuestionDetails as $wrongDetails)
                    <div class="wrong-ans" role="alert">
                        <?php 
                            $qText = str_replace('src="../', 'src="../../../', $wrongDetails['question_text']);
                            echo strip_tags($qText, '<table><img><p><br><br>');
                        ?>
                        <span style="float:right"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                    </div>
                    <div style="width:100%;margin-bottom: 4%;">
                        <button type="button" class="btn btn-default video-btn" data-toggle="modal"
                            data-src="{{$wrongDetails['video_url']}}" data-target="#videoModal">
                            Topic Video
                        </button>
                        <button type="button" class="btn btn-default prerequisiteBtn" data-toggle="modal"
                            data-text="{{json_encode($wrongDetails['topic_pre_requisite'])}}" data-target="#preRequisiteModal">
                            Pre-Requisuite Topic
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @if(count($correctQuestionDetails) > 0)
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="false" aria-controls="collapseTwo" style="font-weight:bold"
                        title="Click to see the details">
                        Correct Answers
                    </button>
                    <span style="float: right;margin-top: 6px;cursor:pointer;color: #D98938;" data-toggle="collapse"
                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"
                        title="Click to see the details"><i class="fas fa-arrow-down"></i></span>
                </h5>

            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    @foreach($correctQuestionDetails as $correctDetails)
                    <div class="correct-ans" role="alert">
                        <?php 
                            $qText = str_replace('src="../', 'src="../../../', $correctDetails['question_text']);
                            echo strip_tags($qText, '<table><img><p><br><br>');
                        ?>
                        <span style="float:right"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                    </div>
                    <div style="width:100%;margin-bottom: 4%;">
                        <button type="button" class="btn btn-default video-btn" data-toggle="modal"
                            data-src="{{$correctDetails['video_url']}}" data-target="#videoModal">
                            Topic Video
                        </button>
                        <button type="button" class="btn btn-default prerequisiteBtn" data-toggle="modal"
                            data-text="{{json_encode($correctDetails['topic_pre_requisite'])}}" data-target="#preRequisiteModal">
                            Pre-Requisuite Topic
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


<!--Content Modal -->
<div class="modal fade" id="preRequisiteModal" tabindex="-1" role="dialog" aria-labelledby="preRequisiteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preRequisiteModalLabel">Pre-Requisite Topics</h5>
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

<!-- Video Modal Popup -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="preRequisiteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
            </div>
        </div>
    </div>
</div>
<!-- Ends here Video Modal Popup -->


@section('scripts')
<script>
function openMultipleVideos(obj) {
    const videoSrc = obj.getAttribute('data-src');
    
    $('#videoModal').on('shown.bs.modal', function(e) {
        // $('#preRequisiteModal').modal('hide');
        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src', videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })

    // stop playing the youtube video when I close the modal
    $('#videoModal').on('hide.bs.modal', function(e) {
        $('#miscGroupUrl').html('');
        // a poor man's stop video
        $("#video").attr('src', videoSrc);
    })
    // $("#video").attr('src', videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
}

$(document).ready(function() {
            // Gets the video src from the data-src on each button
            var $videoSrc;
            $('.video-btn').click(function() {
                $videoSrc = $(this).data("src");
                if ($videoSrc == 'not_available') {
                    swal({
                        title: "Error!",
                        text: "Video Not Available!",
                        icon: "error",
                        buttons: false,
                    });
                    return false;
                }
            });

            let content;
            $('.prerequisiteBtn').click(function() {
                content = $(this).data("text");
                if (Array.isArray(content) &&  content.length==0) {
                    swal({
                        title: "Error!",
                        text: "Topic Pre-requisites Not Available!",
                        icon: "error",
                        buttons: false,
                    });
                    return false;
                }
                let finalContent = '';
                if (content) {
                    for(let topicName in content) {
                    	finalContent += '<button type="button" class="btn btn-warning video-btn testBtn-blue" onClick="openMultipleVideos(this)" data-toggle="modal" data-src="' + content[topicName] + '" data-target="#videoModal" style="margin-bottom: 10px;"><i class="fas fa-play"></i> '+topicName+'</button><br>';
                    }
                }
                $('#modalContent').html(finalContent);
            });


            // when the modal is opened autoplay it  
            $('#videoModal').on('shown.bs.modal', function(e) {
                // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
                $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
            })

            // stop playing the youtube video when I close the modal
            $('#videoModal').on('hide.bs.modal', function(e) {
                // a poor man's stop video
                $("#video").attr('src', $videoSrc);
            })



// ==============HightChart Implementation===================

    const correctPercentage = ({{count($correctQuestionDetails)}} / {{count($correctQuestionDetails) + count($wrongQuestionDetails)}}) * 100;
    const wrongPercentage = ({{count($wrongQuestionDetails)}} / {{count($correctQuestionDetails) + count($wrongQuestionDetails)}}) * 100;

        Highcharts.setOptions({
            colors: ['#f34807', '#50B432']
        });

        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Test Analysis'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</br>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Test Result',
                colorByPoint: true,
                data: [{
                    name: 'Incorrect Answers',
                    y: wrongPercentage,
                    sliced: true,
                    selected: true
                }, {
                    name: 'Correct Answers',
                    y: correctPercentage
                }]
            }]
        });
        // document ready  
    });
</script>
@endsection
@endsection