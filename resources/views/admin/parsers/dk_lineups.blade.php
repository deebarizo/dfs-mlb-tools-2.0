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

		{!! Form::open(array('url' => 'admin/parsers/dk_lineups', 'files' => true)) !!}

			<div class="col-lg-4"> 
				<div class="form-group">
					<label for="player-pool-id">Player Pool:</label>
					<select name="player-pool-id" class="form-control">
						@if (count($playerPools) > 0)
							@foreach ($playerPools as $playerPool)
								<option value="{{ $playerPool->id }}">{{ $playerPool->site}}, {{ $playerPool->time_period }}, {{ $playerPool->date }}</option>
							@endforeach
						@else
							<option value="">All the player pools have been parsed for DK lineups.</option>
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