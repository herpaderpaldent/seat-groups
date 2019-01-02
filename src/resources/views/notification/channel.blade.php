@inject('SeatGroupNotificationController', 'Herpaderpaldent\Seat\SeatGroups\Http\Controllers\SeatGroupNotificationController')

@if( $SeatGroupNotificationController->isDisabledButton('discord') )
    <a href="" type="button" class="btn btn-app disabled">
      <i class="fa fa-bullhorn"></i>Discord
    </a>
@elseif(! $SeatGroupNotificationController->isSubscribed('discord') )
  <a href="" type="button" class="btn btn-app" data-toggle="modal" data-target="#discord-channel-modal">
    <i class="fa fa-bullhorn"></i>Discord
  </a>
@else
  <a href=" {{ route('seatgroups.unsubscribe', ['via' => 'discord']) }}" type="button" class="btn btn-app">
    <span class="badge bg-green"><i class="fa fa-check"></i></span>
    <i class="fa fa-bullhorn"></i>Discord
  </a>
@endif

@if( $SeatGroupNotificationController->isDisabledButton('slack') )
  <a href="" type="button" class="btn btn-app disabled">
    <i class="fa fa-slack"></i>Slack
  </a>
@elseif(! $SeatGroupNotificationController->isSubscribed('slack') )
  <a href="" type="button" class="btn btn-app" data-toggle="modal" data-target="#slack-channel-modal">
    <i class="fa fa-slack"></i>Slack
  </a>
@else
  <a href=" {{ route('seatgroups.unsubscribe', ['via' => 'slack']) }}" type="button" class="btn btn-app">
    <span class="badge bg-green"><i class="fa fa-check"></i></span>
    <i class="fa fa-slack"></i>Slack
  </a>
@endif


<div class="modal fade" id="discord-channel-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Discord channel selection</h4>
      </div>
      <div class="modal-body">

        <form id="subscribeDiscordChannelForm" role="form"
              action="{{ route('seatgroups.subscribe') }}" method="post">
          {{ csrf_field() }}

          <input type="hidden" name="via" value="discord">

          <div class="row">
            <div class="form-group">
              <label for="available_channels">Select delivery channel:</label>
              <select name="channel_id" id="available_channels" class="form-control" style="width: 100%">
                <option></option>
                @foreach($available_channels as $channel)
                  @if(!array_key_exists('discord', $channel))
                    @continue
                  @endif

                  @foreach($channel['discord'] as $channel_id => $channel_name)
                    <option value="{{ $channel_id }}">{{ $channel_name }}</option>
                  @endforeach

                @endforeach
              </select>
            </div>
          </div>
          <span class="help-block">Although you might chose the right channel here you need to make sure that the bot has the appropriate channel permission to post a message.</span>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" form="subscribeDiscordChannelForm" class="btn btn-primary">Save changes</button>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="slack-channel-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Slack channel selection</h4>
      </div>
      <div class="modal-body">

        <form id="subscribeSlackChannelForm" role="form"
              action="{{ route('seatgroups.subscribe') }}" method="post">
          {{ csrf_field() }}

          <input type="hidden" name="via" value="slack">

          <div class="row">
            <div class="form-group">
              <label for="available_channels">Select delivery channel:</label>
              <select name="channel_id" id="available_channels" class="form-control" style="width: 100%">
                <option></option>
                @foreach($available_channels as $channel)
                  @if(!array_key_exists('slack', $channel))
                    @continue
                  @endif

                  @foreach($channel['slack'] as $channel)
                      <option value="{{ $channel['id'] }}">
                        {{ $channel['name'] }}
                        @if($channel['private_channel'])
                          <i>(private channel)</i>
                        @endif
                      </option>
                  @endforeach

                @endforeach
              </select>
            </div>
          </div>
          <span class="help-block">If do not see the wished channel, invite the bot to it and try again later.</span>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" form="subscribeSlackChannelForm" class="btn btn-primary">Save changes</button>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>