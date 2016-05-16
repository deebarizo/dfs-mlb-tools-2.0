@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'admin/parsers/dk_players', 'files' => true)) !!}

			<div class="col-lg-2"> 
				<div class="form-group">
					<label for="site">Site:</label>
					<select name="site" class="form-control">
					  	<option value="DK">DK</option>
					</select>
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					<label for="time-period">Time Period:</label>
					<select name="time-period" class="form-control">
					  	<option value="All Day">All Day</option>
					  	<option value="Early">Early</option>
					  	<option value="Late">Late</option>
					</select>
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('date', 'Date (Today):') !!}
					{!! Form::text('date', setTodayDate(), ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('csv', 'CSV:') !!}
					{!! Form::file('csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop