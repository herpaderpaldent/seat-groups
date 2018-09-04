@if($logCount == 0)
  <a href="#" type="button" class="btn btn-danger btn-sm pull-right disabled" role="button">
    Clear</a>
@else
  <a href="{{route('logs.truncate')}}" type="button"
     class="btn btn-danger btn-sm pull-right" role="button">Clear</a>
@endif