@extends('layouts.admin')
@section('content')
    <div class="privew">
            <div class="col-sm" style="text-align:right">
                <a class="btn btn-primary" href="{{ route('admin.exams.lessonVideos', ['courseId' => $courseId, 'testId' => $testId]) }}">
                    Skip
                </a>
            </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <strong><h4>{{$errors->first()}}</h4></strong>
            </div>
        @endif
        <form action="{{ route("admin.exams.submitExam") }}" method="POST" id="testForm" >
            @csrf
            @foreach($mcqs as $ques)
                @foreach($ques as $quesDetails)
                <?php 
                    $questText = $quesDetails['question_text'];
                    $answerText = $quesDetails['answerText'];
                    $answerId = $quesDetails['answerId'];
                    $optAns = explode(',',$answerText);
                ?>
                    <div class="questionsBox">
                        <div class="questions">{{$questText}}</div>
                        <ul class="answerList">
                            @foreach($optAns as $ans)
                                <li>
                                    <label><input type="radio" name="answerGroup_{{$answerId}}" value="{{$ans}}" >{{$ans}}</label>
                                </li>
                            @endforeach
                            <input type="hidden" name="answerId[]" value="{{$answerId}}" />
                            <input type="hidden" name="courseId" value="{{$courseId}}" />
                            <input type="hidden" name="testId" value="{{$testId}}" />
                        </ul>
                    </div>
                @endforeach
            @endforeach
            <div class="col-sm" style="margin-top: 2%;">
                <button class="btn btn-default btn-block" id="sbmtBtn" type="button" onClick="submitForm()" style="padding: 10px;font-size: 30px;">Submit Test</button>
            </div>
        </form>
    </div>
@section('scripts')
<script>
function submitForm() {
    $('#sbmtBtn').prop('disabled', true);
    $('#sbmtBtn').html('<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>');
    let check = true;
    $("input:radio").each(function(){
        const name = $(this).attr("name");
        if($("input:radio[name="+name+"]:checked").length == 0){
            check = false;
        }
    });
    if(check){
        $('form#testForm').submit();
    }else{
        $('#sbmtBtn').prop('disabled', false);
        $('#sbmtBtn').html('Submit Test');
        alert('Please select one option in each question.');
    }
}
</script>
@endsection
@endsection