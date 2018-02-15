@extends('web::layouts.grids.12')

@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))

<link href="{{ asset('/css/seat-groups.css') }}" rel="stylesheet">
@section('full')

 <h1>something</h1>

    @foreach($groupname as $groupname)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">{{$groupname->name}}</h3>

                <button class="btn btn-default pull-right"><a href="{!! url('seatgroups/edit',$groupname->id) !!}">{{$groupname->name}}</a></button>

                <div class="clearfix"></div>
            </div>
            <div class="panel-body">{{$groupname->description}}</div>
        </div>
    @endforeach


    <a href="{!! 'seatgroups/new' !!}">New Group</a>

@stop