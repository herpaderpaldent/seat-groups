@extends('web::layouts.grids.4-4-4')


@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))



@section('left')



    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Editing {{$seatgroup->name}}</h3>
        </div>

        <div class="panel-body">
            <form method="post" action="{{route('seatgroups.update', $id)}}">
                {{csrf_field()}}
                <input name="_method3" type="hidden" value="PATCH">
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value="{{$seatgroup->name}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="description">SeAT-Group Description:</label>
                        <textarea type="text" class="form-control" rows="5" name="description" >{{$seatgroup->description}}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="type">Select SeAT-Group Type</label>
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
                        <label for="role_id">Select corresponding SeAT-Role</label>
                        {!! Form::select('role_id', $roles, $seatgroup->role_id, ['class' => 'form-control']) !!}
                    </div>
                </div>

                    <div class="col-md-12"></div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning ">Update SeatGroup</button>
                    </div>

            </form>

        </div>
    </div>



@endsection

@section('center')

    <h2>Available for whom</h2>
    <small>here blade for each type of group</small>
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
                            <option value="{{ $corporation->corporation_id }}">
                                {{ $corporation->name }}
                            </option>
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

    {{--
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans_choice('web::seat.user', 2) }}</h3>
        </div>
        <div class="panel-body">

            TODO: Adapt route for SeatGroupManager
            <form role="form" action="{{ route('configuration.access.roles.edit.users') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="role_id" value="{{ $seatgroup->id }}">

                <div class="form-group">
                    <label for="users">{{ trans('web::seat.available_users') }}</label>
                    <select name="users[]" id="available_users" style="width: 100%" multiple>

                        @foreach($all_users as $user)

                            @if(!in_array($user, $role_users)) TODO: check for already assigned user and don't list as option
                                <option value="{{ $user }}">{{ $user }}</option>
                            @endif

                    @endforeach

                    </select>
                </div>

                <button type="submit"
                        class="btn btn-success btn-block">{{ trans_choice('web::seat.add_user', 2) }}</button>

            </form>
            {{--
            <hr>

            <table class="table table-hover table-condensed">
                <tbody>

                <tr>
                    <th colspan="2" class="text-center">{{ trans('web::seat.current_users') }}</th>
                </tr>

                @foreach($role->users as $user)

                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>
                            <a href="{{ route('configuration.access.roles.edit.remove.user', ['role_id' => $role->id, 'user_id' => $user->id]) }}"
                               type="button" class="btn btn-danger btn-xs pull-right">
                                {{ trans('web::seat.remove') }}
                            </a>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

        </div>
        <div class="panel-footer">
            <b>{{ count($role->users) }}</b> {{ trans_choice('web::seat.user', count($role->users)) }}
        </div>
    </div>--}}

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