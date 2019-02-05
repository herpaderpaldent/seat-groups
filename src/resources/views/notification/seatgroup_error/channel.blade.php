@inject('SeatGroupErrorController', 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications\SeatGroupErrorController')

@if($SeatGroupErrorController->isAvailable())

  @if( $SeatGroupErrorController->isDisabledButton('channel', 'discord') )
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @elseif(! $SeatGroupErrorController->isSubscribed('channel', 'discord'))
    <a href="" type="button" class="btn btn-app" data-toggle="modal"
       data-target="#discord-channel-seatGroupError-modal">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
    @include('seatgroups::notification.seatgroup_error.partials.discord-channel-modal')
  @else
    <a href=" {{ route('seatnotifications.seatgroup_error.unsubscribe.channel', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @endif

  @if( $SeatGroupErrorController->isDisabledButton('channel', 'slack'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-slack"></i>Slack
    </a>
  @elseif(! $SeatGroupErrorController->isSubscribed('channel', 'slack'))
    <a href="" type="button" class="btn btn-app" data-toggle="modal"
       data-target="#slack-channel-seatGroupError-modal">
      <i class="fa fa-slack"></i>Slack
    </a>
    @include('seatgroups::notification.seatgroup_error.partials.slack-channel-modal')
  @else
    <a href=" {{ route('seatnotifications.seatgroup_error.unsubscribe.channel', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-slack"></i>Slack
    </a>
  @endif

@else

  @include('seatnotifications::seatnotifications.partials.missing-permissions')

@endif

