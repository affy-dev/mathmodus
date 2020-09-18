@extends('layouts.admin')
@section('content')
@can('student_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-default" href="{{ route("admin.students.create") }}">
                {{ trans('global.add') }} Student Details
            </a>
        </div>
    </div>
@endcan
@if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.student.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            User Id
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email Id
                        </th>
                        <th>
                            DOB
                        </th>
                        <th>
                            Father Name
                        </th>
                        <th>
                            Father Phone
                        </th>
                        <th>
                            School
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $key => $student)
                        <tr data-entry-id="{{ $student->studentId }}">
                            <td>

                            </td>
                            <td>
                                {{ $student->userId ?? '' }}
                            </td>
                            <td>
                                {{ $student->userName ?? '' }}
                            </td>
                            <td>
                                {{ $student->emailId ?? '' }}
                            </td>
                            <td>
                                {{ $student->studentDOB ?? '' }}
                            </td>
                            <td>
                                {{ $student->fatherName ?? '' }}
                            </td>
                            <td>
                                {{ $student->fatherPhone ?? '' }}
                            </td>
                            <td>
                                {{ $student->school_name ?? '' }}
                            </td>
                            <td>
                                @can('student_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.students.show', $student->userId) }}">
                                        {{ trans('global.view') }} Details
                                    </a>
                                @endcan
                                @can('student_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.students.edit', $student->userId) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                    
                                @can('student_delete')
                                    <form action="{{ route('admin.students.destroy', $student->studentId) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.students.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }
      console.log(ids);
      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('student_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
@endsection