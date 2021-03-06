@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $h2Tag }}</h2>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-6">
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
						<?php if (strpos($dkPlayer->position, 'P') !== false) { $fpts = numFormat(($dkPlayer->fpts_razzball + $dkPlayer->fpts_bat) / 2, 2); } else { $fpts = numFormat(($dkPlayer->fpts_razzball + $dkPlayer->fpts_bat) / 2, 2); } ?>
						<tr class="dk-player" 
							data-id="{{ $dkPlayer->id }}" 
							data-player-pool-id="{{ $dkPlayer->player_pool_id }}"
							data-position="{{ $dkPlayer->position }}"
							data-name-dk="{{ $dkPlayer->player->name_dk }}"
							data-team-id="{{ $dkPlayer->team_id }}"
							data-team-name-dk="{{ $dkPlayer->team->name_dk }}"
							data-opp-team-id="{{ $dkPlayer->opp_team_id }}"
							data-opp-team-name-dk="{{ $dkPlayer->opp_team->name_dk }}"
							data-salary="{{ $dkPlayer->salary }}"
							data-fpts="{{ $fpts }}">
							<td>{{ $dkPlayer->position }}</td>
							<td>{{ $dkPlayer->player->name_dk }}</td>
							<td>{{ $dkPlayer->team->name_dk }}</td>
							<td>{{ $dkPlayer->opp_team->name_dk }}</td>
							<td>{{ $dkPlayer->salary }}</td>
							<td>{{ $fpts }}</td>
							<td class="dk-player-add"><a class="add-dk-player-link" href="#"><div class="circle-plus-icon"><span class="glyphicon glyphicon-plus"></span></div></a><?php if (strpos($dkPlayer->position, '/') !== false) { echo '<a class="add-dk-player-link second-position" href=""><div class="circle-plus-icon second-position"><span class="glyphicon glyphicon-plus"></span></div></a>'; } ?></td>
						</tr>		
					@endforeach	
				</tbody>
			</table>
		</div>

		<div class="col-lg-6">
			<h4 class="lineup">Lineup</h4>

			<div class="lineup alert alert-danger fade in" role="alert" style="display: none">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

				<span class="lineup-error"></span>
		    </div>

			<table id="lineup" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Pos</th>					
						<th>Name</th>
						<th>Team</th>
						<th>Opp</th>
						<th>Sal</th>
						<th>Fpts</th>
						<th>Rem</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($positions as $position)
						<tr class="dk-lineup dk-lineup-player"
							data-position="{{ $position }}"
							data-player-pool-id=""
							data-dk-player-id=""
							data-dk-player-fpts="">
							<td style="width: 5%" class="dk-lineup-player-position">{{ $position }}</td>
							<td style="width: 40%" class="dk-lineup-player-name-dk"></td>
							<td style="width: 8%" class="dk-lineup-player-team-name-dk"></td>
							<td style="width: 8%" class="dk-lineup-player-opp-team-name-dk"></td>
							<td style="width: 12%" class="dk-lineup-player-salary"></td>
							<td style="width: 12%" class="dk-lineup-player-fpts"></td>
							<td style="width: 5%"><a href="" class="remove-dk-lineup-player-link"><div class="circle-minus-icon"><span class="glyphicon glyphicon-minus"></span></div></a></td>
						</tr>
					@endforeach
					<tr class="dk-lineup lineup-metadata">
						<td colspan="4">
							<div class="input-group inline" style="margin: 0 auto">
						  		<span class="input-group-addon">$</span>
						  		<input style="width: 55px; margin-right: 10px" type="text" class="form-control lineup-buy-in-amount" value="3"> 

						  		<div style="display: inline-block; margin-top: 8px; margin-right: 10px"><strong>Avg Left: </strong> <span class="avg-salary-per-dk-lineup-player-left">5000</span></div>

						  		<div style="display: inline-block; margin-top: 8px"><strong>Total Left: </strong> <span class="salary-left">50000</span></div>
							</div>
						</td>
						<td style="padding-top: 13px"><span class="dk-lineup-salary-total">0</span></td>
						<td style="padding-top: 13px"><span class="dk-lineup-fpts-total">0.00</span></td>
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
	</script>

	<script type="text/javascript">

		$(document).ready(function() {
		
			$('a.add-dk-player-link').on('click', function(e) {

				e.preventDefault();

				var trDkPlayer = $(this).closest('tr.dk-player');

				if (trDkPlayer.hasClass('strikethrough') === false) {

					var isSecondPosition = false;

					if ($(this).hasClass('second-position')) {

						isSecondPosition = true;
					}
				 
					if (isSecondPosition) {

						var position = trDkPlayer.attr('data-position').replace(/(\w+)(\/)(\w+)/, '$3');

					} else {

						var position = trDkPlayer.attr('data-position').replace(/(\w+)(\/)(\w+)/, '$1');
					}

					switch(position) {

					    case 'SP':
					        var positionCount = 2;
					        break;
					    
					    case 'OF':
					        var positionCount = 3;
					        break;
					    
					    default:
					        var positionCount = 1;
					}

					var positionFull = true;

					for (var i = 0; i < positionCount; i++) { 
						
						var lineupPlayerNameDk = $('tr.dk-lineup-player[data-position="'+position+'"]').eq(i).find('td.dk-lineup-player-name-dk');

						if (lineupPlayerNameDk.text().trim() == '') {

							positionFull = false;

							break;
						}
					}

					if (positionFull) {

						alert('The "'+position+'" position is already full.');
					}
				}
			});

			$('button.submit-lineup').on('click', function(e) {

				var isEmptyLineup = true;

				$('td.dk-lineup-player-name-dk').each(function() {

					if ($(this).text() != '') {

						isEmptyLineup = false;

						return;
					}
				});

				if (isEmptyLineup) {

					alert('You cannot submit an empty lineup.');
				}
			});
		});

	</script>

	<script src="/js/jasmine/src/lineups/create.js"></script>


@stop