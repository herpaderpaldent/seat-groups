<div class="col-md-4">
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">{{$seatgroup->name}}</h3>

    @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.edit-button')
    <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      @include('seatgroups::partials.join-button')
      {{$seatgroup->description}}
    </div>
    <!-- /.box-body -->
  </div>
</div>


