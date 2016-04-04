@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Scrapers - DK Salaries</h2>

			@if (count($errors) > 0)
			    <div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    	
			    	<p>Please try again.</p>

			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif

			@if(Session::has('message'))
				<div class="alert alert-info fade in success-message" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					{!! Session::get('message') !!}
			    </div>
			@endif
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