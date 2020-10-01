@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.show') }} Student Details
    </div>
    
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Name
                    </th>
                    <td>
                        {{$name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        User Name
                    </th>
                    <td>
                        {{ $userName }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Email
                    </th>
                    <td>
                        {{$emailId}}
                    </td>
                </tr>
                <!-- <tr>
                    <th>
                        DOB
                    </th>
                    <td>
                        {{ $studentDOB }}
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
                        Father Name
                    </th>
                    <td>
                        {{ $fatherName }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Father Phone
                    </th>
                    <td>
                        {{ $fatherPhone }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Gender
                    </th>
                    <td>
                        {{ $studentGender }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Blood Group
                    </th>
                    <td>
                        {{ $studentBloodGroup }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Mother Name
                    </th>
                    <td>
                        {{ $studentMothenName }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Mother Phone No.
                    </th>
                    <td>
                        {{ $studentMotherPhoneNo }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Present Address
                    </th>
                    <td>
                        {{ $present_address }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Permanent Address
                    </th>
                    <td>
                        {{ $permanent_address }}
                    </td>
                </tr> -->
            </tbody>
        </table>
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
    </script>
@endsection
<?php } ?>
@endsection
