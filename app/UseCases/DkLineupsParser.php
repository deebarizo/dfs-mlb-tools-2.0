<?php namespace App\UseCases;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

use DB;

trait DkLineupsParser {

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
                
                    $this->lineups[$i] = array( 

                        'player_pool_id' => $playerPool[0]->id,
                        'rank' => $row[0],
                        'entryId' => $row[1],
                        'user' => $row[2],
                        'fpts' => $row[4],
                        'lineupRawText' => $row[5]
                    );

                    if (!is_numeric($this->lineups[$i]['rank'])) {

                        $this->message = 'The rank field in the csv has a non-number.'; 

                        return $this;                       
                    }

                    if (!is_numeric($this->lineups[$i]['fpts'])) {

                        $this->message = 'The fpts field in the csv has a non-number.'; 

                        return $this;                       
                    }

                    if ($this->lineups[$i]['lineupRawText'] != '') {

                        $this->parseLineupRawText($i, $playerPool[0]->id, $this->lineups[$i]['lineupRawText'], $this->lineups[$i]['entryId']);
                    }

                    if ($this->message !== 'Success!') {

                        return $this;
                    }
                }

                $i++;
            }
        } 

        # $this->save($date, $site, $timePeriod);

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

                'position' => $position
#                'dkSalaryId' => $dkSalaryId
            ];
        }

        $this->lineups[$i]['players'] = $players;

        $this->message = 'Success!';

        return $this;
    }

}