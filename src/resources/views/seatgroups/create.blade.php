@extends('web::layouts.grids.12')


@section('title', trans('seat-groups::seat_groups_admin'))
@section('page_header', trans('seatgroups::seat.seat_groups_admin'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

    <h2>Create a new SeatGroup</h2>

    <form method="post" action="{{url('seatgroups')}}">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" placeholder="SeAT-Group Name">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="description">SeAT-Group Description:</label>
                <textarea type="text" class="form-control" rows="5" name="description" placeholder="SeAT-Group Description"></textarea>
            </div>
        </div>
    <!-- TODO: use data-icon-->
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="type">Select SeAT-Group Type</label>
                <select class="form-control" name="type" id="type">
                    <option>auto</option>
                    <option>hidden</option>
                    <optgroup label="Managed">
                        <option>open</option>
                        <option>managed</option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="role_id">Select corresponding SeAT-Role</label>
                {!! Form::select('role_id', $roles, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-success">Add new SeatGroup</button>
            </div>
        </div>
    </form>



@stop