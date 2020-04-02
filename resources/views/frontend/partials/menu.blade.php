<!--Header start-->
<header id="lms_header" style="background:#000">
    <div class="container">
        <h1 class="logo"> <a href="index.html"> <img alt="Porto" width="184" height="38" data-sticky-width="82"
                    data-sticky-height="40" src="{{ asset('frontend/images/logo.png') }}"> </a> </h1>
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
                    <li><a href="/login">Login <i class="fa fa-sign-in" aria-hidden="true"></i></a></li>
                </ul>
            </nav>
            <div class="lms_search_toggle"><a><i class="fa fa-search"></i></a></div>
            <div class="lms_search_wrapper"><input type="search" placeholder="Search..." /></div>
        </div>
    </div>
</header>
<!--Header end-->