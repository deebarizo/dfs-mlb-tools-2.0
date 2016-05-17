@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>$h2Tag</h2>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<form class="form-inline" style="margin: 0 0 10px 0">

				<label>Teams</label>
				<select class="form-control team-filter" style="width: 10%; margin-right: 20px">
				  	<option value="All">All</option>
				  	@foreach ($teams as $team)
					  	<option value="{{ $team }}">{{ $team }}</option>
				  	@endforeach
				</select>	

				<label>Positions</label>
				<select class="form-control position-filter" style="width: 10%; margin-right: 20px">
				  	<option value="All">All</option>
				  	<option value="SP">SP</option>
				  	<option value="Hitters">Hitters</option>
				  	<option value="C">C</option>
				  	<option value="1B">1B</option>
				  	<option value="2B">2B</option>
				  	<option value="3B">3B</option>
				  	<option value="SS">SS</option>
				  	<option value="OF">OF</option>
				</select>

				<label>Salary</label>
				<input class="salary-input form-control" type="number" value="100000" style="width: 10%">
				<input class="form-control" type="radio" name="salary-toggle" id="greater-than" value="greater-than">>=
				<input class="form-control" type="radio" name="salary-toggle" id="less-than" value="less-than" checked="checked"><=				
				<input style="width: 10%; margin-right: 20px; outline: none; margin-left: 5px" class="salary-reset btn btn-default" name="salary-reset" value="Salary Reset">

			</form>
		</div>

		<div class="col-lg-12">
			<table id="player-pool" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Team</th>
						<th>Opp</th>
						<th>Pos</th>
						<th>bLu</th>
						<th>%St</th>
						<th>rLu</th>
						<th>bPts</th>
						<th>rPts</th>
						<th>ruPts</th>
						<th>Sal</th>
						<th>bVr</th>
						<th>rVr</th>
						<th>ruVr</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($dkPlayers as $dkPlayer)
					    <tr data-dk-player-id="{{ $dkPlayer->id }}" 
					    	data-player-pool-id="{{ $dkPlayer->player_pool_id }}"
					    	data-player-id="{{ $dkPlayer->player_id }}"
					    	data-team-name-dk="{{ $dkPlayer->team_name_dk }}"
					    	data-position="{{ $dkPlayer->position }}"
					    	data-salary="{{ $dkPlayer->salary }}"
					    	data-name="{{ $dkPlayer->name_dk }}"
					    	class="player-row">
					    	<td>{{ $dkPlayer->name_dk }}</td>
					    	<td>{{ $dkPlayer->team_name_dk }}</td>
					    	<td>{{ $dkPlayer->opp_team_name_dk }}</td>
					    	<td>{{ $dkPlayer->position }}</td>
					    	<td>{{ $dkPlayer->lineup_bat }}</td>
					    	<td>{{ $dkPlayer->percent_start_razzball }}</td>
					    	<td>{{ $dkPlayer->lineup_razzball }}</td>
					    	<td>{{ $dkPlayer->fpts_bat }}</td>
					    	<td>{{ $dkPlayer->fpts_razzball }}</td>
					    	<td>{{ $dkPlayer->upside_fpts_razzball }}</td>
					    	<td>{{ $dkPlayer->salary }}</td>
					    	<td>{{ numFormat($dkPlayer->fpts_bat / ($dkPlayer->salary / 1000), 2) }}</td>
					    	<td>{{ numFormat($dkPlayer->fpts_bat / ($dkPlayer->salary / 1000), 2) }}</td>
					    	<td>{{ numFormat($dkPlayer->fpts_bat / ($dkPlayer->salary / 1000), 2) }}</td>

					    </tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script src="/js/player_pool.js"></script>
@stop