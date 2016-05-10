<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerPool extends Model {
    
    public function dk_salaries() {

    	return $this->hasMany(DkSalary::class);
    }

}