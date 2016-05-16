<?php namespace App\Http\Controllers;

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

}