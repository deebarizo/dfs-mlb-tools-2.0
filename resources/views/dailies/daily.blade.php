@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Daily - DK MLB - {{ $contestName }}</h2>

			<hr>
		</div>

		<div class="col-lg-12">
			<table id="daily" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Pos</th>
						<th>Lu</th>
						<th>Team</th>
						<th>Opp</th>
						<th>Sal</th>
						<th>bFpts</th>
						<th>bVr</th>
						<th>rFpts</th>
						<th>rVr</th>
						<th>brFpts</th>
						<th>brVr</th>
						<th>Own</th>					
					</tr>
				</thead>
				<tbody>
					@foreach ($players as $player)
					    <tr class="player-row">
					    	<td>{{ $player->name }}</a></td>
					    	<td>{{ $player->abbr_dk }}</td>
					    	<td>{{ $player->position }}</td>
					    	<td>{{ $player->lineup }}</td>
					    	<td>{{ $player->opp }}</td>
					    	<td>{{ $player->bat_fpts }}</td>
					    	<td>{{ $player->salary }}</td>
					    	<td>{{ $player->bat_vr }}</td>
					    </tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop