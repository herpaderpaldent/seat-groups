
@push('head')
    <link href="{{ asset('web/css/seat-groups.css') }}" rel="stylesheet">
@endpush


@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_dashboard'))


@extends('web::layouts.grids.4-4-4')

@section('left')

        <h3>{{trans('seatgroups::seat.seat_groups_autogroup')}}</h3>
        <p>{{trans('seatgroups::seat.seat_groups_autogroup_description')}}</p>


    @foreach($autogroups as $groupname)
        @foreach($groupname->corporation as $corporation)
            @if($corporation->corporation_id === Auth::user()->group->main_character->character->corporation_id || Auth::user()->hasRole('seatgroups.edit'))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                        <button class="btn btn-link pull-right">
                            @if(Auth::user()->hasRole('seatgroups.edit'))
                                <a href="{{route('seatgroups.edit', $groupname->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                            @endif
                        </button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {{$groupname->description}}
                    </div>
                </div>
                @break
            @endif
        @endforeach

    @endforeach


@endsection

@section('center')

    <h3>{{trans('seatgroups::seat.seat_groups_opengroup')}}</h3>
    <p>{{trans('seatgroups::seat.seat_groups_opengroup_description')}}</p>

    @foreach($opengroups as $groupname)
        @if($corporation->corporation_id === Auth::user()->group->main_character->character->corporation_id || Auth::user()->hasRole('seatgroups.edit'))
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                    @if(Auth::user()->hasRole('seatgroups.edit'))
                        <button class="btn btn-link pull-right">
                            <a href="{{route('seatgroups.edit', $groupname->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                        </button>
                    @endif

                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    {{$groupname->description}}

                    @if(true)
                        @if(true)
                            {!! Form::open(['method' => 'POST',
                                        'route' => ['seatgroupuser.update', $groupname->id],
                                        'style'=>'display:inline'
                                        ]) !!}
                            {!! Form::submit(trans('seatgroups::seat.seat_join_opengroup'), ['class' => 'btn btn-success pull-right']) !!}
                            {!! Form::close() !!}
                        @elseif(false)
                            {!! Form::open(['method' => 'DELETE',
                                        'route' => ['seatgroupuser.update', $groupname->id],
                                        'style'=>'display:inline'
                                        ]) !!}
                            {!! Form::submit(trans('seatgroups::seat.seat_leave_opengroup'), ['class' => 'btn btn-danger pull-right']) !!}
                            {!! Form::close() !!}
                        @endif
                    @endif

                </div>
            </div>
        @endif
    @endforeach

@endsection

@section('right')

    <h3>{{trans('seatgroups::seat.seat_groups_managedgroup')}}</h3>
    <p>{{trans('seatgroups::seat.seat_groups_managedgroup_description')}}</p>
    @foreach($managedgroups as $groupname)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                @if($groupname->isManager(auth()->user(),$groupname->id))
                    <button class="btn btn-link pull-right">
                        <a href="{!! url('seatgroups/edit',$groupname->id) !!}"><i class="fa fa-edit"></i></a>
                    </button>
                @endif

                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                {{$groupname->description}}
            </div>

        </div>
    @endforeach

@endsection