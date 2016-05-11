<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActualLineup extends Model {

    public function playerPool() {

    	return $this->belongsTo(PlayerPool::class);
    }

    public function actual_lineup_players() {

        return $this->hasMany(ActualLineupPlayer::class);
    }

}