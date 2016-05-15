<?php namespace App\UseCases;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

use DB;

trait RazzballProjectionsParser {

    /****************************************************************************************
    GET
    ****************************************************************************************/

    public function fetchPlayerPoolsForProjectionsParsers($todayDate) {

        return DB::table('player_pools')
                    ->select('*')
                    ->where('date', $todayDate)
                    ->orderBy(DB::raw('`date` asc, FIELD(time_period, "Early", "Late", "All Day")'))
                    ->get();
    }


    /****************************************************************************************
    POST
    ****************************************************************************************/

    public function parseRazzballPitcherProjections($csvFile, $playerPoolId) {

        if (($handle = fopen($csvFile, 'r')) !== false) {
            
            $i = 0; // index

            while (($row = fgetcsv($handle, 5000, ',')) !== false) {
                
                if ($i > 0) { 
                
                    $razzballName = $row[1];

                    $dkPlayer = DB::table('player_pools')
                                    ->join('dk_players', 'dk_players.player_pool_id', '=', 'player_pools.id')
                                    ->join('players', 'players.id', '=', 'dk_players.player_id')
                                    ->where('player_pools.id', $playerPoolId)
                                    ->where('name_dk', $razzballName)
                                    ->get();

                    if (count($dkPlayer) === 0) {

                        $this->message = 'The Razzball name, '.$razzballName.', does not match a DK name.'; 

                        return $this;  
                    }
                }

                $i++;
            }
        }                 
    }

}