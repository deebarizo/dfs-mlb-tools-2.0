<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerPool extends Model {
    
    public function dk_players() {

    	return $this->hasMany(DkPlayer::class);
    }

    public function actual_lineups() {

    	return $this->hasMany(ActualLineup::class);
    }

}