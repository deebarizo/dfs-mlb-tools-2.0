<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActualLineupPlayer extends Model {

    public function actual_lineups() {

        return $this->belongsTo(ActualLineup::class);
    }

    public function dk_salaries() {

    	return $this->belongsTo(DkSalary::class);
    }

}
