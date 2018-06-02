@extends('web::layouts.grids.4-4-4')


@section('title', trans('seatgroups::seat.seat_groups'))
@section('page_header', trans('seatgroups::seat.seat_groups'))
@section('page_description', trans('seatgroups::seat.seat_groups_create'))

@section('left')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{trans('seatgroups::seat.seat_groups_create_new')}}</h3>
        </div>
        <div class="panel-body">
            <form method="post" action="{{route('seatgroups.store')}}">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="name">{{trans('seatgroups::seat.seat_groups_name')}}</label>
                        <input type="text" class="form-control" name="name" placeholder="SeAT-Group Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="description">{{trans('seatgroups::seat.seat_groups_description')}}</label>
                        <textarea type="text" class="form-control" rows="5" name="description" placeholder="SeAT-Group Description"></textarea>
                    </div>
                </div>
                <!-- TODO: use data-icon-->
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="type">{{trans('seatgroups::seat.seat_groups_type')}}</label>
                        {{Form::select('type',[
                            'auto' => 'auto',
                            'managed'=>[
                                'open'=>'open',
                                'managed' => 'managed'
                            ],
                            'hidden' => 'hidden'
                        ],null,['class'=>'form-control'])}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <label for="role_id">{{trans('seatgroups::seat.seat_groups_role')}}</label>
                        <select name="roles[]" id="available_roles" style="width: 100%" multiple>


                            @foreach($roles as $role)
                                @if(true)
                                    <option value="{{ $role->id }}">
                                        {{ $role->title }}
                                    </option>
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"></div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success">{{trans('seatgroups::seat.seat_groups_add')}}</button>
                    </div>
                </div>
            </form>
        </div>

@endsection

@section('center')

@endsection

@section('right')

@endsection

        @push('javascript')

            @include('web::includes.javascript.id-to-name')

            <script>
              $("#available_roles").select2({
                placeholder: "{{ trans('web::seat.select_item_add') }}"
              });
            </script>

    @endpush
