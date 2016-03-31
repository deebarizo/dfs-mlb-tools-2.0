@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Scrapers - DK Salaries</h2>
		</div>
	</div>
	<div class="row">

		{!! Form::open(array('url' => 'scrapers/dk_salaries', 'files' => true)) !!}

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('date', 'Date:') !!}
					{!! Form::text('date', setTodayDate(), ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-3 col-lg-offset-1"> 
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