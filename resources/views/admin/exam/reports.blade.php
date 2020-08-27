@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row headingBox" style="margin-top: 10px;">
        <div class="col-sm-12">
            <h3>Lesson Wise Report</h3>
        </div>
    </div>
    <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Lesson Name</th>
      <th scope="col">Times you got correct</th>
      <th scope="col">Times you got wrong</th>
    </tr>
  </thead>
  <tbody>
    @foreach($allLessonTestGiven as $testData)
    <tr>
      <td>{{$allLessons[$testData]}}</td>
      <td style="color:#00d600;font-weight: bold;">{{isset($correctAnsData[$testData]) ? $correctAnsData[$testData] : '0'}}</td>
      <td style="color:#ff0000;font-weight: bold;">{{isset($inCorrectAnsData[$testData]) ? $inCorrectAnsData[$testData] : '0'}}</td>
    </tr>
    @endforeach
  </tbody>
</table>


</div>
@endsection