<div class="col-md-4">
    <div class="box box-danger">
      <div class="box-header with-border">
        @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.edit-button')
        <h3 class="box-title"> {{$seatgroup->name}} </h3>
      </div>
      <div class="box-body">
        {{$seatgroup->description}} <br>
        Members: {{str_limit($seatgroup->member->map(function($group) { return $group->main_character->name;})->implode(', '),42)}}
      </div>
    </div>

</div>
