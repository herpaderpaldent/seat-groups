@extends('web::layouts.grids.4-4-4')


@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_edit'))



@section('left')



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
                        ]], $seatgroup->type,['class'=>'form-control'])}}

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="role_id">{{trans('seatgroups::seat.seat_groups_role')}}</label>
                        {!! Form::select('role_id', $roles, $seatgroup->role_id, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="col-md-12"></div>
                <div class="form-group">
                    <button type="submit" class="btn btn-warning ">{{trans('seatgroups::seat.seat_groups_update')}}</button>
                </div>

            </form>

        </div>
    </div>

@endsection

@section('center')

    <h2>Available for whom</h2>
    <p>here blade for each type of group</p>

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

    <h3>test</h3>

    <div class="row">
        <div class="col-md-3">
            @if(Auth::user()->hasRole('Superuser'))
                {!! Form::open([
                'method' => 'DELETE',
                'route' => ['seatgroups.destroy', $seatgroup->id],
                'style'=>'display:inline']) !!}
                {!! Form::submit('Delete SeATGroup', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            @endif
        </div>
    </div>

    <div class="row"> <br> </div>

    @if(!$seatgroup->type == 'open')
        <!--TODO: write controller for making Manager-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Manager</h3>
            </div>
            <div class="panel-body">
                <form method="post" action="{{route('seatgroupuser.update', $id)}}">
                    {{csrf_field()}}
                    <input name="_method3" type="hidden" value="PATCH">
                    <div class="form-group">
                        <label for="users">{{ trans('web::seat.available_user') }}</label>
                        <select name="users[]" id="available_corporations" style="width: 100%" multiple>


                            @foreach($all_characters as $character)
                                @if(!in_array($character->character_id,$seatgroup->user->pluck('character_id')->toArray()))
                                    <option value="{{ $character->character_id }}">
                                        {{ $character->name }}
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
                    @foreach($characters as $character)

                        <tr>
                            <td>{{$character ->name}}</td>
                            <td>
                                {!! Form::open(['method' => 'DELETE',
                                'route' => ['seatgroupuser.destroy',$seatgroup->id],
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
            "#available_corporations").select2({
            placeholder: "{{ trans('web::seat.select_item_add') }}"
        });
    </script>

@endpush