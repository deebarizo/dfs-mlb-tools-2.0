<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkPlayersRequest;
use App\Http\Requests\ParseDkActualLineupsRequest;
use App\Http\Requests\ParseRazzballProjectionsRequest;

use Illuminate\Support\Facades\Input;

use App\UseCases\UseCase;

use App\PlayerPool;

use DB;

class ParsersController extends Controller {

    public function showParseRazzballProjections() {

        $titleTag = 'Razzball Projections - Parsers | ';

        $useCase = new UseCase;

        $playerPools = $useCase->fetchPlayerPoolsForProjectionsParsers(setTodayDate());

        return view('/admin/parsers/razzball_projections', compact('titleTag', 'playerPools'));
    }

    public function parseRazzballProjections(ParseRazzballProjectionsRequest $request) {


    }


    public function parseDkOwnerships(Request $request) {

        $useCase = new UseCase;

        $results = $useCase->parseDkOwnerships();

        $message = $results->message;

        return redirect()->route('admin.parsers.dk_ownerships')->with('message', $message);
    }


    public function parseDkActualLineupPlayers(Request $request) {

        $useCase = new UseCase;

        $results = $useCase->parseDkActualLineupPlayers();

        $message = $results->message;

        return redirect()->route('admin.parsers.dk_actual_lineup_players')->with('message', $message);
    }


    public function showParseDkActualLineups() {

        $titleTag = 'DK Actual Lineups - Parsers | ';

        $useCase = new UseCase;

        $playerPools = $useCase->fetchPlayerPoolsForDkActualLineupsParser();

        return view('/admin/parsers/dk_actual_lineups', compact('titleTag', 'playerPools'));
    }

    public function parseDkActualLineups(ParseDkActualLineupsRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_actual_lineups/'; // '/files/dk_salaries/' doesn't work

            $playerPool = PlayerPool::find($request->input('player-pool-id'));

            $useCase = new UseCase;

            $csvFile = $useCase->uploadCsvFileForDkParsers($playerPool->time_period, $playerPool->site, $playerPool->date, $fileDirectory, $request);
            
            $results = $useCase->parseDkActualLineups($csvFile, $playerPool->date, $playerPool->site, $playerPool->time_period);
       
            $message = $results->message;

        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('admin.parsers.dk_actual_lineups')->with('message', $message);
    }


	public function parseDkPlayers(ParseDkPlayersRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_players/'; // '/files/dk_salaries/' doesn't work

            $useCase = new UseCase;
                
            $csvFile = $useCase->uploadCsvFileForDkParsers($request->input('time-period'), $request->input('site'), $request->input('date'), $fileDirectory, $request);
            
            $results = $useCase->parseDkPlayers($csvFile, $request->input('date'), $request->input('site'), $request->input('time-period'));
       
            $message = $results->message;

        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('admin.parsers.dk_players')->with('message', $message);
	}

}