@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header tbl-header">
        Exams {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                            Test Id
                        </th>
                        <th>
                            Course Name
                        </th>
                        <th>
                            Exam Status
                        </th>
                        <th>
                            Date
                        </th>
                        <th style="text-align:center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testHistory as $key => $test)
                        <tr data-entry-id="{{ $test->id }}">
                            <td>
                                #{{ $test->id ?? '' }}
                            </td>
                            <td>
                                {{ ucfirst($availableCourses[$test->courseId]) ?? '' }}
                            </td>
                            <td>
                                <span class="btn bg-success btn-xs">{{ ucfirst($test->test_status) ?? '' }}</span>
                            </td>
                            <td>
                                {{ date("F d Y h:i:s",strtotime($test->created_at)) ?? '' }}
                            </td>
                            <td style="text-align:center">
                                    <a class="btn btn-xs btn-default" href="{{ route('admin.exams.examresult', $test->id) }}">
                                        {{ trans('global.view') }} Test Report
                                    </a>
                            </td>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection