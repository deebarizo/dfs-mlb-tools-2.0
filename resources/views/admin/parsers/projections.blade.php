@extends('master')

@section('content')
	
	@include('admin.parsers._heading')
	
	<div class="row">

		{!! Form::open(array('url' => 'admin/parsers/projections', 'files' => true)) !!}

			<div class="col-lg-4"> 
				<div class="form-group">
					<label for="player-pool-id">Player Pool:</label>
					<select name="player-pool-id" class="form-control">
						@foreach ($playerPools as $playerPool)
								<option value="{{ $playerPool->id }}">{{ $playerPool->site}}, {{ $playerPool->time_period }}, {{ $playerPool->date }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="col-lg-12"> 
				<div class="form-group">
					{!! Form::label('razzball-pitchers-csv', 'Razzball Pitchers CSV:') !!}
					{!! Form::file('razzball-pitchers-csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				<div class="form-group">
					{!! Form::label('razzball-hitters-csv', 'Razzball Hitters CSV:') !!}
					{!! Form::file('razzball-hitters-csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				<div class="form-group">
					{!! Form::label('bat-csv', 'BAT CSV:') !!}
					{!! Form::file('bat-csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12" style="margin-top: 15px"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop