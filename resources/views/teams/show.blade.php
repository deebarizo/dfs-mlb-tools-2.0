@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $team->name_dk }} - Teams</h2>
		</div>
	</div>

	<div class="row">
		<ul>
			@foreach ($team->players as $player)
				<li>{{ $player->name_dk }}</li>
			@endforeach
		</ul>
	</div>
@stop