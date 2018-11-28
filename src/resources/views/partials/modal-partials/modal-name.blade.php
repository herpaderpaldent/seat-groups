{!! img('auto', $row->main_character_id, 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
{{ optional($row->main_character)->name }} ({{$row->users->map(function($user) { return $user->name; })->implode(', ')}})

@if($row->pivot->is_manager === 1)
  (Manager)
@endif