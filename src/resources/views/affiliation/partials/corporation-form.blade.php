<form method="post" action="{{route('affiliation.add.corp.affiliation')}}">
  {{csrf_field()}}
  <input name="_method3" type="hidden" value="PATCH">
  <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
  <div class="form-group">
    <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
    <select name="corporation_ids[]" id="seat-group-corporation-id" style="width: 100%" multiple>

      @if(!$seatgroup->all_corporations)
        <option value="-1">All Corporation</option>
        @foreach($all_corporations as $corporation)
          <option value="{{ $corporation->corporation_id }}">{{ $corporation->name }}</option>
        @endforeach
      @endif

    </select>
  </div>
  <div class="row">
    <div class="col-md-6"></div>
    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-success btn-block">Add Corporation</button>
    </div>
  </div>
</form>

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#seat-group-corporation-id").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}"
    });
  </script>

@endpush