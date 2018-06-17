@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_dashboard'))

@extends('web::layouts.grids.4-4-4')

@section('left')

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
                    @continue
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="btn-group pull-right">
                            @if(auth()->user()->hasRole('seatgroups.edit'))
                                <a href="{{ route('seatgroups.edit', $seatgroup->id)}}" class="btn btn-sm btn-warning pull-right">
                                    <i class="fa fa-edit"></i> {{ trans('web::seat.edit') }}
                                </a>
                            @endif
                        </div>
                        <h3 class="panel-title">{{$seatgroup->name}}</h3>
                    </div>
                    <div class="panel-body">{{$seatgroup->description}}</div>
                </div>
            @endforeach

        </div>





    </div>

@endsection

@section('center')
    <div class="row">

        <div class="col-md-12">
            <h3>{{ trans('seatgroups::seat.seat_groups_opengroup')}}</h3>
            <p>{{ trans('seatgroups::seat.seat_groups_opengroup_description')}}</p>
        </div>

    </div>

    <div class="col-md-12">

        @foreach($seatgroups->where('type', 'open') as $seatgroup)
            @if(! $seatgroup->isAllowedToSeeSeatGroup())
                @continue
            @endif

            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="btn-group pull-right">
                        @if(auth()->user()->hasRole('seatgroups.edit'))
                            <a href="{{ route('seatgroups.edit', $seatgroup->id)}}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i> {{ trans('web::seat.edit') }}
                            </a>
                        @endif
                    </div>
                    <h3 class="panel-title">{{$seatgroup->name}}</h3>
                </div>
                <div class="panel-body">
                    {{$seatgroup->description}}

                    @if(!$seatgroup->isMember())
                        {!! Form::open(['method' => 'POST',
                                        'route' => ['seatgroupuser.update', $seatgroup->id],
                                        'style'=>'display:inline'
                                       ]) !!}
                        {!! Form::submit(trans('seatgroups::seat.seat_join_opengroup'), ['class' => 'btn btn-sm btn-success pull-right']) !!}
                        {!! Form::close() !!}
                    @elseif($seatgroup->isMember())
                        {!! Form::open(['method' => 'DELETE',
                                        'route' => ['seatgroupuser.update', $seatgroup->id],
                                        'style'=>'display:inline'
                                        ]) !!}
                        {!! Form::submit(trans('seatgroups::seat.seat_leave_opengroup'), ['class' => 'btn btn-sm btn-danger pull-right']) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>

        @endforeach

    </div>
@endsection

@section('right')
    <div class="row">
        <div class="col-md-12">
            <h3>{{ trans('seatgroups::seat.seat_groups_managedgroup')}}</h3>
            <p>{{ trans('seatgroups::seat.seat_groups_managedgroup_description')}}</p>
        </div>
    </div>

    <div class="col-md-12">

        @foreach($seatgroups->where('type', 'managed') as $seatgroup)
            @if(!$seatgroup->isAllowedToSeeSeatGroup())
                @continue
            @endif

            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="btn-group pull-right">
                        @if(auth()->user()->hasRole('seatgroups.edit'))
                            <a href="{{ route('seatgroups.edit', $seatgroup->id)}}" class="btn btn-sm btn-warning pull-right">
                                <i class="fa fa-edit"></i> {{ trans('web::seat.edit') }}
                            </a>
                        @endif
                    </div>
                    <h3 class="panel-title">{{$seatgroup->name}}</h3>
                </div>
                <div class="panel-body">
                    {{$seatgroup->description}} <br>
                    Members: {{$seatgroup->member->map(function($group) { return $group->main_character->name;})->implode(', ')}}

                    @if($seatgroup->onWaitlist())
                        {!! Form::open(['method' => 'DELETE',
                                        'route' => ['seatgroupuser.update', $seatgroup->id],
                                        'style'=>'display:inline'
                                       ]) !!}
                        {!! Form::submit(trans('seatgroups::seat.seat_leave_waitlist'), ['class' => 'btn btn-sm btn-info pull-right']) !!}
                        {!! Form::close() !!}
                    @elseif (!$seatgroup->isMember())
                        {!! Form::open(['method' => 'POST',
                                        'route' => ['seatgroupuser.update', $seatgroup->id],
                                        'style'=>'display:inline'
                                       ]) !!}
                        {!! Form::submit(trans('seatgroups::seat.seat_join_waitlist'), ['class' => 'btn btn-sm btn-success pull-right']) !!}
                        {!! Form::close() !!}
                    @elseif($seatgroup->isMember())
                        {!! Form::open(['method' => 'DELETE',
                                        'route' => ['seatgroupuser.update', $seatgroup->id],
                                        'style'=>'display:inline'
                                       ]) !!}
                        {!! Form::submit(trans('seatgroups::seat.seat_leave_managedgroup'), ['class' => 'btn btn-sm btn-danger pull-right']) !!}
                        {!! Form::close() !!}
                    @endif

                </div>
                <div class="panel-footer">
                    Managers: {{$seatgroup->manager->map(function($user) { return $user->main_character->name; })->implode(', ')}}

                @if($seatgroup->isManager())
                    <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#ModalSeATGrooup{{$seatgroup->id}}">Manage Members</button>

                        <!-- Modal -->
                        <div id="ModalSeATGrooup{{$seatgroup->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Manage Memebers: {{$seatgroup->name}}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-hover table-condensed table-striped">
                                            @foreach($seatgroup->waitlist as $group)
                                                <tr>
                                                    <td>
                                                        {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                                                    </td>
                                                    <td>
                                                        {!! Form::open(['method' => 'POST',
                                                                        'route' => ['seatgroupuser.acceptmember', $seatgroup->id, $group->id],
                                                                        'style'=>'display:inline'
                                                                       ]) !!}
                                                        {!! Form::submit(trans('seatgroups::seat.seat_accept_managedgroup'), ['class' => 'btn-xs btn-success pull-right']) !!}
                                                        {!! Form::close() !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::open(['method' => 'DELETE',
                                                                        'route' => ['seatgroupuser.removemember', $seatgroup->id, $group->id],
                                                                        'style'=>'display:inline'
                                                                       ]) !!}
                                                        {!! Form::submit(trans('seatgroups::seat.seat_deny_managedgroup'), ['class' => 'btn btn-xs btn-danger pull-right']) !!}
                                                        {!! Form::close() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach($seatgroup->member as $group)
                                                <tr>
                                                    <td>{{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}</td>
                                                    <td></td>
                                                    <td>
                                                        {!! Form::open(['method' => 'DELETE',
                                                                        'route' => ['seatgroupuser.removemember', $seatgroup->id, $group->id],
                                                                        'style'=>'display:inline'
                                                                       ]) !!}
                                                        {!! Form::submit(trans('seatgroups::seat.seat_removefrom_managedgroup'), ['class' => 'btn btn-xs btn-danger pull-right']) !!}
                                                        {!! Form::close() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>

            </div>
        @endforeach

        @foreach ($seatgroups->where('type', 'hidden') as $seatgroup)
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="btn-group pull-right">
                        @if(auth()->user()->hasRole('seatgroups.edit'))
                            <a href="{{ route('seatgroups.edit', $seatgroup->id)}}" class="btn btn-warning pull-right">
                                <i class="fa fa-edit"></i> {{ trans('web::seat.edit') }}
                            </a>
                        @endif
                    </div>
                    <h3 class="panel-title">{{$seatgroup->name}}</h3>
                </div>
                <div class="panel-body">
                    {{$seatgroup->description}} <br>
                    Members: {{$seatgroup->member->map(function($group) { return $group->main_character->name;})->implode(', ')}}
                </div>
            </div>
        @endforeach

    </div>
@endsection
