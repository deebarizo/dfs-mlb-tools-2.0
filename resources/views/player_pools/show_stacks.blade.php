@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $h2Tag }}</h2>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-9" style="margin-top: 15px">
			<table id="stacks" class="stacks table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Team</th>
						<th>Avg muPts</th>
						<th>Avg muVr</th>
						<th>Avg Salary</th>
						<th>Pos</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($teams as $team)
					    <tr>
					    	<td>{{ $team->name_dk }}</td>
					    	<td>{{ $team->avgMuPts }}</td>
					    	<td>{{ $team->avgMuVr }}</td>
					    	<td>{{ $team->avgSalary }}</td>
					    	<td>{{ $team->takenPositions }}</td>
					    </tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">

		var columnIndex = localStorage.getItem('columnIndex') || 1;

		var stacksTable = $('#stacks').DataTable({
			
			"scrollY": "600px",
			"paging": false,
			"order": [[columnIndex, "desc"]],
	        "aoColumns": [
	            null,
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            null
	        ]
		});

		$('#stacks_filter').hide();

		$(document).ready(function() {

			$('table.stacks thead').on('click', 'th', function() { // can't use #stacks selector because DataTables removes the selector
			  	
			  	var columnIndex = stacksTable.column(this).index();
			  	
			  	localStorage.setItem('columnIndex', columnIndex);
			});
		});



	</script>
@stop