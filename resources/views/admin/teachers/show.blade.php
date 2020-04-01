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
            </tbody>
        </table>
    </div>
</div>

@endsection