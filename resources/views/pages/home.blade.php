@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Dailies</h2>

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
							<td><a href="/daily/{{ $playerPool->date }}/{{ $playerPool->time_period_in_url }}/{{ $playerPool->site_in_url }}">Daily</a> | <a href="/lineups/{{ $playerPool->date }}/{{ $playerPool->time_period_in_url }}/{{ $playerPool->site_in_url }}">Lineups</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop