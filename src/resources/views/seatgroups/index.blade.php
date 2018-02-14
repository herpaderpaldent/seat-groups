@extends('web::layouts.grids.12')


@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

 <h1>something</h1>

    <ul>
        @foreach($groupname as $groupname)
            <li><a href="{!! url('seatgroups/edit',$groupname->id) !!}">{{$groupname->name}}</a></li>
        @endforeach
    </ul>

    <a href="{!! 'seatgroups/new' !!}">New Group</a>
@stop