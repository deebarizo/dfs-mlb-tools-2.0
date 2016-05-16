<?php namespace App\UseCases;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

use DB;

trait ProjectionsParser {

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

                    set_time_limit(60);
                
                    $razzballName = $row[1];
                    $lineup = $row[7];
                    $fpts = $row[17];

                    if ($lineup !== 'Live' && $lineup !== 'Lst7') {

                        $this->message = 'The lineup field is "'.$lineup.'". It should be "Live" or "Lst7".'; 

                        return $this;  
                    }

                    if (!is_numeric($fpts)) {

                        $this->message = 'The fpts field is "'.$fpts.'". It should be a number.'; 

                        return $this;                          
                    }

                    $dkPlayer = DB::table('player_pools')
                                    ->join('dk_players', 'dk_players.player_pool_id', '=', 'player_pools.id')
                                    ->join('players', 'players.id', '=', 'dk_players.player_id')
                                    ->where('player_pools.id', $playerPoolId)
                                    ->where(function ($query) use ($razzballName) {
                                        $query->where('players.name_dk', $razzballName)
                                              ->orWhere('players.name_razzball', $razzballName);
                                    })
                                    ->first();

                    if ($dkPlayer->position !== 'SP' && $dkPlayer->position !== 'RP') {

                        $this->message = 'The Razzball pitcher, '.$razzballName.', is not a DK pitcher.'; 

                        return $this;  
                    }

                    if ($dkPlayer) {

                        DB::table('dk_players')
                            ->where('id', $dkPlayer->id)
                            ->update(['lineup_razzball' => $lineup, 'fpts_razzball' => $fpts]);
                    }
                }

                $i++;
            }
        }

        $this->message = 'Success!';

        return $this;                 
    }

    public function parseRazzballHitterProjections($csvFile, $playerPoolId) {

        if (($handle = fopen($csvFile, 'r')) !== false) {
            
            $i = 0; // index

            while (($row = fgetcsv($handle, 5000, ',')) !== false) {
                
                if ($i > 0) { 

                    set_time_limit(60);
                
                    $razzballName = $row[1];
                    $lineup = $row[11];
                    $percentStart = $row[10];
                    $fpts = $row[21];
                    $upsideFpts = $row[22];

                    if ($lineup !== 'Live' && $lineup !== 'Lst7') {

                        $this->message = 'The lineup field is "'.$lineup.'". It should be "Live" or "Lst7".'; 

                        return $this;  
                    }

                    if (!is_numeric($percentStart)) {

                        $this->message = 'The percent start field is "'.$percentStart.'". It should be a number.'; 

                        return $this;                          
                    }

                    if (!is_numeric($fpts)) {

                        $this->message = 'The fpts field is "'.$fpts.'". It should be a number.'; 

                        return $this;                          
                    }

                    if (!is_numeric($upsideFpts)) {

                        $this->message = 'The upside fpts field is "'.$upsideFpts.'". It should be a number.'; 

                        return $this;                          
                    }

                    $dkPlayer = DB::table('player_pools')
                                    ->join('dk_players', 'dk_players.player_pool_id', '=', 'player_pools.id')
                                    ->join('players', 'players.id', '=', 'dk_players.player_id')
                                    ->where('player_pools.id', $playerPoolId)
                                    ->where(function ($query) use ($razzballName) {
                                        $query->where('players.name_dk', $razzballName)
                                              ->orWhere('players.name_razzball', $razzballName);
                                    })
                                    ->first();

                    if ($dkPlayer->position === 'SP' || $dkPlayer->position === 'RP') {

                        $this->message = 'The Razzball hitter, '.$razzballName.', is not a DK hitter.'; 

                        return $this;  
                    }

                    if ($dkPlayer) {

                        DB::table('dk_players')
                            ->where('id', $dkPlayer->id)
                            ->update([
                                'lineup_razzball' => $lineup,
                                'percent_start_razzball' => $percentStart,
                                'fpts_razzball' => $fpts,
                                'upside_fpts_razzball' => $upsideFpts
                            ]);
                    }
                }

                $i++;
            }
        }

        $this->message = 'Success!';

        return $this;                 
    }    

    public function parseBatProjections($csvFile, $playerPoolId) {



    }

}