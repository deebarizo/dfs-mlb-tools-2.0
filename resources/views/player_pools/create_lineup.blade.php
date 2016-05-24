@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $h2Tag }}</h2>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-6" style="margin-top: 15px">
			<h4>Players in Player Pool</h4>

			<table id="dk-players" style="font-size: 85%" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Pos</th>					
						<th>Name</th>
						<th>Team</th>
						<th>Opp</th>
						<th>Sal</th>
						<th>Fpts</th>
						<th>Add</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($dkPlayers as $dkPlayer)
						<tr class="player-row" 
							data-dk-player-id="{{ $dkPlayer->id }}" 
							data-position="{{ $dkPlayer->position }}"
							data-name="{{ $dkPlayer->player->name_dk }}"
							data-team-id="{{ $dkPlayer->team_id }}"
							data-team-name-dk="{{ $dkPlayer->team->name_dk }}"
							data-opp-team-id="{{ $dkPlayer->opp_team_id }}"
							data-opp-team-name-dk="{{ $dkPlayer->opp_team->name_dk }}"
							data-salary="{{ $dkPlayer->salary }}">
							<td>{{ $dkPlayer->position }}</td>
							<td>{{ $dkPlayer->player->name_dk }}</td>
							<td>{{ $dkPlayer->team->name_dk }}</td>
							<td>{{ $dkPlayer->opp_team->name_dk }}</td>
							<td>{{ $dkPlayer->salary }}</td>
							<td>0.00</td>
							<td class="dk-player-update"><a class="update-dk-player-link" href=""><div class="circle-plus-icon"><span class="glyphicon glyphicon-plus"></span></div><?php if (strpos($dkPlayer->position, '/') !== false) echo '<a class="update-dk-player-link" href=""><div class="circle-plus-icon second-position"><span class="glyphicon glyphicon-plus"></span>'; ?></td>
						</tr>		
					@endforeach	
				</tbody>
			</table>
		</div>

		<div class="col-lg-6">
			<h4 class="lineup">Lineup</h4>

			<table id="lineup" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Pos</th>					
						<th>Name</th>
						<th>Team</th>
						<th>Opp</th>
						<th>Salary</th>
						<th>Fpts</th>
						<th>Remove</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i = 0; $i < 9; $i++) { ?>
						<tr class="lineup-row lineup-player-row">
							<td style="width: 5%" class="lineup-player-position">SP</td>
							<td style="width: 25%" class="lineup-player-name">Bob Jones</td>
							<td style="width: 10%" class="lineup-player-team">LAD</td>
							<td style="width: 10%" class="lineup-player-opp">Sea</td>
							<td style="width: 15%" class="lineup-player-salary">9000</td>
							<td style="width: 15%" class="lineup-player-bat-fpts">22.3</td>
							<td style="width: 10%"><a href="" class="remove-lineup-player-link"></a></td>
						</tr>
					<?php } ?>
					<tr class="lineup-row">
						<td colspan="4">
							<div class="input-group inline" style="margin: 0 auto">
						  		<span class="input-group-addon">$</span>
						  		<input style="width: 75px; margin-right: 30px" type="text" class="form-control lineup-buy-in-amount" value=""> 

						  		<div style="display: inline-block; margin-top: 8px"><strong>Avg/Player: </strong> $<span class="avg-salary-per-player-left"></span></div>
							</div>
						</td>
						<td><span class="lineup-salary-total"></span></td>
						<td><span class="lineup-bat-fpts-total"></span></td>
						<td></td>
					</tr>	
				</tbody>
			</table>

			<button style="width: 128px" class="btn btn-primary pull-right submit-lineup" type="submit">Submit Lineup</button>
		</div>
	</div>

	<script type="text/javascript">
		
		var dkPlayersTable = $('#dk-players').DataTable({
			
			"scrollY": "600px",
			"paging": false,
			"order": [[4, "desc"]]
		});

		$('#dk-players_filter').hide();

		var lineupTable = $('#lineup').DataTable({
			
			"scrollY": "600px",
			"paging": false,
			"order": [[4, "desc"]]
		});

		$('#dk-players_filter').hide();

	</script>
@stop