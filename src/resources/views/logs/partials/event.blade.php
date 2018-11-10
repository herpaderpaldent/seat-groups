@switch($row->event)
  @case('attached')
    <span class="label label-success"> {{$row->event}} </span>
    @break
  @case('detached')
    <span class="label label-warning"> {{$row->event}} </span>
    @break
  @case('error')
    <span class="label label-danger"> {{$row->event}} </span>
  @default
    <span class="label label-default"> {{$row->event}} </span>
@endswitch