@inject('SeatGroupApplicationController', 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications\SeatGroupApplicationController')

@if($SeatGroupApplicationController->isAvailable())

  @if( $SeatGroupApplicationController->isDisabledButton('private', 'discord'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @elseif(! $SeatGroupApplicationController->isSubscribed('private', 'discord'))
    <a href=" {{ route('seatnotifications.seatgroup_application.subscribe.user', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @else
    <a href=" {{ route('seatnotifications.seatgroup_application.unsubscribe.user', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @endif

  @if( $SeatGroupApplicationController->isDisabledButton('private','slack'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-slack"></i>Slack
    </a>
  @elseif(! $SeatGroupApplicationController->isSubscribed('private', 'slack'))
    <a href=" {{ route('seatnotifications.seatgroup_application.subscribe.user', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <i class="fa fa-slack"></i>Slack
    </a>
  @else
    <a href=" {{ route('seatnotifications.seatgroup_application.unsubscribe.user', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-slack"></i>Slack
    </a>
  @endif

@else

  @include('seatnotifications::seatnotifications.partials.missing-permissions')

@endif
