@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.show') }} Teachers Details
    </div>
    
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Name
                    </th>
                    <td>
                        {{ $name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Email
                    </th>
                    <td>
                        {{$email}}
                    </td>
                </tr>
                <tr>
                    <th>
                        DOB
                    </th>
                    <td>
                        {{ $dob }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Phone
                    </th>
                    <td>
                        {{ $phone_no }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Address
                    </th>
                    <td>
                        {{ $address }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Gender
                    </th>
                    <td>
                        {{ $gender }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Joining Date
                    </th>
                    <td>
                        {{ $joining_date }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Designation
                    </th>
                    <td>
                        {{ $designation }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Qualification
                    </th>
                    <td>
                        {{ $qualification }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Total Students
                    </th>
                    <td>
                        {{ count($getAllStudents) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="barChartCourseComparison"></div>
        <div class="col-sm-12">
            <div id="resultGraph"></div>
            <hr>
            <hr>
            <hr>
            <div id="columnChartDiv"></div>
        </div>
    </div>
</div>
<?php if(count($examsTaken) > 0) { ?>
@section('scripts')
    <script>
    
        const correctPercentage = ({{$correct_ans}} / {{$total_ans}}) * 100;
        const wrongPercentage = ({{$wrong_ans}} / {{$total_ans}}) * 100;
        
        const setColors = ['#50B432', '#f34807'];

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
                text: 'Correct Answers Percentage / Wrong Answers Percentage'
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
    


        // ===============Column Comparison Chart==================
        Highcharts.setOptions({
            lang: {
            thousandsSep: ' '
        },
        colors: setColors
        })
        Highcharts.chart('columnChartDiv', {
            chart: {
                type: 'column',
                zoomType: 'y',
            },
            title: {
                text: 'Correct Answer VS Incorrect Answer / Course'
            },
            xAxis: {
                categories: <?php echo json_encode($courseNames) ?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count'
                }
            },
            tooltip: {
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: <?php echo $mapCorrIncorrWithCourse; ?>
        });


        Highcharts.chart('barChartCourseComparison', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Teacher Performance as per students course averages'
            },
            xAxis: {
                categories: <?php echo json_encode($graphXAxisValues) ?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Score'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Score',
                data: <?php echo json_encode($graphYAxisValues) ?>

            }]
        });

    </script>
@endsection
<?php } ?>
@endsection