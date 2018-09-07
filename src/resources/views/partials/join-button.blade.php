@switch($seatgroup->type)
  @case('open')
  @switch($seatgroup->isMember(auth()->user()->group))
    @case(false)
    <form action="{{route('seatgroups.user.join', $seatgroup->id)}}" method="POST">
      {{csrf_field()}}
      <input name="_method3" type="hidden" value="PATCH">
      <button type="submit" class="btn btn-sm btn-success pull-right" style="display: inline">{{trans('seatgroups::seat.seat_join_opengroup')}}</button>
    </form>
    @break
    @case(true)
    <form action="{{route('seatgroups.user.leave', $seatgroup->id)}}" method="DELETE">
      {{csrf_field()}}
      <button type="submit" class="btn btn-sm btn-danger pull-right" style="display: inline">{{trans('seatgroups::seat.seat_leave_opengroup')}}</button>
    </form>
    @break
  @endswitch
  @case('managed')
  @if($seatgroup->onWaitlist())
    <form action="{{route('seatgroups.user.leave', $seatgroup->id)}}" method="DELETE">
      {{csrf_field()}}
      <button type="submit" class="btn btn-sm btn-info pull-right" style="display: inline">{{trans('seatgroups::seat.seat_leave_waitlist')}}</button>
    </form>
    @break
  @endif
  @switch($seatgroup->isMember(auth()->user()->group))
    @case(false)
    <form action="{{route('seatgroups.user.join', $seatgroup->id)}}" method="POST">
      {{csrf_field()}}
      <input name="_method3" type="hidden" value="PATCH">
      <button type="submit" class="btn btn-sm btn-success pull-right" style="display: inline">{{trans('seatgroups::seat.seat_join_waitlist')}}</button>
    </form>
    @break
    @case(true)
    <form action="{{route('seatgroups.user.leave', $seatgroup->id)}}" method="DELETE">
      {{csrf_field()}}
      <button type="submit" class="btn btn-sm btn-danger pull-right" style="display: inline">{{trans('seatgroups::seat.seat_leave_opengroup')}}</button>
    </form>
    @break
  @endswitch
@endswitch



