<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DkActualLineup extends Model {

	protected $fillable = ['raw_text_players_parsed'];

    public function player_pool() {

    	return $this->belongsTo(PlayerPool::class);
    }

    public function dk_actual_lineup_players() {

        return $this->hasMany(DkActualLineupPlayer::class);
    }

}
