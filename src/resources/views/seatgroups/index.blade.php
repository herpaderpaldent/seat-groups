
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                <button class="btn btn-link pull-right">
                    @if($groupname->isManager(auth()->user()->group->main_character_id,$groupname->id)
                        || Auth::user()->hasRole('Superuser')
                    )
                        <a href="{{route('seatgroups.edit', $groupname->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                    @endif
                </button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                {{$groupname->description}}
                {{$groupname->manager}}
            </div>
        </div>
    @endforeach


@endsection

@section('center')

    <h3>{{trans('seatgroups::seat.seat_groups_opengroup')}}</h3>
    <p>{{trans('seatgroups::seat.seat_groups_opengroup_description')}}</p>

    @foreach($opengroups as $groupname)
        @if(count($groupname->corporation->firstwhere('corporation_id','=',\Seat\Eveapi\Models\Corporation\CorporationInfo::find(auth()->user()->group->main_character_id)->corporation))>0 ||
        $groupname->isManager(auth()->user()->group->main_character_id,$groupname->id) ||
        auth()->user()->hasRole('Superuser'))
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                    @if($groupname->isManager(auth()->user(),$groupname->id))
                        <button class="btn btn-link pull-right">
                            <a href="{{route('seatgroups.edit', $groupname->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                        </button>
                    @endif

                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    {{$groupname->description}}

                    @if(count($groupname->corporation->firstwhere('corporation_id','=',auth()->user()->character->corporation_id))>0)
                        @if(!$groupname->isMember(auth()->user()->getAuthIdentifier(),$groupname->id))
                            {!! Form::open(['method' => 'POST',
                                        'route' => ['seatgroupuser.update', $groupname->id],
                                        'style'=>'display:inline'
                                        ]) !!}
                            {!! Form::submit(trans('seatgroups::seat.seat_join_opengroup'), ['class' => 'btn btn-success pull-right']) !!}
                            {!! Form::close() !!}
                        @elseif($groupname->isMember(auth()->user()->getAuthIdentifier(),$groupname->id))
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