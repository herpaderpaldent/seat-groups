@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_dashboard'))

@extends('web::layouts.grids.4-4-4')

@section('left')

    @include('seatgroups::partials.auto-group')

@endsection

@section('center')

    @include('seatgroups::partials.open-group')

@endsection

@section('right')
  <div class="row">
    <div class="col-md-12">
      <h3>{{ trans('seatgroups::seat.seat_groups_managedgroup')}}</h3>
      <p>{{ trans('seatgroups::seat.seat_groups_managedgroup_description')}}</p>
    </div>


    @include('seatgroups::partials.managed-group')

  </div>
@endsection
