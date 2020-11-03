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
                <th>Lesson Name</th>
                <th><input id="tags"></th>
            </tr>
        </thead>
        <tbody id="myTable">
            @foreach($allLessons as $lessonId => $lessonName)
            <tr>
                <td><a href="{{url()->current()}}/{{auth()->user()->id}}/correct/{{$lessonId}}">{{$lessonName}}</a></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3">
                    @if(count($allLessons) == 0)
                    <div style="text-align:center;color:#ff0000;font-weight: bold;">No Data Found</div>
                    @endif
                </td>
            </tr>

        </tbody>
    </table>


</div>
@section('scripts')
<script>
$(document).ready(function(){
  $("#tags").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endsection
@endsection