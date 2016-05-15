@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Parsers - Razzball Projections</h2>

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

		{!! Form::open(array('url' => 'admin/parsers/razzball_projections', 'files' => true)) !!}

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
					{!! Form::label('pitchers-csv', 'Pitchers CSV:') !!}
					{!! Form::file('pitchers-csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				<div class="form-group">
					{!! Form::label('hitters-csv', 'Hitters CSV:') !!}
					{!! Form::file('hitters-csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12" style="margin-top: 15px"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop