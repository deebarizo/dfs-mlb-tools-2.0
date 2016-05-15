@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Player Pools</h2>

			<hr>

			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Date</th>
						<th>Time Period</th>
						<th>Site</th>
						<th>Buy In</th>
						<th>Links</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($playerPools as $playerPool)
						<tr>
							<td>{{ $playerPool->date }}</td>
							<td>{{ $playerPool->time_period }}</td>
							<td>{{ $playerPool->site }}</td>
							<td>{{ $playerPool->buy_in }}</td>
							<td><a href="">Player Pool</a> | <a href="">Lineups</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop