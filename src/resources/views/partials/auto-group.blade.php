<div class="row">
  <div class="col-md-12">
    <h3>{{ trans('seatgroups::seat.seat_groups_autogroup')}}</h3>
    <p>{{ trans('seatgroups::seat.seat_groups_autogroup_description')}}</p>
  </div>

</div>

<div class="row">

  <div class="col-md-12">


    @foreach($seatgroups->where('type', 'auto') as $seatgroup)

      @if(!$seatgroup->isAllowedToSeeSeatGroup())
        false {{auth()->user()->group->id}}
        @continue
      @endif

      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.edit-button')
          <h3 class="panel-title">{{$seatgroup->name}}</h3>
        </div>
        <div class="panel-body">{{$seatgroup->description}}</div>
      </div>
    @endforeach

  </div>


</div>