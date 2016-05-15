<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DkActualLineupPlayer extends Model {

    public function dk_actual_lineups() {

        return $this->belongsTo(DkActualLineup::class);
    }

    public function dk_players() {

    	return $this->belongsTo(DkPlayer::class);
    }

}
