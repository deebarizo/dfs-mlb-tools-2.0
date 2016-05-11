<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DkSalary extends Model {

    protected $fillable = ['ownership', 'ownership_of_first_position', 'ownership_of_second_position'];

    public function team() {

    	return $this->belongsTo(Team::class);
    }

    public function oppTeam() {

    	return $this->belongsTo(Team::class);
    }

    public function playerPool() {

    	return $this->belongsTo(PlayerPool::class);
    }

    public function player() {

    	return $this->belongsTo(Player::class);
    }

    public function actual_lineup_players() {

        return $this->hasMany(ActualLineupPlayer::class);
    }

}
