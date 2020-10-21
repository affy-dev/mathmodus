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
                            Lesson Name
                        </th>
                        <th>
                            Test Type
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
                                {{ !empty($test->lessonId) ? ucfirst($availableLessons[$test->lessonId]) : ucfirst($availableCourses[$test->courseId]) }}
                            </td>
                            <td>
                                {{ !empty($test->lessonId) ? 'Lesson Wise Test' : 'Diagnostic Test' }}
                            </td>
                            <td>
                                <span class="btn <?php echo $test->test_status == 'completed' ? 'bg-success' : 'bg-danger' ?> btn-xs">{{ ucfirst($test->test_status) ?? '' }}</span>
                            </td>
                            <td>
                                {{ date("F d Y h:i:s",strtotime($test->created_at)) ?? '' }}
                            </td>
                            <td style="text-align:center">
                                    <?php if($test->test_status == 'completed') { ?>
                                        <a class="btn btn-xs btn-default" href="{{ route('admin.exams.examresult', $test->id) }}">
                                            {{ trans('global.view') }} Test Report
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-xs btn-success" style="width:106px" href="{{ route('admin.exams.takeexam', [$test->courseId, 0 ,$test->id]) }}">
                                            Resume Test
                                        </a>
                                    <?php } ?>
                                    @if(!empty($canDeleteTest))
                                    <a class="btn btn-xs btn-danger" onclick="return confirm('Are you sure want to delete?')" href="{{ route('admin.exams.deleteTest', $test->id) }}">
                                        Delete Test
                                    </a>
                                    @endif
                            </td>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $testHistory->links() }}
        </div>
    </div>
</div>
@endsection