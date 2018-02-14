@extends('web::layouts.grids.12')


@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

    <h1>{{$group->name}}</h1>
    <p>{{$group->description}}</p>
    <p><small>{{$group->type}}</small></p>


@stop