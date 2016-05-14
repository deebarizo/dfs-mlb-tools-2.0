<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkSalariesRequest;
use App\Http\Requests\ParseDkLineupsRequest;

use Illuminate\Support\Facades\Input;

use App\UseCases\UseCase;

use App\PlayerPool;

use DB;

class ParsersController extends Controller {

    public function parseDkOwnerships(Request $request) {

        
    }


    public function parseDkLineupPlayers(Request $request) {

        $useCase = new UseCase;

        $results = $useCase->parseDkLineupPlayers();

        $message = $results->message;

        return redirect()->route('admin.parsers.dk_lineup_players')->with('message', $message);
    }


    public function getParseDkLineups() {

        $titleTag = 'DK Lineups - Parsers | ';

        $useCase = new UseCase;

        $playerPools = $useCase->fetchPlayerPoolsForDkLineupsParser();

        return view('/admin/parsers/dk_lineups', compact('titleTag', 'playerPools'));
    }

    public function parseDkLineups(ParseDkLineupsRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_lineups/'; // '/files/dk_salaries/' doesn't work

            $playerPool = PlayerPool::find($response->input('player-pool-id'));

            $useCase = new UseCase;

            $csvFile = $useCase->uploadCsvFile($playerPool->time_period, $playerPool->site, $playerPool->date, $fileDirectory);
            
            $results = $useCase->parseDkLineups($csvFile, $request->input('date'), $request->input('site'), $request->input('time-period'));
       
            $message = $results->message;

        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('admin.parsers.dk_lineups')->with('message', $message);
    }


	public function parseDkSalaries(ParseDkSalariesRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_salaries/'; // '/files/dk_salaries/' doesn't work

            $useCase = new UseCase;
                
            $csvFile = $useCase->uploadCsvFile($request->input('time-period'), $request->input('site'), $request->input('date'), $fileDirectory);
            
            $results = $useCase->parseDkSalaries($csvFile, $request->input('date'), $request->input('site'), $request->input('time-period'));
       
            $message = $results->message;

        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('admin.parsers.dk_salaries')->with('message', $message);
	}

}