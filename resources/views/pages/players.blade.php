@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Players</h2>

			<hr>

			<table id="players" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name (DK)</th>
						<th>Name (Razzball)</th>
						<th>Name (BAT)</th>
						<th>Team</th>
						<th>Edit</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($players as $player)
						<tr>
							<td>{{ $player->name_dk }}</td>
							<td>{{ $player->name_razzball }}</td>
							<td>{{ $player->name_bat }}</td>
							<td>{{ $player->team_name }}</td>
							<td><a href="/players/{{ $player->id }}/edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">

		$('#players').dataTable({

			"paging": true,
			"order": [[0, "asc"]]
		});

	</script>
@stop