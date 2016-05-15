<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DkPlayer extends Model {

    protected $fillable = [

        'ownership', 
        'ownership_of_first_position', 
        'ownership_of_second_position', 
        'ownerships_parsed',
        'lineup_razzball',
        'percent_start_razzball',
        'fpts_razzball',
        'upside_fpts_razzball'
    ];

    public function team() {

    	return $this->belongsTo(Team::class);
    }

    public function opp_team() {

    	return $this->belongsTo(Team::class);
    }

    public function player_pool() {

    	return $this->belongsTo(PlayerPool::class);
    }

    public function player() {

    	return $this->belongsTo(Player::class);
    }

    public function dk_actual_lineup_players() {

        return $this->hasMany(DkActualLineupPlayer::class);
    }

}
