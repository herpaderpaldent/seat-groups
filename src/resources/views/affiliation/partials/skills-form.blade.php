<form method="post" action="{{route('affiliation.add.skill.affiliation')}}">
  {{csrf_field()}}
  <input name="_method3" type="hidden" value="PATCH">
  <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
  <div class="form-group">
    <label for="skills">Available Skills</label>
    <select name="skill_ids[]" id="seat-group-skill-id" style="width: 100%" multiple required>
      @foreach($all_available_skills as $skill)
        @for($i = 1; $i < 6; $i++)
          <option value="{{ $skill->typeID . '.' . $i }}">{{ $skill->typeName }}
            @switch($i)
              @case(1)
              I
              @break
              @case(2)
              II
              @break
              @case(3)
              III
              @break
              @case(4)
              IV
              @break
              @case(5)
              V
              @break
            @endswitch
          </option>
        @endfor
      @endforeach
    </select>
  </div>
  <div class="row">
    <div class="col-md-6"></div>
    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-success btn-block">Add Skill</button>
    </div>
  </div>
</form>

@push('javascript')
  <script>
      $("#seat-group-skill-id").select2({
          placeholder: "{{ trans('web::seat.select_item_add') }}"
      });
  </script>
@endpush
