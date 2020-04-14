@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="row mb-3">
                @can('user_management_access')
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-user fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Users</h6>
                            <h1 class="display-4">{{$countUser}}</h1>
                        </div>
                    </div>
                </div>
                @endcan
                @can('teachers_access')
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fas fa-chalkboard-teacher fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Teachers</h6>
                            <h1 class="display-4">{{$countTeacher}}</h1>
                        </div>
                    </div>
                </div>
                @endcan
                @can('student_access')
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-graduation-cap fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Students</h6>
                            <h1 class="display-4">{{$countStudent}}</h1>
                        </div>
                    </div>
                </div>
                @endcan
                @can('school_access')
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fas fa-school fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">School</h6>
                            <h1 class="display-4">{{$schoolCount}}</h1>
                        </div>
                    </div>
                </div>
                @endcan
                @can('exams_list')
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fa fa-sitemap nav-icon fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Exams Taken</h6>
                            <h1 class="display-4">{{$examsTakes}}</h1>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection