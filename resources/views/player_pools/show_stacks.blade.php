@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $h2Tag }}</h2>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-6" style="margin-top: 15px">
			<table id="stacks" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Team</th>
						<th>Avg muPts</th>
						<th>Pos</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($teams as $team)
					    <tr>
					    	<td>{{ $team->name_dk }}</td>
					    	<td>{{ $team->avgMuPts }}</td>
					    	<td>{{ $team->takenPositions }}</td>
					    </tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">

		var stacksTable = $('#stacks').DataTable({
			
			"scrollY": "600px",
			"paging": false,
			"order": [[1, "desc"]],
		});

		$('#stacks_filter').hide();

	</script>
@stop