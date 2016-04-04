<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DkSalary extends Model {

    public function player() {

    	return $this->belongsTo(Player::class);
    }

    public function team() {

    	return $this->belongsTo(Team::class);
    }

    public function oppTeam() {

    	return $this->belongsTo(Team::class);
    }

}
