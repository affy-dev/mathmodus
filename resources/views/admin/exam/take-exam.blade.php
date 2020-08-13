@extends('layouts.admin')
@section('content')
<div class="privew">

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>
            <h4>{{$errors->first()}}</h4>
        </strong>
    </div>
    @endif
    <div class="row headingBox">
        <div class="col-sm-12">
            <h3>Test Name: [ {{$testName}} ]</h3>
        </div>
        
    </div>
    <div class="col-sm" style="text-align:right">
        <a class="btn btn-default"
            href="{{ route('admin.exams.lessonVideos', ['courseId' => $courseId, 'testId' => $testId]) }}">
            Skip Test
        </a>
    </div>
    <form action="{{ route("admin.exams.submitExam") }}" method="POST" id="testForm">
        @csrf
        @foreach($mcqs as $qDetails)
        @foreach($qDetails as $ques)
        @if(count($ques) > 0)
        <?php 
            $quesDetails = $ques['questionDetails'];
            $ansDetails = $ques['answerDetails'];
            $questNumber = $ques['questNumber'];
            $questText = $quesDetails['question_text'];
        ?>
        <div class="questionsBox">
            <div class="questions">
                <?php        
                    $question = str_replace('src="../', 'src="'.$imgSrcPath.'', $questText);
                    echo '('.$questNumber.') '.$question;
                ?>
            </div>
            <ul class="answerList">
                @foreach($ansDetails as $ans)
                <li>
                    <label><input type="radio" name="answerGroup_{{$quesDetails['id']}}"
                            value="{{$ans->id}}"><span>{{$ans->answer_text}}</span></label>
                </li>
                @endforeach
                <input type="hidden" name="questionIds[]" value="{{$quesDetails['id']}}" />
                <input type="hidden" name="courseId" value="{{$courseId}}" />
                <input type="hidden" name="testId" value="{{$testId}}" />
                <input type="hidden" name="questNumber_{{$quesDetails['id']}}" value="{{$questNumber}}" />
            </ul>
        </div>
        @endif
        @endforeach
        @endforeach
        <div class="col-sm" style="margin-top: 2%;">
            <button class="btn btn-default btn-block" id="sbmtBtn" type="button" onClick="submitForm()"
                style="padding: 10px;font-size: 30px;">Submit Test</button>
        </div>
    </form>
</div>
@section('scripts')
<script>
function submitForm() {
    $('#sbmtBtn').prop('disabled', true);
    $('#sbmtBtn').html('<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>');
    let check = true;
    // $("input:radio").each(function() {
    //     const name = $(this).attr("name");
    //     if ($("input:radio[name=" + name + "]:checked").length == 0) {
    //         check = false;
    //     }
    // });
    if (check) {
        $('form#testForm').submit();
    } else {
        $('#sbmtBtn').prop('disabled', false);
        $('#sbmtBtn').html('Submit Test');
        swal({
            title: "Error!",
            text: "Please select one option in each question!",
            icon: "error",
            buttons: false,
        });
    }
}
</script>
@endsection
@endsection