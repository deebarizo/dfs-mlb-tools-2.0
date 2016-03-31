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



		{!!	Form::close() !!}

	</div>
@stop