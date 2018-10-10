@if(isset($row['all_corporations']))
  <form role="form" action="{{ route('affiliation.remove.all.corporation') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="seatgroup_id" value="{{ $row['seatgroup_id'] }}">
    <button type="submit" class="btn btn-danger btn-xs pull-right">
      {{ trans('web::seat.remove') }}
    </button>
  </form>

@elseif(isset($row['corporation_title']))
  <form role="form" action="{{ route('affiliation.remove.corporation.title') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="corporation_id" value="{{ $row['corporation_id'] }}">
    <input type="hidden" name="seatgroup_id" value="{{ $row['seatgroup_id'] }}">
    <input type="hidden" name="title_id" value="{{ $row['corporation_title']['title_id'] }}">
    <button type="submit" class="btn btn-danger btn-xs pull-right">
      {{ trans('web::seat.remove') }}
    </button>
  </form>

@elseif(isset($row['alliance_id']))
  <form role="form" action="{{ route('affiliation.remove.alliance.affiliation') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="alliance_ids[]" value="{{ $row['alliance_id'] }}">
    <input type="hidden" name="seatgroup_id" value="{{ $row['seatgroup_id'] }}">
    <button type="submit" class="btn btn-danger btn-xs pull-right">
      {{ trans('web::seat.remove') }}
    </button>
  </form>


@else
  <form role="form" action="{{ route('affiliation.remove.corporation') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="seatgroup_id" value="{{ $row['seatgroup_id'] }}">
    <input type="hidden" name="corporation_id" value="{{ $row['corporation_id'] }}">
    <button type="submit" class="btn btn-danger btn-xs pull-right">
      {{ trans('web::seat.remove') }}
    </button>
  </form>

@endif

