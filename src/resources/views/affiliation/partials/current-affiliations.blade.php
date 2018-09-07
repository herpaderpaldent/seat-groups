<table id="current_affiliation_table" class="display table-condensed table-hover" style="width:100%">
  <thead>
    <tr>
      <th colspan="4" class="text-center">Current Affiliations</th>
    </tr>
  </thead>
</table>



@push('javascript')
  <script type="application/javascript">
    $(function(){
      $('table#current_affiliation_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('affiliation.get.current.affiliations') }}',
          type: "POST",
          data: {
            seatgroup_id: '{{$seatgroup->id}}'
          }
        },
        columns: [
          {data: 'affiliation'},
          {data: 'remove'},
        ],
        order: [[0,'asc']]
      });
    });
  </script>
@endpush


