@extends('web::layouts.grids.12')

@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_edit'))

@section('content')


  <div class="row">
    <div class="col-lg-12">
      @if($seatgroup->corporation->isEmpty() && !$seatgroup->all_corporations && $seatgroup->corporationTitles->isEmpty() && $seatgroup->alliance->isEmpty())
        <div class="alert alert-info alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-info"></i> SeAT-Group is not working</h4>
          SeAT-Group needs an affiliation to set to work correctly. This will prevent members that have been purged from
          alliance, corporation or lost their title, to still keep their roles.
        </div>
      @endif
    </div>

  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <div class="btn-group pull-right">
            @if(auth()->user()->hasSuperUser())
              <form role="form" action="{{ route('seatgroups.destroy', ['seatgroup_id' => $seatgroup->id]) }}"
                    method="post">
                {{ csrf_field() }}
                <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
                <button type="submit" class="btn btn-danger btn-xs">
                  {{ trans('web::seat.remove') }}
                </button>
              </form>
            @endif
          </div>
          <h3 class="panel-title">{{trans('seatgroups::seat.seat_groups_editing')}} {{$seatgroup->name}}</h3>
        </div>

        <div class="panel-body">
          <form method="post" action="{{route('seatgroups.update', $id)}}">
            {{csrf_field()}}
            <input name="_method3" type="hidden" value="PATCH">
            <div class="row">
              <div class="col-md-12"></div>
              <div class="form-group col-md-12">
                <label for="name">{{trans('seatgroups::seat.seat_groups_name')}}</label>
                <input type="text" class="form-control" name="name" value="{{$seatgroup->name}}">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"></div>
              <div class="form-group col-md-12">
                <label for="description">{{trans('seatgroups::seat.seat_groups_description')}}</label>
                <textarea type="text" class="form-control" rows="5"
                          name="description">{{$seatgroup->description}}</textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12"></div>
              <div class="form-group col-md-12">
                <label for="type">{{trans('seatgroups::seat.seat_groups_type')}}</label>
                <select class="form-control" name="type">
                  <option value="auto" {{$seatgroup->type === 'auto' ? 'selected' : ''}}>auto</option>
                  <option value="open" {{$seatgroup->type === 'open' ? 'selected' : ''}}>open</option>
                  <option value="managed" {{$seatgroup->type === 'managed' ? 'selected' : ''}}>managed</option>
                  <option value="hidden" {{$seatgroup->type === 'hidden' ? 'selected' : ''}}>hidden</option>
                </select>
              </div>
            </div>


            <div class="row">
              <div class="col-md-12"></div>
              <div class="form-group col-md-12">
                <label for="role_id">{{trans('seatgroups::seat.seat_groups_role')}}</label>
                <select name="roles[]" id="available_roles" style="width: 100%" multiple>


                  @foreach($seatgroup->role as $role)
                    <option selected="selected" value="{{ $role->id }}">
                      {{ $role->title }}
                    </option>
                  @endforeach
                  @foreach($roles as $role)
                    @if(!in_array($role->id,$seatgroup->role->pluck('id')->toArray()))
                      <option value="{{ $role->id }}">
                        {{ $role->title }}
                      </option>
                    @endif
                  @endforeach

                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <button type="submit" class="btn btn-success">{{trans('seatgroups::seat.seat_groups_update')}}</button>
              </div>
            </div>
          </form>


        </div>
      </div>
    </div>

    <!--Affiliation -->
    <div class="col-md-4">
      @include('seatgroups::affiliation.affiliation')
    </div>

    <!--Moderator (Managed) and User (Hidden) -->
    <div class="col-md-4">
      @if($seatgroup->type == 'managed')


        @include('seatgroups::manager.manager')
      @endif

      @if($seatgroup->type == 'hidden')
        <h3>{{trans('seatgroups::seat.seat_groups_hiddengroup')}}</h3>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{trans('seatgroups::seat.seat_groups_user')}}</h3>
          </div>
          <div class="panel-body">
            <form method="post" action="{{route('seatgroups.user.join', $id)}}">
              {{csrf_field()}}
              <input name="_method3" type="hidden" value="PATCH">
              <div class="form-group">
                <label for="groups">{{ trans_choice('web::seat.available_groups',2) }}</label>
                <select name="groups[]" id="available_users" style="width: 100%" multiple required>

                  @foreach($all_groups as $group)
                    @if(!in_array($group->id,$seatgroup->member->pluck('id')->toArray())))
                    <option value="{{ $group->id }}">
                      {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                    </option>
                    @endif
                  @endforeach

                </select>
              </div>
              <div class="row">
                <div class="col-md-6"></div>
                <div class="form-group col-md-12">
                  <button type="submit" class="btn btn-success btn-block">Add User</button>
                </div>
              </div>
            </form>
            <hr>
            <table class="table table-hover table-condensed">
              <tbody>

              <tr>
                <th colspan="2" class="text-center">Current Members</th>
              </tr>

              @foreach($seatgroup->member as $group)

                <tr>
                  <td>
                    {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                  </td>
                  <td>

                    <form role="form" action="{{ route('seatgroupuser.removeGroupFromSeatGroup',['seat_group_id' => $seatgroup->id, 'group_id' => $group->id]) }}" method="post">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-danger btn-xs pull-right">
                        {{ trans('web::seat.remove') }}
                      </button>
                    </form>
                  </td>
                </tr>

              @endforeach

              </tbody>
            </table>
          </div>
        </div>
      @endif
    </div>
  </div>





@endsection

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_permissions," +
        "#available_users," +
        "#available_characters," +
        "#available_roles").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}"
    });
  </script>

@endpush
