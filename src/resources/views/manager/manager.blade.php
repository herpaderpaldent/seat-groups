<h3 class="box-title">{{trans('seatgroups::seat.seat_groups_managedgroup')}}</h3>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">{{trans('seatgroups::seat.seat_groups_manager')}}</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="post" action="{{route('seatgroupuser.addmanager')}}">
      {{csrf_field()}}
      <input name="_method3" type="hidden" value="PATCH">
      <input name="seatgroup_id" type="hidden" value="{{$id}}">
      <div class="form-group">
        <label for="groups">{{ trans_choice('web::seat.available_groups',2) }}</label>
        <select name="groups[]" id="available_users" style="width: 100%" multiple>

          @foreach($all_groups as $group)
            @if(!in_array($group->id,$seatgroup->manager->pluck('id')->toArray())))
            <option value="{{ $group->id }}">
              {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
            </option>
            @endif
          @endforeach

        </select>
      </div>
      <div class="form-group">
        <label for="groups">{{ trans_choice('web::seat.available_seatgroups',2) }}</label>
        <select name="seatgroups[]" id="available_seatgroups" style="width: 100%" multiple>

          @foreach($all_seatgroups as $group)
            {{--@if(!in_array($group->id,$seatgroup->children->pluck('id')->toArray())))--}}
            <option value="{{ $group->id }}">
              {{ $group->name }}
            </option>
            {{--@endif--}}
          @endforeach



        </select>
      </div>

      <div class="row">
        <div class="col-md-6"></div>
        <div class="form-group col-md-12">
          <button type="submit" class="btn btn-success btn-block">Add Manager</button>
        </div>
      </div>
    </form>

    Table of current Managers below
  </div>
  <!-- /.box-body -->
</div>

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_users," +
        "#available_seatgroups").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}"
    });
  </script>

@endpush