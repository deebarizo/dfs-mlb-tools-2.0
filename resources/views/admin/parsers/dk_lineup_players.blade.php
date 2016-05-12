@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Parsers - DK Lineups</h2>

			<hr>

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

			@if (Session::has('message'))
				<div class="alert <?php echo (Session::get('message') === 'Success!' ? 'alert-info' : 'alert-danger'); ?> fade in success-message" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

					{!! Session::get('message') !!}
			    </div>
			@endif
		</div>
	</div>
	<div class="row">

		{!! Form::open(array('url' => 'admin/parsers/dk_lineup_players', 'files' => true)) !!}

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop