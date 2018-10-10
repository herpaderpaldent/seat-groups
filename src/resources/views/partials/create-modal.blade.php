<li class="pull-right">
  <a href="" class="text-muted" data-toggle="modal" data-target="#SeATGroupCreate">
    <i class="fa fa-plus-square" data-toggle="tooltip" title="Add new SeAT Group" style="color:green"></i>
  </a>
</li>

<div class="modal fade" id="SeATGroupCreate" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">{{trans('seatgroups::seat.seat_groups_create_new')}}</h4>
      </div>
      <form id="seatgroup-create-form" method="post" action="{{route('seatgroups.store')}}">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12"></div>
            <div class="form-group col-md-12">
              <label for="name">{{trans('seatgroups::seat.seat_groups_name')}}</label>
              <input type="text" class="form-control" name="name" placeholder="SeAT-Group Name" pattern=".{5,}" required title="5 characters minimum">
            </div>
          </div>
          <div class="row">
            <div class="col-md-12"></div>
            <div class="form-group col-md-12">
              <label for="description">{{trans('seatgroups::seat.seat_groups_description')}}</label>
              <textarea type="text" class="form-control" rows="5" name="description" placeholder="SeAT-Group Description" minlength="10" required></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12"></div>
            <div class="form-group col-md-12">
              <label for="type">{{trans('seatgroups::seat.seat_groups_type')}}</label>
              <select class="form-control" name="type">
                <option value="auto">auto</option>
                <option value="open">open</option>
                <option value="managed">managed</option>
                <option value="hidden">hidden</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12"></div>
            <div class="form-group-lg col-md-12">
              <label for="role_id">{{trans('seatgroups::seat.seat_groups_role')}}</label>
              <select name="roles[]" id="available_roles" style="width: 100%" multiple>
                <option></option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">{{trans('seatgroups::seat.seat_groups_add')}}</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@push('javascript')

  <script type="text/javascript">

    $(document).ready(function(){
      $("#SeATGroupCreate").on('show.bs.modal', function () {
        $("#available_roles").select2({
          placeholder: "{{ trans('web::seat.select_item_add') }}"
        });
        $.ajax({
          type: 'GET',
          url: '{{ route('seatgroups.create') }}',
          success: function(data){
            for (var i = 0; i < data.length; i++) {
              $('#available_roles').append($('<option></option>').attr('value', data[i].id).text(data[i].title));
            }
          },
          error  : function (xhr, textStatus, errorThrown) {
            console.log(xhr);
            console.log(textStatus);
            console.log(errorThrown);
          }
        })
      })
    });

  </script>

@endpush