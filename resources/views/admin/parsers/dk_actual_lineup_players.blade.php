@extends('master')

@section('content')

	@include('_form_heading')

	<div class="row">

		<div class="col-lg-12"> 
			<p><strong>Number of Unparsed Dk Actual Lineups: </strong>{{ $numOfUnparsedDkActualLineups }}</p>
		</div>

		{!! Form::open(array('url' => 'admin/parsers/dk_actual_lineup_players')) !!}

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop