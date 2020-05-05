<div class="sidebar">
    <nav class="sidebar-nav ps ps--active-y">

        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route("admin.home") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle">
                    <i class="fas fa-users nav-icon">

                    </i>
                    {{ trans('global.userManagement.title') }}
                </a>
                <ul class="nav-dropdown-items">
                    @can('permission_create')
                    <li class="nav-item">
                        <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                            <i class="fas fa-unlock-alt nav-icon">

                            </i>
                            {{ trans('global.permission.title') }}
                        </a>
                    </li>
                    @endcan
                    @can('role_create')
                    <li class="nav-item">
                        <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase nav-icon">

                            </i>
                            {{ trans('global.role.title') }}
                        </a>
                    </li>
                    @endcan
                    @can('user_create')
                    <li class="nav-item">
                        <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon">

                            </i>
                            {{ trans('global.user.title') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @can('school_access')
            <li class="nav-item">
                <a href="{{ route("admin.schools.index") }}" class="nav-link {{ request()->is('admin/schools') || request()->is('admin/schools/*') ? 'active' : '' }}">
                    <i class="fas fa-school nav-icon">
                    
                    </i>
                    {{ trans('global.school.title') }}
                </a>
            </li>
            @endcan
            @can('student_access')
            <li class="nav-item">
                <a href="{{ route("admin.students.index") }}" class="nav-link {{ request()->is('admin/students') || request()->is('admin/students/*') ? 'active' : '' }}">
                    <i class="fa fa-graduation-cap nav-icon">

                    </i>
                    {{ trans('global.student.title') }}
                </a>
            </li>
            @endcan
            @can('teachers_access')
            <li class="nav-item">
                <a href="{{ route("admin.teachers.index") }}" class="nav-link {{ request()->is('admin/teachers') || request()->is('admin/teachers/*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher nav-icon">

                    </i>
                    Teachers
                </a>
            </li>
            @endcan
            @can('exams_list')
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle">
                    <i class="fa fa-sitemap nav-icon">

                    </i>
                    Subjects
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a href="{{ route("admin.exams.index") }}" class="nav-link {{ request()->is('admin/exams') ? 'active' : '' }}">
                            <i class="fa fa-sitemap nav-icon">

                            </i>
                            Choose Subject
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route("admin.exams.history") }}" class="nav-link {{ request()->is('admin/exams/history') || request()->is('admin/exams/history') ? 'active' : '' }}">
                            <i class="fa fa-bar-chart nav-icon">

                            </i>
                            History
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
            
        </ul>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 869px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 415px;"></div>
        </div>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>