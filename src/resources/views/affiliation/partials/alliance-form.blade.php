<form method="post" action="{{route('affiliation.add.alliance.affiliation')}}">
  {{csrf_field()}}
  <input name="_method3" type="hidden" value="PATCH">
  <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
  <div class="form-group">
    <label for="alliances">{{ trans('web::seat.available_corporations') }}</label>
    <select name="alliance_ids[]" id="seat-group-alliance-id" style="width: 100%" multiple required>

      @if(!$seatgroup->all_corporations)
        @foreach($all_available_alliances as $alliance)
          <option value="{{ $alliance->alliance_id }}">{{ $alliance->name }}</option>
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
    $("#seat-group-alliance-id").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}"
    });
  </script>

@endpush