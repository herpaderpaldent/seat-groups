@inject('MissingRefreshTokenController', 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications\MissingRefreshTokenController')

@if($MissingRefreshTokenController->isAvailable())

  @if( $MissingRefreshTokenController->isDisabledButton('channel', 'discord') )
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @elseif(! $MissingRefreshTokenController->isSubscribed('channel', 'discord'))
    <a href="" type="button" class="btn btn-app" data-toggle="modal"
       data-target="#discord-channel-seatGroupMissingRefreshToken-modal">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
    @include('seatgroups::notification.missing_refreshtoken.partials.discord-channel-modal')
  @else
    <a href=" {{ route('seatnotifications.missing_refreshtoken.unsubscribe.channel', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @endif

  @if( $MissingRefreshTokenController->isDisabledButton('channel', 'slack'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-slack"></i>Slack
    </a>
  @elseif(! $MissingRefreshTokenController->isSubscribed('channel', 'slack'))
    <a href="" type="button" class="btn btn-app" data-toggle="modal"
       data-target="#slack-channel-seatGroupMissingRefreshToken-modal">
      <i class="fa fa-slack"></i>Slack
    </a>
    @include('seatgroups::notification.missing_refreshtoken.partials.slack-channel-modal')
  @else
    <a href=" {{ route('seatnotifications.missing_refreshtoken.unsubscribe.channel', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-slack"></i>Slack
    </a>
  @endif

@else

  @include('seatnotifications::seatnotifications.partials.missing-permissions')

@endif

