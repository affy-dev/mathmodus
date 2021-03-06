<!DOCTYPE html>
<html>

<!-- Header Start -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Mathmodus" />
    <meta name="keywords" content="">
    <meta name="MobileOptimized" content="320">
    <!-- favicon links -->
    <link rel="shortcut icon" type="image/ico" href="{{asset('frontend/mathmodus.ico')}}" />
    <link rel="icon" type="image/ico" href="{{asset('frontend/mathmodus.ico')}}" />
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}" media="screen" />
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" media="screen" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AfoxScMJDXiiDwoZYjgdd_kEna-R0UnsvL9hxZBthCVM_G4Vnw9Vj91MZ2Q5YTAgeFqY9llbCnRXUlqY&currency=USD"></script>
    <!-- <script src="https://www.paypal.com/sdk/js?client-id=AX2B0e_u7YO4waoNp46CXTOdbDFFd6YmSlHmPfB7k2IbFqqcRIfjeP-yg2Wewbfd6UWxMljtqpxbZc0e"></script> -->
    <title>{{ trans('global.site_title') }}</title>
    @yield('styles') 
</head>
<!-- Header End -->

<!-- Body Start -->
<body>
    @yield('slider')
    
    @include('frontend.partials.menu')

    <div class="container">
        @yield('content')
    </div>

    @include('frontend.partials.footer')
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5f60fad3f0e7167d0010938c/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
    <!--main js file start-->
    <script type="text/javascript" src="{{ asset('frontend/js/jquery-1.11.1.js') }}"></script>
    <!--plugin-->
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/appear/jquery.appear.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/count/jquery.countTo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/mediaelement/mediaelement-and-player.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/mixitup/jquery.mixitup.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/modernizr/modernizr.custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/owl-carousel/owl.carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/parallax/jquery.stellar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/prettyphoto/js/jquery.prettyPhoto.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/revslider/js/jquery.themepunch.plugins.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/revslider/js/jquery.themepunch.revolution.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/single/single.js')}}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/plugin/wow/wow.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/grid-gallery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/custom.js') }}"></script>
    <!--main js file end-->
    @include('sweetalert::alert')
    @yield('scripts')  
</body>
<!-- Body End -->

</html>