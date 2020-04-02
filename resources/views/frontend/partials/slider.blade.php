@extends('frontend.layouts.app')
@section('slider')
<!--slider start-->
<div class="lms_slider">
    <div class="lms_revslider">
        <ul>
            <!-- SLIDE 1 -->
            <li data-transition="random-premium" data-slotamount="5" data-masterspeed="700">
                <!-- MAIN IMAGE -->
                <img src="{{ asset('frontend/images/slider/slider1.jpg')}}" alt="slidebg1" data-bgfit="cover"
                    data-bgposition="center center" data-bgrepeat="no-repeat">
                <div class="lms_slider_overlay"></div>

                <!-- LAYERS -->
                <div class="tp-caption mediumlarge_light_white_center customin customout start" data-x="5"
                    data-hoffset="0" data-y="150"
                    data-customin="x:0;y:0;z:0;rotationX:90;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:200;transformOrigin:50% 0%;"
                    data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                    data-speed="1000" data-start="500" data-easing="Back.easeInOut" data-endspeed="300">Online
                    Certified Courses from the</div>


            </li>
        </ul>
        <!-- <div class="tp-bannertimer"></div> -->
    </div>
</div>
@endsection
<!--slider end-->