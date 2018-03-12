
@push('head')
    <link href="{{ asset('web/css/seat-groups.css') }}" rel="stylesheet">
@endpush


@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))


@extends('web::layouts.grids.4-4-4')

@section('left')

    <h3>Auto Groups</h3>
    <p>To these Groups you are automatically assigned based on the corporation or alliance you beloi</p>

    @foreach($autogroups as $groupname)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                <!-- ToDo: Adapt to buttongroup -->
                <button class="btn btn-link pull-right">
                @if($groupname->isManager(auth()->user(),$groupname->id) || Auth::user()->hasRole('Superuser'))
                        <a href="{{route('seatgroups.edit', $groupname->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                @endif
                </button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                {{$groupname->description}}
                {{$groupname->manager}}

            </div>
            <!-- TODO: with Groupmanagers to extend
            <div class="panel-footer">

            </div>
            -->
        </div>
    @endforeach
@endsection

@section('center')

    <h3>Open Groups</h3>
    <p>In these Groups you can opt-in and opt-out as you like.</p>

    <p>{{auth()->user()->getAuthIdentifier()}}</p>

    @foreach($opengroups as $groupname)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                @if($groupname->isManager(auth()->user(),$groupname->id))
                    <button class="btn btn-link pull-right">
                        <a href="{{route('seatgroups.edit', $groupname->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                    </button>
                @endif

                @if(!$groupname->isMember(auth()->user()->getAuthIdentifier(),$groupname->id))
                {!! Form::open(['method' => 'POST',
                            'route' => ['seatgroupuser.update', $groupname->id],
                            'style'=>'display:inline'
                            ]) !!}
                {!! Form::submit(trans('web::seat.joingroup'), ['class' => 'btn btn-success pull-right']) !!}
                {!! Form::close() !!}
                @endif

                @if($groupname->isMember(auth()->user()->getAuthIdentifier(),$groupname->id))
                    {!! Form::open(['method' => 'DELETE',
                                'route' => ['seatgroupuser.update', $groupname->id],
                                'style'=>'display:inline'
                                ]) !!}
                    {!! Form::submit(trans('web::seat.leavegroup'), ['class' => 'btn btn-danger pull-right']) !!}
                    {!! Form::close() !!}
                @endif

                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                {{$groupname->description}}

            </div>
        </div>
    @endforeach

@endsection

@section('right')
    <h3>Managed Groups</h3>
    <p>Here you can apply for certain groups. The managers of this
    group will approve or deny your request.</p>
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