@extends('layouts.admin')
@section('content')
@can('school_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-default" href="{{ route("admin.schools.create") }}">
                {{ trans('global.add') }} {{ trans('global.school.title_singular') }}
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
        {{ trans('global.school.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            School Id
                        </th>
                        <th>
                            {{ trans('global.school.fields.school_name') }}
                        </th>
                        <th>
                            {{ trans('global.school.fields.school_phone') }}
                        </th>
                        <th>
                            Assigned Teacher
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $key => $school)
                        <tr data-entry-id="{{ $school->schoolId }}">
                            <td>

                            </td>
                            <td>
                                {{ $school->schoolId ?? '' }}
                            </td>
                            <td>
                                {{ $school->school_name ?? '' }}
                            </td>
                            <td>
                                {{ $school->school_phone ?? '' }}
                            </td>
                            <td>
                                {{ $school->userName ?? 'Not Assigned' }}
                            </td>
                            <td>
                                @can('school_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.schools.show', $school->schoolId) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('school_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.schools.edit', $school->schoolId) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('school_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.schools.assignTeachers', $school->schoolId) }}">
                                        Assign Principal
                                    </a>
                                @endcan
                                @can('school_delete')
                                    <form action="{{ route('admin.schools.destroy', $school->schoolId) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    url: "{{ route('admin.schools.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

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
@can('school_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
@endsection