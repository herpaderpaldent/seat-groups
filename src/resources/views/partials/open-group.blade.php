<div class="row">

  <div class="col-md-12">
    <h3>{{ trans('seatgroups::seat.seat_groups_opengroup')}}</h3>
    <p>{{ trans('seatgroups::seat.seat_groups_opengroup_description')}}</p>
  </div>

</div>

<div class="row">
  @foreach($seatgroups->where('type', 'open') as $seatgroup)
    <div class="col-md-12">


      @if(! $seatgroup->isAllowedToSeeSeatGroup())
        @continue
      @endif

      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.edit-button')
          <h3 class="panel-title">{{$seatgroup->name}}</h3>
        </div>
        <div class="panel-body">
          @include('seatgroups::partials.join-button')
          {{$seatgroup->description}}


        </div>
      </div>

    </div>
  @endforeach
</div>
