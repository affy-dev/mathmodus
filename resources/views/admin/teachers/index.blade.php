@extends('layouts.admin')
@section('content')
@can('teachers_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-default" href="{{ route("admin.teachers.create") }}">
                {{ trans('global.add') }} Teachers Details
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
        Teachers List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Teacher Id
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email Id
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Phone No.
                        </th>
                        <th>
                            School Assigned.
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $key => $teacher)
                        <tr data-entry-id="{{ $teacher->teacherId }}">
                            <td>

                            </td>
                            <td>
                                {{ $teacher->userId ?? '' }}
                            </td>
                            <td>
                                {{ $teacher->name ?? '' }}
                            </td>
                            <td>
                                {{ $teacher->email ?? '' }}
                            </td>
                            <td>
                                {{ $teacher->designation ?? '' }}
                            </td>
                            <td>
                                {{ $teacher->phone_no ?? '' }}
                            </td>
                            <td>
                                {{ $teacher->school_name ?? '' }}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-default" href="{{ route('admin.teachers.assign-school', $teacher->userId) }}">
                                    Assign School
                                </a>
                                @can('teachers_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.teachers.show', $teacher->userId) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('teachers_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.teachers.edit', $teacher->userId) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('teachers_delete')
                                    <form action="{{ route('admin.teachers.destroy', $teacher->teacherId) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    url: "{{ route('admin.teachers.massDestroy') }}",
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
@can('teachers_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
@endsection