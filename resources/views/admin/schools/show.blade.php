@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header tbl-header">
        {{ trans('global.show') }} {{ trans('global.school.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.school.fields.school_name') }}
                    </th>
                    <td>
                        {{ $school->school_name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.school.fields.school_address') }}
                    </th>
                    <td>
                        {!! $school->school_address !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.school.fields.school_pincode') }}
                    </th>
                    <td>
                        ${{ $school->school_pincode }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.school.fields.school_phone') }}
                    </th>
                    <td>
                        ${{ $school->school_phone }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection