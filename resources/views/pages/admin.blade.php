@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Admin</h2>

			<hr>

			<h4>Parsers</h4>

			<ul>
				<li><a href="/admin/parsers/dk_players">DK Players</a></li>
				<li><a href="/admin/parsers/dk_actual_lineups">DK Actual Lineups</a></li>
				<li><a href="/admin/parsers/dk_actual_lineup_players">DK Actual Lineup Players</a></li>
				<li><a href="/admin/parsers/dk_ownerships">DK Ownerships</a></li>
				<li><a href="/admin/parsers/projections">Projections</a></li>
			</ul>
		</div>
	</div>
@stop