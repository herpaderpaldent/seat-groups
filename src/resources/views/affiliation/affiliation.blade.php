<h3>Available for whom</h3>
<p>Select the corporation to which the SeAT Group is bound.</p>


<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Corporations</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{route('seatgroups.add.corp.affiliation', $id)}}">
      {{csrf_field()}}
      <input name="_method3" type="hidden" value="PATCH">
      <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">

      <div class="form-group">
        <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
        <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>

          <option value="1337">All Corporation</option>

          @foreach($all_corporations as $corporation)
            @if(!in_array($corporation->corporation_id,$seatgroup->corporation->pluck('corporation_id')->toArray()))
              <option value="{{ $corporation->corporation_id }}">
                {{ $corporation->name }}
              </option>
            @endif
          @endforeach

        </select>
      </div>
      <div class="row">
        <div class="col-md-6"></div>
        <div class="form-group col-md-12">
          <button type="submit" class="btn btn-success btn-block">Add Corporation</button>
        </div>
      </div>
    </form>
    <hr>
    <table class="table table-hover table-condensed">
      <tbody>

      <tr>
        <th colspan="4" class="text-center">Current Corporations</th>
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
  </div>
</div>

<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li {{--class="active"--}}><a href="#corporation-filter" data-toggle="tab">Corporation Filter</a></li>
    <li class="active"><a href="#title-filter" data-toggle="tab">Corporation Title Filter</a></li>
  </ul>
  <div class="tab-content">

    <div class="tab-pane {{--active--}}" id="corporation-filter">
      <form method="post" action="{{route('seatgroups.add.corp.affiliation', $id)}}">
        {{csrf_field()}}
        <input name="_method3" type="hidden" value="PATCH">
        <input type="hidden" name="seatgroup_id" value="{{ $seatgroup->id }}">
        <div class="form-group">
          <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
          <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>

            <option value="1337">All Corporation</option>

            @foreach($all_corporations as $corporation)
              @if(!in_array($corporation->corporation_id,$seatgroup->corporation->pluck('corporation_id')->toArray()))
                <option value="{{ $corporation->corporation_id }}">
                  {{ $corporation->name }}
                </option>
              @endif
            @endforeach

          </select>
        </div>
        <div class="row">
          <div class="col-md-6"></div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-block">Add Corporation</button>
          </div>
        </div>
      </form>

    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane active" id="title-filter">
      @include('seatgroups::affiliation.partials.corporation-title-form')
    </div>

    @include('seatgroups::affiliation.partials.current-affiliations')
    <!-- /.tab-pane -->
  </div>

  {{$seatgroup->corporationTitles}}



  <!-- /.tab-content -->
</div>