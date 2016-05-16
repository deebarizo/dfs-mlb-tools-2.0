@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'players/'.$player->id.'/update' )) !!}

			<div class="col-lg-3"> 
				<div class="form-group">
					{!! Form::label('name_dk', 'Name (DK):') !!}
					{!! Form::text('name_dk', $player->name_dk, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-3"> 
				<div class="form-group">
					{!! Form::label('name_razzball', 'Name (Razzball):') !!}
					{!! Form::text('name_razzball', $player->name_dk, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-3"> 
				<div class="form-group">
					{!! Form::label('name_bat', 'Name (BAT):') !!}
					{!! Form::text('name_bat', $player->name_bat, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					<label for="team-id">Team:</label>
					<select name="team-id" class="form-control">
						@foreach ($teams as $team)
						  	<option value="{{ $team->id }}" <?php echo ($team->id === $player->team_id ? 'selected' : ''); ?> >{{ $team->name_dk }}</option>
						@endforeach
					</select>
				</div>
			</div>		

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop