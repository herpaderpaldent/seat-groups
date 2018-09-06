@switch($row->pivot->on_waitlist)
  @case('1')
  <div class="pull-right">
    <div style="width:50%; float:left;">
      <form action="{{route('seatgroups.accept.member', $row->pivot->seatgroup_id)}}" method="POST">
        {{csrf_field()}}
        <input type="hidden" name="group_id" value="{{$row->id}}" />
        <input type="hidden" name="action" value="accept"/>
        <button type="submit" class="btn btn-s btn-success" style="display: inline-block">{{trans('seatgroups::seat.seat_accept_managedgroup')}}</button>
      </form>
    </div>
    <div style="width:50%; float:right;">
      <form action="{{route('seatgroups.accept.member', $row->pivot->seatgroup_id)}}" method="POST">
        {{csrf_field()}}
        <input type="hidden" name="group_id" value="{{$row->id}}" />
        <input type="hidden" name="action" value="deny"/>
        <button type="submit" class="btn btn-s btn-danger" style="display: inline-block">{{trans('seatgroups::seat.seat_deny_managedgroup')}}</button>
      </form>
    </div>
  </div>

  @break
  @case('0')
  <form action="{{route('seatgroups.accept.member', $row->pivot->seatgroup_id)}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="group_id" value="{{$row->id}}" />
    <input type="hidden" name="action" value="deny" />
    <button type="submit" class="btn btn-s btn-danger pull-right"> {{trans('seatgroups::seat.seat_removefrom_managedgroup')}}</button>
  </form>

  @break
@endswitch
