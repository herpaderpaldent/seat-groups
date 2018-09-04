@switch($row->event)
  @case('success')
    <span class="label label-success"> {{$row->event}} </span>
    @break
  @case('warning')
    <span class="label label-warning"> {{$row->event}} </span>
    @break
  @case('error')
    <span class="label label-danger"> {{$row->event}} </span>
@endswitch