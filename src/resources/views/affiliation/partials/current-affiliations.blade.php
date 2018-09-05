<table id="current_affiliation_table" class="display table-condensed table-hover" style="width:100%">
  <tbody>

  <tr>
    <th colspan="4" class="text-center">Current Affiliations</th>
  </tr>
  @if($seatgroup->all_corporations)
    <tr>
      <td> All Corporations <span data-toggle="tooltip" title="" class="badge bg-orange-active" data-original-title="Dangerous: purged members are not removed from SeAT-Group"><i class="fa fa-warning"></i></span></td>
      <td>
        <form role="form" action="{{ route('seatgroups.remove.corp.affiliation', ['seatgroup_id' => $seatgroup->id, 'corporation_id' => $corporation->corporation_id]) }}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
          <input type="hidden" name="corporation_id" value="1337">
          <button type="submit" class="btn btn-danger btn-xs pull-right">
            {{ trans('web::seat.remove') }}
          </button>
        </form>
      </td>
    </tr>
  @endif
  @foreach($corporations as $corporation)

    <tr>
      <td>
        {!! img('auto', $corporation->corporation_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $corporation->name }}

      </td>
      <td>
        <form role="form" action="{{ route('seatgroups.remove.corp.affiliation', ['seatgroup_id' => $seatgroup->id, 'corporation_id' => $corporation->corporation_id]) }}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
          <input type="hidden" name="corporation_id" value="{{ $corporation->corporation_id }}">
          <button type="submit" class="btn btn-danger btn-xs pull-right">
            {{ trans('web::seat.remove') }}
          </button>
        </form>
      </td>
    </tr>

  @endforeach
  </tbody>
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
          {data: 'name'},
          {data: 'corporation_id'},
        ],
        order: [[0,'desc']]
      });
    });
  </script>
@endpush


