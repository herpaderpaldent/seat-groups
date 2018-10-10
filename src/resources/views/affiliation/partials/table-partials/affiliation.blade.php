@if(isset($row['alliance_id']))
  {!! img('alliance', $row['alliance_id'], 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  {{ $row['name'] }}

@elseif(isset($row['all_corporations']))
  All Corporations <span data-toggle="tooltip" title="" class="badge bg-orange-active" data-original-title="Dangerous: purged members are not removed from SeAT-Group"><i class="fa fa-warning"></i></span>

@else
  {!! img('corporation', $row['corporation_id'], 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  {{ $row['name'] }}

  @if(isset($row['corporation_title']))
    ({{$row['corporation_title']['name']}})
  @endif

@endif