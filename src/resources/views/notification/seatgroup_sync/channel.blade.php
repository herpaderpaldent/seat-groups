@inject('SeatGroupSyncController', 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications\SeatGroupSyncController')

@if($SeatGroupSyncController->isAvailable())

  @if( $SeatGroupSyncController->isDisabledButton('channel', 'discord') )
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @elseif(! $SeatGroupSyncController->isSubscribed('channel', 'discord'))
    <a href="" type="button" class="btn btn-app" data-toggle="modal"
       data-target="#discord-channel-seatGroupSync-modal">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
    @include('seatgroups::notification.seatgroup_sync.partials.discord-channel-modal')
  @else
    <a href=" {{ route('seatnotifications.seatgroup_sync.unsubscribe.channel', ['via' => 'discord']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-bullhorn"></i>Discord
    </a>
  @endif

  @if( $SeatGroupSyncController->isDisabledButton('channel', 'slack'))
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-slack"></i>Slack
    </a>
  @elseif(! $SeatGroupSyncController->isSubscribed('channel', 'slack'))
    <a href="" type="button" class="btn btn-app" data-toggle="modal"
       data-target="#slack-channel-seatGroupSync-modal">
      <i class="fa fa-slack"></i>Slack
    </a>
    @include('seatgroups::notification.seatgroup_sync.partials.slack-channel-modal')
  @else
    <a href=" {{ route('seatnotifications.seatgroup_sync.unsubscribe.channel', ['via' => 'slack']) }}" type="button"
       class="btn btn-app">
      <span class="badge bg-green"><i class="fa fa-check"></i></span>
      <i class="fa fa-slack"></i>Slack
    </a>
  @endif

@else

  @include('seatnotifications::seatnotifications.partials.missing-permissions')

@endif

