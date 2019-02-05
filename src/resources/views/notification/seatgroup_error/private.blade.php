@inject('SeatGroupErrorController', 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications\SeatGroupErrorController')

@if($SeatGroupErrorController->isAvailable())

  @if( $SeatGroupErrorController->isDisabledButton('private', 'discord'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @elseif(! $SeatGroupErrorController->isSubscribed('private', 'discord'))
    <a href=" {{ route('seatnotifications.seatgroup_error.subscribe.user', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @else
    <a href=" {{ route('seatnotifications.seatgroup_error.unsubscribe.user', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @endif

  @if( $SeatGroupErrorController->isDisabledButton('private','slack'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-slack"></i>Slack
    </a>
  @elseif(! $SeatGroupErrorController->isSubscribed('private', 'slack'))
    <a href=" {{ route('seatnotifications.seatgroup_error.subscribe.user', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <i class="fa fa-slack"></i>Slack
    </a>
  @else
    <a href=" {{ route('seatnotifications.seatgroup_error.unsubscribe.user', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-slack"></i>Slack
    </a>
  @endif

@else

  @include('seatnotifications::seatnotifications.partials.missing-permissions')

@endif
