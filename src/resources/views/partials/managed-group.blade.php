<div class="col-md-12">
@foreach($seatgroups->where('type', 'managed') as $seatgroup)

    @if(!$seatgroup->isAllowedToSeeSeatGroup())
      @continue
    @endif

    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.edit-button')
        <h3 class="panel-title">{{$seatgroup->name}}</h3>
      </div>
      <div class="panel-body">
        @include('seatgroups::partials.join-button')
        {{$seatgroup->description}} <br>
        @if($seatgroup->isMember(auth()->user()->group))
          Members: {{$seatgroup->member->map(function($group) { return $group->main_character->name;})->implode(', ')}}
        @endif


      </div>
      <div class="panel-footer">
        Managers: {{$seatgroup->manager->map(function($user) { return $user->main_character->name; })->implode(', ')}}

        @includeWhen($seatgroup->isManager(auth()->user()->group),'seatgroups::partials.manager-modal')

      </div>

    </div>

@endforeach
</div>
