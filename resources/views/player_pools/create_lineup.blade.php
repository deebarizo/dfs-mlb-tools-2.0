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

			<table id="dk-players" class="stacks table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Pos</th>					
						<th>Name</th>
						<th>Team</th>
						<th>Opp</th>
						<th>Salary</th>
						<th>Update</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($dkPlayers as $dkPlayer)
						<tr class="player-row" 
							data-dk-player-id="{{ $dkPlayer->id }}" 
							data-position="{{ $dkPlayer->position }}"
							data-name="{{ $dkPlayer->player->name_dk }}"
							data-team-id="{{ $dkPlayer->team_id }}" 
							data-opp-team-id="{{ $dkPlayer->opp_team_id }}"
							data-salary="{{ $dkPlayer->salary }}">
							<td>{{ $dkPlayer->position }}</td>
							<td>{{ $dkPlayer->player->name_dk }}</td>
							<td>{{ $dkPlayer->team->name_dk }}</td>
							<td>{{ $dkPlayer->opp_team->name_dk }}</td>
							<td>{{ $dkPlayer->salary }}</td>
							<td class="dk-player-update"><a class="update-dk-player-link" href=""><div class="circle-plus-icon"><span class="glyphicon glyphicon-plus"></span></div></td>
						</tr>		
					@endforeach	
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">
		
		var dkPlayersTable = $('#dk-players').DataTable({
			
			"scrollY": "600px",
			"paging": false,
			"order": [[4, "desc"]]
		});

		$('#dk-players_filter').hide();

	</script>
@stop