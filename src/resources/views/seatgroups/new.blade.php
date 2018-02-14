@extends('web::layouts.grids.12')


@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

    <h1>New</h1>
    {{ Form::open(array('url' => 'seatgroups/create')) }}
        {{Form::label('email', 'E-Mail Address')}}<br>
        {{Form::text('email', 'example@gmail.com')}}
    {{Form::submit('Button')}}
    {{ Form::close() }}


@stop