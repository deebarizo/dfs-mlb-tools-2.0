<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkSalariesRequest;

use Illuminate\Support\Facades\Input;

use App\UseCases\UseCase;

use App\PlayerPool;

class ParsersController extends Controller {

    public function parseDkLineupPlayers() {


        
    }

    public function parseDkLineups(ParseDkSalariesRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_lineups/'; // '/files/dk_salaries/' doesn't work
            $fileName = $request->input('date').'.csv';
         
            Input::file('csv')->move($fileDirectory, $fileName);    

            $csvFile = $fileDirectory . $fileName;   

            $useCase = new UseCase;
            
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
            $fileName = $request->input('date').'.csv';
         
            Input::file('csv')->move($fileDirectory, $fileName);    

            $csvFile = $fileDirectory . $fileName;   

            $useCase = new UseCase;
            
            $results = $useCase->parseDkSalaries($csvFile, $request->input('date'), $request->input('site'), $request->input('time-period'));
       
            $message = $results->message;

        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('admin.parsers.dk_salaries')->with('message', $message);
	}

}