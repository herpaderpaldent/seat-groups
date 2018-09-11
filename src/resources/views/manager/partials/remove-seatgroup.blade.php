@foreach($seatgroup->children as $row)
<tr class="text-warning">
  <td>
    {{$row->name}} <span class="badge bg-yellow">SeAT Group</span>
    {{--{{ $row->children->map(function($user) { return $user->name; })->implode(', ') }}--}}
  </td>
  <td>

    <form role="form" action="{{ route('seatgroupuser.remove.manager') }}" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="seatgroup_id" value="{{ $id }}">
      <input type="hidden" name="group_id" value="">
      <input type="hidden" name="children_id" value="{{ $row->id }}">
      <button type="submit" class="btn btn-danger btn-xs pull-right">
        {{ trans('web::seat.remove') }}
      </button>
    </form>

  </td>
</tr>

@endforeach
