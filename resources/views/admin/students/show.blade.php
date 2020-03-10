@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
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
                <tr>
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
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection