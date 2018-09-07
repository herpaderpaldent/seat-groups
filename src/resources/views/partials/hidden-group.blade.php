<div class="col-md-12">
@foreach ($seatgroups->where('type', 'hidden') as $seatgroup)
  @if($seatgroup->isMember(auth()->user()->group) || auth()->user()->hasRole('seatgroups.create')  )
    <div class="panel panel-warning">
      <div class="panel-heading clearfix">
        @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.edit-button')
        <h3 class="panel-title"><span data-toggle="tooltip" title="" class="badge bg-blue-active" data-original-title="Only Members can see this SeAT Group"><i class="fa fa-info-circle"></i></span> {{$seatgroup->name}} </h3>
      </div>
      <div class="panel-body">
        {{$seatgroup->description}} <br>
        Members: {{$seatgroup->member->map(function($group) { return $group->main_character->name;})->implode(', ')}}
      </div>
    </div>
  @endif

@endforeach
</div>