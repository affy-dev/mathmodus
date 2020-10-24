@extends('frontend.layouts.app')
@section('slider')
    @include('frontend.partials.slider')
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="lms_title_center">
            <div class="lms_heading_1">
                <h2 class="lms_heading_title">Introduction</h2>
            </div>
            <p style="font-size:14px">
                MathModus is an online, easy-to-use blended learning system used in Mathematical subjects from grades 4 onwards. Through its use of computer based automation, MathModus delivers pre-developed instructional lessons, diagnostic assessment tests, and targeted remedial instruction. As a result, teachers will not be required to return home each evening to create lessons, tests, or to grade and diagnose student outcomes. Through MathModus’ use, teachers will always be aware of what is required of them to ensure subject matter mastery by their students. Simultaneously, students will have the capability to assume their own responsibility for subject matter mastery through independent self-paced study targeted to their needs by MathModus. Through automation, teachers can increase their diagnostic testing activities with the assurance that MathModus will not only diagnose a student’s problem, but will deliver a customized course of remedial study to each student based upon their test results. In addition, it also provides teachers with real-time diagnostic data that enables them to hold targeted remedial discussions with students in need of this critical assistance. Upon completion of these face-to-face discussions with their teacher, students can then use MathModus’ repetitive diagnostic assessment and remediation strategies as often as necessary to achieve mastery. Throughout this entire repetitive process, teachers are made aware of each student’s progress and use this real-time data to assist in the remedial process.
                <a href="{{ route('frontend.introduction') }}#gotointroduction" style="color:#FF9933">Read More.....</a>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="lms_title_center">
            <div class="lms_heading_1">
                <h2 class="lms_heading_title">About Us</h2>
            </div>
            <p style="font-size:14px;margin-bottom: 10px;">
            Modus is an LLC corporation established in Virginia owned and operated by retired educators and educational technologists who, collectively, possess more than 100 years of teaching and educational technology experience. Since 1997, we have installed our proprietary on-line educational products in more than 1,700 schools who placed 1.2 million students in its curriculum for credit recovery and graduation purposes. During this period, some of our nation’s most prestigious educational institutions and research organizations expressed their concern about the educational effectiveness of online Learning Management Systems (LMS). Studies conducted by four of these organizations identified six major reasons why they believed these products do not provide the quality educational experience predicted by their developers when used as the sole source of a student’s education. The six reasons are:
                <ul class="list-group">
                    <li class="list-group-item">Reading difficulties of students</li>
                    <li class="list-group-item">Student’s inability to comprehend what they have read</li>
                    <li class="list-group-item">Lack of Mathematics foundation skills</li>
                    <li class="list-group-item">Lack of real-time diagnostic assessment tools</li>
                    <li class="list-group-item">Lack of remedial methods and tools</li>
                    <li class="list-group-item">Inability of students to obtain targeted remedial instruction</li>
                </ul>

            During the past five years, we have spent countless hours in researching the needs of teachers and students to resolve the above issues, and the many others brought to our attention by our clients. Through our use of automation, our proprietary product, MathModus©, will decrease a teacher’s daily workload by as much as 60% during their delivery of instruction, assessment, diagnostics, and remediation. More Importantly, through automation, it substantially increases a student’s ability to achieve mastery in Mathematics through “intelligent “ oversight that can reveal and remediate the “gaps'' in each student’s math education, even for topics taught as long ago as three or four years! To date, more than 100,000 students have successfully used its automated features to achieve success in Mathematics even when all previously used remedial materials had failed.
            Not a single student or teacher has ever found fault with its ability to find and correct their problems in math.
            </p>
        </div>
    </div>
</div>
@endsection