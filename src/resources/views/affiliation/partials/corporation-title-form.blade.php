<form method="post" action="{{route('affiliation.add.corporation.title.affiliation')}}">
  {{csrf_field()}}
  <input name="_method3" type="hidden" value="PATCH">
  <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">

  <div class="form-group">
    <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
    <select name="seatgroup-corporation-id" id="seatgroup-corporation-id" style="width: 100%" >
      <option></option>
    </select>
  </div>

  <div class="form-group">
    <label for="corporation-title">{{ trans('web::seat.available_titles') }}</label>
    <select name="seatgroup-title-id" id="seatgroup-title-id" style="width: 100%">
      <option></option>
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
  <script type="application/javascript">
    function getCorporationTitle() {
      $('#seatgroup-title-id').empty();

      $.ajax({
        type: 'POST',
        url: '{{ route('affiliation.resolve.corporation.title') }}',
        data: {
          corporation_id: $('#seatgroup-corporation-id').val(),
          seatgroup_id: '{{$seatgroup->id}}'
        },
        success: function(data){
          for (var i = 0; i < data.length; i++) {
            $('#seatgroup-title-id').append($('<option></option>').attr('value', data[i].title_id).text(data[i].name));
          }
        },
        error  : function (xhr, textStatus, errorThrown) {
          console.log(xhr);
          console.log(textStatus);
          console.log(errorThrown);
        }
      });
    }

    function getCorporations() {
      $('#seatgroup-corporation-id').empty();
      $.ajax({
        type   : 'POST',
        url    : '{{ route('affiliation.get.corporation.list') }}',
        data   : {
          filter: 'corporation-tile-form',
          seatgroup_id: '{{$seatgroup->id}}'
        },
        success: function (data) {
          for (var i = 0; i < data.length; i++) {
            $('#seatgroup-corporation-id').append($('<option></option>').attr('value', data[i].corporation_id).text(data[i].name));
          }
        },
        error  : function (xhr, textStatus, errorThrown) {
          console.log(xhr);
          console.log(textStatus);
          console.log(errorThrown);
        }
      })
    }

    $("#seatgroup-corporation-id,"+ "#seatgroup-title-id").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}",
      allowClear: true
    });


    $('#seatgroup-corporation-id').change(function(){
      getCorporationTitle();
    });

    //TODO optimize this loading, so no corporation is preselected. This causes errors in network tab.
    $(document).ready(function() {
        getCorporations();
    })

  </script>
@endpush
