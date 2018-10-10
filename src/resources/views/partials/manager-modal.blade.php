<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#ModalSeATGroup{{$seatgroup->id}}">Manage Members</button>

<!-- Modal -->
<div id="ModalSeATGroup{{$seatgroup->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Manage Members: {{$seatgroup->name}}</h4>
      </div>
      <div class="modal-body">
        <table id="current_member_table_{{$seatgroup->id}}" class="display table-hover table-condensed table-striped" style="width:100%" >
          <thead>
            <th>Member</th>
            <th>Action</th>
          </thead>

        </table>

      </div>
    </div>

  </div>
</div>

@push('javascript')
  <script type="application/javascript">
    $(function(){
      $('table#current_member_table_{{$seatgroup->id}}').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('seatgroups.get.members.table', $seatgroup->id) }}',
          type: "GET"
        },
        columns: [
          {className: 'col-sm-9', data: 'name'},
          {className: 'col-sm-3', data: 'actions'},
        ],
      });
    });
  </script>
@endpush