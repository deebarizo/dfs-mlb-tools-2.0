@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12 wide">
			<h2>{{ $h2Tag }}</h2>

			<h4><a target="_blank" href="/player_pools/{{ $dkPlayers[0]->player_pool_id }}/stacks">Stacks</a> | <a target="_blank" href="/player_pools/{{ $dkPlayers[0]->player_pool_id }}/lineups/create">Create Lineup</a></h4>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12 wide" style="margin: 5px 0 15px 0">
			<form class="form-inline" style="margin: 0 0 10px -200px">

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

		<div class="col-lg-12 wide">
			<table id="player-pool" style="font-size: 85%" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Team</th>
						<th>Opp</th>
						<th>OppSp</th>
						<th>Pos</th>
						<th>bLu</th>
						<th>%St</th>
						<th>rLu</th>
						<th>bPts</th>
						<th>bVr</th>
						<th>rPts</th>
						<th>rVr</th>
						<th>ruPts</th>
						<th>ruVr</th>
						<th>mPts</th>
						<th>mVr</th>
						<th>muPts</th>
						<th>muVr</th>
						<th>Sal</th>
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
					    	<td>{{ $dkPlayer->opp_pitcher }}</td>
					    	<td>{{ $dkPlayer->position }}</td>
					    	<td>{{ $dkPlayer->lineup_bat }}</td>
					    	<td>{{ $dkPlayer->percent_start_razzball }}</td>
					    	<td>{{ $dkPlayer->lineup_razzball }}</td>
					    	<td>{{ $dkPlayer->fpts_bat }}</td>
					    	<td>{{ $dkPlayer->bVr }}</td>
					    	<td>{{ $dkPlayer->fpts_razzball }}</td>
					    	<td>{{ $dkPlayer->rVr }}</td>
					    	<td>{{ $dkPlayer->upside_fpts_razzball }}</td>
					    	<td>{{ $dkPlayer->ruVr }}</td>
					    	<td>{{ $dkPlayer->mPts }}</td>
					    	<td>{{ $dkPlayer->mVr }}</td>
					    	<td>{{ $dkPlayer->muPts }}</td>
					    	<td>{{ $dkPlayer->muVr }}</td>
					    	<td>{{ $dkPlayer->salary }}</td>
					    </tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">

		var playerPoolTable = $('#player-pool').DataTable({
			
			"scrollY": "600px",
			"paging": false,
			"order": [[16, "desc"]],
	        "aoColumns": [
	            null,
	            null,
	            null,
	            null,
	            null,
	            null,
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] }
	        ]
		});

		$('#player-pool_filter').hide();

	</script>

	<script src="/js/player_pools/index.js"></script>
@stop