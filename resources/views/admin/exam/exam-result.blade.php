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
    
    <div class="row">
        
        <div class="col-sm-12">
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
        <div class="col-sm-12">
            <div id="resultGraph"></div>
        </div>
        <div class="col-sm-12">
            <div class="accordion js-accordion">
                @if(count($wrongQuestionDetails) > 0)
                <div class="accordion__item js-accordion-item">
                    <div class="accordion-header js-accordion-header">Incorrect Answers (Click to see the details)</div>
                    <div class="accordion-body js-accordion-body">
                        <div class="accordion-body__contents">
                            <div class="card-body">
                                <?php $questionCount = 0; ?>
                                @foreach($wrongQuestionDetails as $wrongDetails)
                                <div class="wrong-ans" role="alert">
                                    <?php 
                                        $qText = str_replace('src="../', 'src="../../../', $wrongDetails['question_text']);
                                        echo '('.$wrongDetails['quesNum'].') '.strip_tags($qText, '<table><img><p><br><br>');
                                    ?>
                                    <span style="float:right"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                </div>
                                <div style="width:100%;margin-bottom: 4%;">
                                    @if($testFromLessonsTab)
                                        <button type="button" class="btn btn-default video-btn" data-toggle="modal"
                                            data-src="{{$wrongDetails['video_url']}}" 
                                            data-target="#videoModal"
                                            data-misc_urls="{{$wrongDetails['misc_urls']}}"
                                            data-lesson-video="false"
                                            data-lessonId="{{$wrongDetails['lesson_id']}}"
                                            data-courseId="{{$wrongDetails['courseId']}}"
                                        >
                                            Topic Video
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-default video-btn" data-toggle="modal"
                                        data-src="{{$wrongDetails['full_video_url']}}" 
                                        data-target="#videoModal"
                                        data-misc_urls="{{$wrongDetails['misc_urls']}}"
                                        data-lesson-video="true"
                                        data-lessonId="{{$wrongDetails['lesson_id']}}"
                                        data-courseId="{{$wrongDetails['courseId']}}"
                                    >
                                        Full Lesson Video
                                    </button>
                                    <!-- <button type="button" class="btn btn-default prerequisiteBtn" data-toggle="modal"
                                        data-text="{{json_encode($wrongDetails['topic_pre_requisite'])}}" data-target="#preRequisiteModal">
                                        Pre-Requisuite Topic
                                    </button> -->
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div> 
                </div>
                @endif

                @if(count($correctQuestionDetails) > 0)
                <div class="accordion__item js-accordion-item">
                    <div class="accordion-header js-accordion-header">Correct Answers (Click to see the details)</div>
                    <div class="accordion-body js-accordion-body">
                        <div class="accordion-body__contents">
                            <div class="card-body">
                                <?php $questionCount = 0; ?>
                                @foreach($correctQuestionDetails as $correctDetails)
                                <div class="correct-ans" role="alert">
                                    <?php 
                                        $qText = str_replace('src="../', 'src="../../../', $correctDetails['question_text']);
                                        echo '('.$correctDetails['quesNum'].') '.strip_tags($qText, '<table><img><p><br><br>');
                                    ?>
                                    <span style="float:right"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                                </div>
                                <div style="width:100%;margin-bottom: 4%;">
                                    @if($testFromLessonsTab)
                                        <button type="button" class="btn btn-default video-btn" data-toggle="modal"
                                            data-src="{{$correctDetails['video_url']}}" 
                                            data-target="#videoModal" 
                                            data-misc_urls="{{$correctDetails['misc_urls']}}"
                                            data-lesson-video="false"
                                            data-lessonId="{{$correctDetails['lesson_id']}}"
                                            data-courseId="{{$correctDetails['courseId']}}"
                                        >
                                            Topic Video
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-default video-btn" data-toggle="modal"
                                        data-src="{{$correctDetails['full_video_url']}}" 
                                        data-target="#videoModal"
                                        data-misc_urls="{{$correctDetails['misc_urls']}}"
                                        data-lesson-video="true"
                                        data-lessonId="{{$correctDetails['lesson_id']}}"
                                        data-courseId="{{$correctDetails['courseId']}}"
                                    >
                                        Full Lesson Video
                                    </button>
                                    <!-- <button type="button" class="btn btn-default prerequisiteBtn" data-toggle="modal"
                                        data-text="{{json_encode($correctDetails['topic_pre_requisite'])}}" data-target="#preRequisiteModal">
                                        Pre-Requisuite Topic
                                    </button> -->
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<input type="hidden" name="lessonId" id="lessonId" value="" />
<input type="hidden" name="courseId" id="courseId" value="" />

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
                
            </div>
        </div>
    </div>
</div>

<!-- Video Modal Popup -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="preRequisiteModalLabel" aria-hidden="true">
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
                <hr>
                <h4 class="modal-title">Additional Study Links</h4>
                <div class="list-group" id="takeTestBtn" style="margin-top:20px">
                    
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
}

$(document).ready(function() {
            // Gets the video src from the data-src on each button
            var $videoSrc;
            
            $('.video-btn').click(function() {
                $('#miscGroupUrl').html('');
                const checkFullLessonVideo = $(this).data("lesson-video");
                const lessonId = $(this).data("lessonid");
                const courseId = $(this).data("courseid");
                if(lessonId) {
                    $('#lessonId').val(lessonId);
                    $('#courseId').val(courseId);
                } else {
                    $('#lessonId').val('');
                    $('#courseId').val('');
                }
                $videoSrc = (checkFullLessonVideo === true) ? 'https://www.youtube.com/embed/'+ $(this).data("src") : $(this).data("src");
                if ($videoSrc == 'not_available') {
                    swal({
                        title: "Error!",
                        text: "Video Not Available!",
                        icon: "error",
                        buttons: false,
                    });
                    return false;
                }
                const misc_urls = $(this).data("misc_urls");
                if (misc_urls == 'not_available' || misc_urls == '') {
                    const routeUrl = "{{ route('admin.exams.takeexam') }}/"+courseId+"/"+lessonId;
                    $('#takeTestBtn').html('<a class="btn btn-warning" href="'+routeUrl+'" role="button">Want to Take Test ?</a>')
                    $('#miscGroupUrl').append('<p class="mb-1">No further Links attached to this lesson!</p>')
                } else {
                    const routeUrl = "{{ route('admin.exams.takeexam') }}/"+courseId+"/"+lessonId;
                    $('#takeTestBtn').html('<a class="btn btn-warning" href="'+routeUrl+'" role="button">Want to Take Test ?</a>')
                    misc_urls.split(',').forEach((value) => {
                        $('#miscGroupUrl').append('<a href="' + value +
                            '" target="_blank" class="list-group-item list-group-item-action flex-column align-items-start"><p class="mb-1">' +
                            (value ? value : 'No further Links attached to this lesson') + '</p></a>')
                    })
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
                $('#takeTestBtn').html('')
                $('#miscGroupUrl').html('');
                $('#lessonId').val('');
                $('#courseId').val('');
            })

            $('#videoModal').on('hidden.bs.modal', function () {
                swal({
                    title: "Do you want to give test for this lesson ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        const lessonId = $('#lessonId').val();
                        const newCourseId = $('#courseId').val();
                        const routeUrl = "{{ route('admin.exams.takeexam') }}/"+newCourseId+"/"+lessonId;
                        $('#lessonId').val('');
                        $('#courseId').val('');
                        window.location = routeUrl;
                        return;
                    } else {
                        $('#lessonId').val('');
                        $('#courseId').val('');
                        return;
                    }
                });
            // do something…
            })

// ==============HightChart Implementation===================

    const correctPercentage = ({{count($correctQuestionDetails)}} / {{count($correctQuestionDetails) + count($wrongQuestionDetails)}}) * 100;
    const wrongPercentage = ({{count($wrongQuestionDetails)}} / {{count($correctQuestionDetails) + count($wrongQuestionDetails)}}) * 100;

        Highcharts.setOptions({
            colors: ['#f34807', '#50B432']
        });

        Highcharts.chart('resultGraph', {
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


var accordion = (function(){
  
  var $accordion = $('.js-accordion');
  var $accordion_header = $accordion.find('.js-accordion-header');
  var $accordion_item = $('.js-accordion-item');
 
  // default settings 
  var settings = {
    // animation speed
    speed: 400,
    
    // close all other accordion items if true
    oneOpen: false
  };
    
  return {
    // pass configurable object literal
    init: function($settings) {
      $accordion_header.on('click', function() {
        accordion.toggle($(this));
      });
      
      $.extend(settings, $settings); 
      
      // ensure only one accordion is active if oneOpen is true
      if(settings.oneOpen && $('.js-accordion-item.active').length > 1) {
        $('.js-accordion-item.active:not(:first)').removeClass('active');
      }
      
      // reveal the active accordion bodies
      $('.js-accordion-item.active').find('> .js-accordion-body').show();
    },
    toggle: function($this) {
            
      if(settings.oneOpen && $this[0] != $this.closest('.js-accordion').find('> .js-accordion-item.active > .js-accordion-header')[0]) {
        $this.closest('.js-accordion')
               .find('> .js-accordion-item') 
               .removeClass('active')
               .find('.js-accordion-body')
               .slideUp()
      }
      
      // show/hide the clicked accordion item
      $this.closest('.js-accordion-item').toggleClass('active');
      $this.next().stop().slideToggle(settings.speed);
    }
  }
})();

$(document).ready(function(){
  accordion.init({ speed: 800, oneOpen: true });
});
</script>
@endsection
@endsection