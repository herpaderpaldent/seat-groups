@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_dashboard'))

@extends('web::layouts.grids.12')

@section('content')
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li><a href="#auto_group" data-toggle="tab">{{ trans('seatgroups::seat.seat_groups_autogroup')}}</a></li>
      <li class="active"><a href="#open_group" data-toggle="tab">{{ trans('seatgroups::seat.seat_groups_opengroup')}}</a></li>
      <li><a href="#managed_group" data-toggle="tab">{{ trans('seatgroups::seat.seat_groups_managedgroup')}}</a></li>
      @if($seatgroups->where('type', 'hidden')->count()>0)
        <li><a href="#hidden_group" data-toggle="tab">{{ trans('seatgroups::seat.seat_groups_hiddengroup')}}  <i class="fa fa-info" data-toggle="tooltip" data-title="Only Members of a hidden SeAT Group can see this tab"></i></a></li>
      @endif
      @includeWhen(auth()->user()->hasRole('seatgroups.create'),'seatgroups::partials.create-modal')
    </ul>
    <div class="tab-content">
      <div class="tab-pane " id="auto_group">
        <div class="row">
          <div class="col-md-12">
            <h3>{{ trans('seatgroups::seat.seat_groups_autogroup')}}</h3>
            <p>{{ trans('seatgroups::seat.seat_groups_autogroup_description')}}</p>
          </div>
        </div>

        <div class="row">
          @each('seatgroups::partials.auto-group',$seatgroups->where('type', 'auto'),'seatgroup')
        </div>
      </div>
      <!-- /.tab-pane -->
      <div class="tab-pane active" id="open_group">
        <div class="row">

          <div class="col-md-12">
            <h3>{{ trans('seatgroups::seat.seat_groups_opengroup')}}</h3>
            <p>{{ trans('seatgroups::seat.seat_groups_opengroup_description')}}</p>
          </div>

        </div>
        <div class="row">
          @each('seatgroups::partials.open-group',$seatgroups->where('type', 'open'),'seatgroup')
        </div>
      </div>
      <!-- /.tab-pane -->
      <div class="tab-pane" id="managed_group">
        <div class="row">
          <div class="col-md-12">
            <h3>{{ trans('seatgroups::seat.seat_groups_managedgroup')}}</h3>
            <p>{{ trans('seatgroups::seat.seat_groups_managedgroup_description')}}</p>
          </div>
        </div>

        <div class="row">
          @each('seatgroups::partials.managed-group',$seatgroups->where('type', 'managed'),'seatgroup')
        </div>
        {{--@include('seatgroups::partials.managed-group')--}}
      </div>
      <!-- /.tab-pane -->
      <div class="tab-pane" id="hidden_group">
        <div class="row">
          @each('seatgroups::partials.hidden-group',$seatgroups->where('type', 'hidden'),'seatgroup')
        </div>
      </div>
      <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
  </div>
@endsection

@push('javascript')
  <script type="application/javascript">
    $(function() {
      @if(!empty(session('activeTab')))
      $('.nav-tabs a[href="#' + '{{session('activeTab')}}' + '"]').tab('show');
      @endif
    })
  </script>
@endpush