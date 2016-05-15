<?php namespace App\UseCases;

ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;
use App\DkActualLineup;
use App\DkActualLineupPlayer;

use DB;

trait DkActualLineupsParser {

    /****************************************************************************************
    GET
    ****************************************************************************************/

    public function fetchPlayerPoolsForDkActualLineupsParser() {

        return DB::table('player_pools')
                    ->leftJoin('dk_actual_lineups', 'dk_actual_lineups.player_pool_id', '=', 'player_pools.id')
                    ->select('player_pools.id', 'player_pools.date', 'player_pools.time_period', 'player_pools.site')
                    ->groupBy('player_pools.id')
                    ->whereNull('dk_actual_lineups.player_pool_id')
                    ->orderBy(DB::raw('`date` asc, FIELD(player_pools.time_period, "Early", "Late", "All Day")'))
                    ->get();
    }


    /****************************************************************************************
    POST
    ****************************************************************************************/

    public function parseDkActualLineups($csvFile, $date, $site, $timePeriod) {

        $playerPool = PlayerPool::where('date', $date)
                                      ->where('site', $site)
                                      ->where('time_period', $timePeriod)
                                      ->get();

        if (count($playerPool) === 0) {

            $this->message = 'This player pool does not exist.';

            return $this;
        } 

        $dkActualLineups = DkActualLineup::where('player_pool_id', $playerPool[0]->id)->get();

        if (count($dkActualLineups) > 0) {

            $this->message = 'This player pool has already been parsed.';

            return $this;           
        }

        if (($handle = fopen($csvFile, 'r')) !== false) {
            
            $i = 0; // index

            $this->players = [];

            while (($row = fgetcsv($handle, 1000000, ',')) !== false) {
                
                if ($i > 0) { 

                    set_time_limit(60);

                    if (!is_numeric($row[0])) {

                        $this->message = 'The rank field of EntryId '.$row[1].' in the csv has a non-number.'; 

                        return $this;                       
                    }

                    if (!is_numeric($row[4])) {

                        $this->message = 'The fpts field of EntryId '.$row[1].' in the csv has a non-number.'; 

                        return $this;                       
                    }
                
                    $dkActualLineup = new DkActualLineup;

                    $dkActualLineup->player_pool_id = $playerPool[0]->id;
                    $dkActualLineup->rank = $row[0];
                    $dkActualLineup->user = preg_replace("/\s\(.+\)/", "", $row[2]);
                    $dkActualLineup->fpts = $row[4];
                    $dkActualLineup->raw_text_players = $row[5];

                    $dkActualLineup->save();
                }

                $i++;
            }
        } 

        $this->message = 'Success!';

        return $this;   
    }

}