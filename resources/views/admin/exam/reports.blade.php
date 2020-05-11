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

    @section('scripts')
    <script>
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
                    const contentArr = content ? content.split(',') : false;
                    let finalContent = '';
                    if (contentArr) {
                        contentArr.forEach((item, index) => {
                            finalContent += '<p>' + item + '</p>';
                        });
                    } else {
                        finalContent = 'Not available'
                    }

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
                    // a poor man's stop video
                    $("#video").attr('src', $videoSrc);
                })


                // ==============HightChart Implementation===================

                const correctPercentage = ({
                        {
                            count($correctQuestionDetails)
                        }
                    }
                    / {{count($correctQuestionDetails) + count($wrongQuestionDetails)}}) * 100;
                    const wrongPercentage = ({
                            {
                                count($wrongQuestionDetails)
                            }
                        }
                        / {{count($correctQuestionDetails) + count($wrongQuestionDetails)}}) * 100;

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
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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