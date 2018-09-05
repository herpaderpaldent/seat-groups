@extends('web::layouts.grids.12')

@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_edit'))

@section('content')


    <div class="row">
        <div class="col-lg-12">
            @if($seatgroup->corporation->isEmpty() && !$seatgroup->all_corporations)
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> SeAT-Group is not working</h4>
                    SeAT-Group needs a corporation to set to work correctly. This will prevent members that have been purged from corporation to still keep their roles.
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
                            <form role="form" action="{{ route('seatgroups.destroy', ['seatgroup_id' => $seatgroup->id]) }}" method="post">
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
                                <textarea type="text" class="form-control" rows="5" name="description" >{{$seatgroup->description}}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12"></div>
                            <div class="form-group col-md-12">
                                <label for="type">{{trans('seatgroups::seat.seat_groups_type')}}</label>
                                {{Form::select('type',[
                                    'auto' => 'auto',
                                    'managed'=>[
                                        'open'=>'open',
                                        'managed' => 'managed'
                                    ],
                                    'hidden' => 'hidden'
                                ], $seatgroup->type,['class'=>'form-control'])}}

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
                                            <option  value="{{ $role->id }}">
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
                <h3>{{trans('seatgroups::seat.seat_groups_managedgroup')}}</h3>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans('seatgroups::seat.seat_groups_manager')}}</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="{{route('seatgroupuser.addmanager', $id)}}">
                            {{csrf_field()}}
                            <input name="_method3" type="hidden" value="PATCH">
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
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-success btn-block">Add Manager</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <table class="table table-hover table-condensed">
                            <tbody>

                            <tr>
                                <th colspan="2" class="text-center">Current Manager</th>
                            </tr>

                            @foreach($seatgroup->manager as $group)

                                <tr>
                                    <td>
                                        {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                                    </td>
                                    <td>
                                        {!! Form::open(['method' => 'DELETE',
                                    'route' => ['seatgroupuser.removemanager',$seatgroup->id,$group->id],
                                    'style'=>'display:inline'
                                    ]) !!}
                                        {!! Form::submit(trans('web::seat.remove'), ['class' => 'btn btn-danger btn-xs pull-right']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($seatgroup->type == 'hidden')
                <h3>{{trans('seatgroups::seat.seat_groups_hiddengroup')}}</h3>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans('seatgroups::seat.seat_groups_user')}}</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="{{route('seatgroupuser.update', $id)}}">
                            {{csrf_field()}}
                            <input name="_method3" type="hidden" value="PATCH">
                            <div class="form-group">
                                <label for="groups">{{ trans_choice('web::seat.available_groups',2) }}</label>
                                <select name="groups[]" id="available_users" style="width: 100%" multiple>

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
                                <th colspan="2" class="text-center">Current Users</th>
                            </tr>

                            @foreach($seatgroup->member as $group)

                                <tr>
                                    <td>
                                        {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                                    </td>
                                    <td>
                                        {!! Form::open(['method' => 'DELETE',
                                    'route' => ['seatgroupuser.removeGroupFromSeatGroup',$seatgroup->id,$group->id],
                                    'style'=>'display:inline'
                                    ]) !!}
                                        {!! Form::submit(trans('web::seat.remove'), ['class' => 'btn btn-danger btn-xs pull-right']) !!}
                                        {!! Form::close() !!}
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
          "#available_roles," +
          "#available_corporations").select2({
        placeholder: "{{ trans('web::seat.select_item_add') }}"
      });
    </script>

@endpush
