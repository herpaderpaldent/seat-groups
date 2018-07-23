@extends('web::layouts.grids.4-4-4')

@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_about'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">SeAT-Groups</h3>
    </div>
    <div class="panel-body">
      <div class="box-body">

        <legend>Thank you</legend>

        <p>Since SeAT 3.0 beta has launched SeAT Groups has been downloaded almost 400 times. I am very content that my package is being used and supports you and your members.</p>

        <p>As you might know, <code>SeAT</code> and <code>SeAT-Groups</code> are OpenSource Projects which are available free of charge. However, programming takes up a lot of time which keeps me away from the game.</p>

        <p>If you like <code>SeAT-Groups</code>, i highly appreciate ISK Donations to <a href="https://evewho.com/pilot/Herpaderp%20Aldent/"> {!! img('character', 95725047, 64, ['class' => 'img-circle eve-icon small-icon']) !!} Herpaderp Aldent</a></p>

        </div>
    </div>
  </div>

@stop
@section('center')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">About</h3>
    </div>
    <div class="panel-body">

      <legend>Bugs and issues</legend>

      <p>If you find something is not working as expectected, please don't hesitate and contact me. Either use SeAT-Slack or submit an <a href="https://github.com/herpaderpaldent/seat-groups/issues/new">issue on Github</a></p>

    </div>
  </div>

@stop
@section('right')
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-rss"></i> Update feed</h3>
    </div>
    <div class="panel-body" style="height: 500px; overflow-y: scroll">
      {!! $changelog !!}
    </div>
    <div class="panel-footer">
      <div class="row">
        <div class="col-md-6">
          Installed version: <b>{{ config('seatgroups.config.version') }}</b>
        </div>
        <div class="col-md-6">
          Latest version:
          <a href="https://packagist.org/packages/herpaderpaldent/seat-groups">
            <img src="https://poser.pugx.org/herpaderpaldent/seat-groups/v/stable" alt="SeAT Groups version" />
          </a>
        </div>
      </div>
    </div>
  </div>
@stop




