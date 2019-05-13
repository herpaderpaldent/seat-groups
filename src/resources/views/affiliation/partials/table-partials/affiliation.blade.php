@if(isset($row['alliance_id']))
  {!! img('alliance', $row['alliance_id'], 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  {{ $row['name'] }}

@elseif(isset($row['all_corporations']))
  All Corporations <span data-toggle="tooltip" title="" class="badge bg-orange-active" data-original-title="Dangerous: purged members are not removed from SeAT-Group"><i class="fa fa-warning"></i></span>

@elseif(isset($row['skill_id']))
  {!! img('type', $row['skill_id'], 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  {{ $row['name'] }}
  @switch($row['skill_level'])
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

@else
  {!! img('corporation', $row['corporation_id'], 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  {{ $row['name'] }}

  @if(isset($row['corporation_title']))
    ({{$row['corporation_title']['name']}})
  @endif

@endif