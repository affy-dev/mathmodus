@extends('layouts.admin')
@section('content')
<div class="container">
    <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Test Id</th>  
      <th scope="col">Correct Answers</th>
      <th scope="col">InCorrect Answers</th>
      <th scope="col">Test Date</th>
    </tr>
  </thead>
  <tbody>
    @foreach($testDetails as $data)                               
      <tr class='clickable-row' data-href='{{ route('admin.exams.examresult', [$data['id']]) }}'>
        <td><b>#{{$data['id']}}</b></td>
        <td>{{$data['correct_ans']}}</td>
        <td>{{$data['wrong_ans']}}</td>
        <td>{{date('d-m-Y', strtotime($data['created_at']))}}</td>
      </tr>
    @endforeach
    <tr>
        <td colspan="4">
            @if(count($testDetails) == 0)
                <div style="text-align:center;color:#ff0000;font-weight: bold;">Yet to take any test for this lesson</div>
            @endif
        </td>
    </tr>
    
  </tbody>
</table>
</div>
@section('scripts')
<script>
  $(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
  });
</script>
@endsection
@endsection