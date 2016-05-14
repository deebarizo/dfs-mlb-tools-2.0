<?php namespace App\UseCases;

ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

use DB;

trait DkLineupsParser {

    /****************************************************************************************
    GET
    ****************************************************************************************/

    public function fetchValidPlayerPools() {

        return DB::table('player_pools')
                    ->leftJoin('actual_lineups', 'actual_lineups.player_pool_id', '=', 'player_pools.id')
                    ->select('player_pools.id', 'player_pools.date', 'player_pools.time_period', 'player_pools.site')
                    ->groupBy('player_pools.id')
                    ->whereNull('actual_lineups.player_pool_id')
                    ->orderBy(DB::raw('`date` asc, FIELD(player_pools.time_period, "Early", "Late", "All Day")'))
                    ->get();
    }


    /****************************************************************************************
    POST
    ****************************************************************************************/

    public function parseDkLineups($csvFile, $date, $site, $timePeriod) {

        $playerPool = PlayerPool::where('date', $date)
                                      ->where('site', $site)
                                      ->where('time_period', $timePeriod)
                                      ->get();

        if (count($playerPool) === 0) {

            $this->message = 'This player pool does not exist.';

            return $this;
        } 

        $actualLineups = ActualLineup::where('player_pool_id', $playerPool[0]->id)->get();

        if (count($actualLineups) > 0) {

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
                
                    $actualLineup = new ActualLineup;

                    $actualLineup->player_pool_id = $playerPool[0]->id;
                    $actualLineup->rank = $row[0];
                    $actualLineup->user = preg_replace("/\s\(.+\)/", "", $row[2]);
                    $actualLineup->fpts = $row[4];
                    $actualLineup->raw_text_players = $row[5];

                    $actualLineup->save();
                }

                $i++;
            }
        } 

        # $this->saveDkLineups($this->lineups);

        # $this->addOwnerships($playerPool[0]->id);

        $this->message = 'Success!';

        return $this;   
    }

    private function parseLineupRawText($i, $playerPoolId, $rawText, $entryId) {

        $rawText = preg_replace("/\sP\s/", "|P ", $rawText);
        $rawText = preg_replace("/\sC\s/", "|C ", $rawText);
        $rawText = preg_replace("/\s1B\s/", "|1B ", $rawText);
        $rawText = preg_replace("/\s2B\s/", "|2B ", $rawText);
        $rawText = preg_replace("/\s3B\s/", "|3B ", $rawText);
        $rawText = preg_replace("/\sSS\s/", "|SS ", $rawText);
        $rawText = preg_replace("/\sOF\s/", "|OF ", $rawText);

        $rawTextPlayers = explode('|', $rawText);

        if (count($rawTextPlayers) !== 10) {

            $this->message = 'The lineup with entry ID of '.$entryId.' does not have 10 players';

            return $this;
        } 

        $players = [];

        foreach ($rawTextPlayers as $rawTextPlayer) {

            $position = preg_replace("/^(\w+)(\s)(.+)/", "$1", $rawTextPlayer);

            $name = preg_replace("/^(\w+)(\s)(.+)/", "$3", $rawTextPlayer);

            $dkSalary = DB::table('players')
                            ->join('dk_salaries', 'dk_salaries.player_id', '=', 'players.id')
                            ->select('*')
                            ->where('players.name_dk', $name)
                            ->where('dk_salaries.position', 'like', '%'.$position.'%')
                            ->where('dk_salaries.player_pool_id', $playerPoolId)
                            ->get();

            if (count($dkSalary) === 0) {

                $this->message = 'The lineup with entry ID of '.$this->lineups[$i]['entryId'].' has a missing player in database: '.$rawTextPlayer;

                return $this;
            }

            $players[] = [

                'position' => $position,
                'dkSalaryId' => $dkSalary[0]->id
            ];
        }

        $this->lineups[$i]['players'] = $players;

        $this->message = 'Success!';

        return $this;
    }

    private function saveDkLineups($lineups) {

        foreach ($lineups as $lineup) {

            # ddAll($lineup);
            
            $actualLineup = new ActualLineup;

            $actualLineup->player_pool_id = $lineup['player_pool_id'];
            $actualLineup->rank = $lineup['rank'];
            $actualLineup->user = $lineup['user'];
            $actualLineup->fpts = $lineup['fpts'];

            $actualLineup->save();

            foreach ($lineup['players'] as $player) {
                
                $actualLineupPlayer = new ActualLineupPlayer;

                $actualLineupPlayer->actual_lineup_id = $actualLineup->id;
                $actualLineupPlayer->position = $player['position'];
                $actualLineupPlayer->dk_salary_id = $player['dkSalaryId'];

                $actualLineupPlayer->save();
            }
        }
    }

    private function addOwnerships($playerPoolId) {

        $dkSalaries = DkSalary::where('player_pool_id', $playerPoolId)->get();

        $actualLineups = ActualLineup::where('player_pool_id', $playerPoolId)->get();

        foreach ($dkSalaries as $dkSalary) {

            $numOfLineupsWithPlayer = DB::table('player_pools')
                                        ->join('actual_lineups', 'actual_lineups.player_pool_id', '=', 'player_pools.id')
                                        ->join('actual_lineup_players', 'actual_lineup_players.actual_lineup_id', '=', 'actual_lineups.id')
                                        ->where('player_pools.id', $playerPoolId)
                                        ->where('actual_lineup_players.dk_salary_id', $dkSalary->id)
                                        ->count();

            if ($numOfLineupsWithPlayer > 0) {

                $ownership = numFormat($numOfLineupsWithPlayer / count($actualLineups) * 100, 1);

                $dkSalary->update(['ownership' => $ownership, 'ownership_of_first_position' => $ownership]);
            }
            
            if (strpos($dkSalary->position, '/') !== false) {

                $positions = [];

                $positions['first'] = preg_replace("/(\w+)(\/)(\w+)/", "$1", $dkSalary->position);
                $positions['second'] = preg_replace("/(\w+)(\/)(\w+)/", "$3", $dkSalary->position);

                foreach ($positions as $key => $position) {

                    $numOfLineupsWithPlayer = DB::table('player_pools')
                                                ->join('actual_lineups', 'actual_lineups.player_pool_id', '=', 'player_pools.id')
                                                ->join('actual_lineup_players', 'actual_lineup_players.actual_lineup_id', '=', 'actual_lineups.id')
                                                ->where('player_pools.id', $playerPoolId)
                                                ->where('actual_lineup_players.dk_salary_id', $dkSalary->id)
                                                ->where('actual_lineup_players.position', $position)
                                                ->count();

                    if ($numOfLineupsWithPlayer > 0) {

                        $ownership = numFormat($numOfLineupsWithPlayer / count($actualLineups) * 100, 1);

                        $dkSalary->update(['ownership_of_'.$key.'_position' => $ownership]);
                    }                    
                }
            } 
        }        
    }

}