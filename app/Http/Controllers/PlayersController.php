<?php namespace App\Http\Controllers;

use App\Player;
use App\Team;

use DB;

class PlayersController extends Controller {

	public function showPlayers() {

		$titleTag = 'Players | ';

		$players = DB::table('players')
						->select(DB::raw('players.id,
								 		  players.name_dk, 
								 		  players.name_razzball, 
								 		  players.name_bat, 
								 		  players.team_id,
								 		  teams.name_dk as team_name'))
						->join('teams', 'teams.id', '=', 'players.team_id')
						->orderBy('players.name_dk', 'asc')
						->get();

		return view('pages/players', compact('titleTag', 'players'));
	}

	public function editPlayer($id) {

		$player = Player::find($id);
		$teams = Team::orderBy('name_dk', 'asc')->get();

		$titleTag = 'Edit Player ('.$player->name_dk.') | ';
		$h2Tag = 'Edit Player ('.$player->name_dk.')';

		return view('players/edit', compact('titleTag', 'h2Tag', 'player', 'teams'));
	}

	public function updatePlayer($id) {



		
	}

}