@extends('web::layouts.grids.4-4-4')


@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_edit'))



@section('left')

    <div class="panel-body">
        @if(Auth::user()->hasRole('Superuser'))
            {!! Form::open([
            'method' => 'DELETE',
            'route' => ['seatgroups.destroy', $seatgroup->id],
            'style'=>'display:inline']) !!}
            {!! Form::submit('Delete SeATGroup', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        @endif
    </div>



    <div class="panel panel-default">
        <div class="panel-heading">

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
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success">{{trans('seatgroups::seat.seat_groups_update')}}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection

@section('center')

    <h3>Available for whom</h3>
    <p>Select the corporation to which the SeAT Group is bound.</p>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Corporations</h3>
        </div>
        <div class="panel-body">
            <form method="post" action="{{route('seatgroupcorporation.update', $id)}}">
                {{csrf_field()}}
                <input name="_method3" type="hidden" value="PATCH">
                <div class="form-group">
                    <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
                    <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>


                        @foreach($all_corporations as $corporation)
                            @if(!in_array($corporation->corporation_id,$seatgroup->corporation->pluck('corporation_id')->toArray()))
                                <option value="{{ $corporation->corporation_id }}">
                                    {{ $corporation->name }}
                                </option>
                            @endif
                        @endforeach

                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success btn-block">Add Corporation</button>
                    </div>
                </div>
            </form>
            <hr>
            <table class="table table-hover table-condensed">
                <tbody>

                <tr>
                    <th colspan="2" class="text-center">Current Corporations</th>
                </tr>
                @foreach($corporations as $corperation)

                    <tr>
                        <td>{{$corperation ->name}}</td>
                        <td>
                            {!! Form::open(['method' => 'DELETE',
                            'route' => ['seatgroupcorporation.destroy',$seatgroup->id,$corperation->corporation_id],
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

@endsection

@section('right')

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