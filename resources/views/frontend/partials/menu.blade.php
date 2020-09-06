<!--Header start-->
<header id="lms_header" style="background:#000">
    <div class="container">
        <h1 class=""> <a href="/"> <img alt="Porto" data-sticky-width="82" style="height: 100px;width: 160px;" data-sticky-height="40" src="{{ asset('frontend/images/new-logo.png') }}"> </a> </h1>
        <button class="lms_menu_toggle btn-responsive-nav btn-inverse" data-toggle="collapse"
            data-target=".nav-main-collapse"><i class="fa fa-bars"></i> </button>
    </div>
    <div class="navbar-collapse nav-main-collapse collapse">
        <div class="container">
            <nav class="nav-main mega-menu">
                <ul class="nav nav-pills nav-main" id="mainMenu">
                    <li class="{{ request()->is('/') ? 'active' : '' }}"> <a class="dropdown-toggle" href="/"> Home</a>
                    <li class="{{ request()->is('how-it-works') ? 'active' : '' }}"><a href="/how-it-works">How It Works</a></li>
                    <li class="{{ request()->is('contact-us') ? 'active' : '' }}"><a href="/contact-us">Contact Us</a></li>
                    @guest
                        <li class="{{ request()->is('login') ? 'active' : '' }}"><a href="/login">Login <i class="fa fa-sign-in" aria-hidden="true"></i></a></li>
                        <li class="{{ request()->is('register') ? 'active' : '' }}"><a href="/register">Register </a></li>
                    @endguest
                    <!-- @auth
                        <li class=""><a href="/admin/exams">Go to Dashboard <i class="fa fa-sign-in" aria-hidden="true"></i></a></li>
                    @endauth -->
                </ul>
            </nav>
            <div class="lms_search_toggle"><a><i class="fa fa-search"></i></a></div>
            <div class="lms_search_wrapper"><input type="search" placeholder="Search..." /></div>
        </div>
    </div>
</header>
<!--Header end-->