<table class="table table-condensed table-hover table-responsive no-margin" id="logs-table" data-page-length="25">
  <thead>
  <tr>
    <th>{{ trans('web::seat.date') }}</th>
    <th>{{ trans('web::seat.category') }}</th>
    <th>{{ trans('web::seat.message') }}</th>
  </tr>
  </thead>
</table>


@push('javascript')
  <script type="text/javascript">
    $(function(){
      $('table#logs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('logs.get') }}',
        columns: [
          {data: 'created_at'},
          {data: 'event', name: 'event'},
          {data: 'message'}
        ],
        order: [[0,'desc']]
      });
    });
  </script>
@endpush