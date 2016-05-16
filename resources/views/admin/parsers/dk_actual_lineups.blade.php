@extends('master')

@section('content')

	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'admin/parsers/dk_actual_lineups', 'files' => true)) !!}

			<div class="col-lg-4"> 
				<div class="form-group">
					<label for="player-pool-id">Player Pool:</label>
					<select name="player-pool-id" class="form-control">
						@if (count($playerPools) > 0)
							@foreach ($playerPools as $playerPool)
								<option value="{{ $playerPool->id }}">{{ $playerPool->site}}, {{ $playerPool->time_period }}, {{ $playerPool->date }}</option>
							@endforeach
						@else
							<option value="">All player pools have been parsed.</option>
						@endif
					</select>
				</div>
			</div>

			<div class="col-lg-12"> 
				<div class="form-group">
					{!! Form::label('csv', 'CSV:') !!}
					{!! Form::file('csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12" style="margin-top: 15px"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop