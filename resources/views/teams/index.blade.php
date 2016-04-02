@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Teams</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name (DraftKings)</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($teams as $team)
						<tr>
							<td>{{ $team->name_dk }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop