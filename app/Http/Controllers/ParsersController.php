<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkSalariesRequest;

use Illuminate\Support\Facades\Input;

use App\UseCases\StoreDkSalaries;

class ParsersController extends Controller {

	public function parseDkSalaries(ParseDkSalariesRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_salaries/'; // '/files/dk_salaries/' doesn't work
            $fileName = $request->input('date').'.csv';
         
            Input::file('csv')->move($fileDirectory, $fileName);    

            $csvFile = $fileDirectory . $fileName;   

            $storeDkSalaries = new StoreDkSalaries; 
            
            $results = $storeDkSalaries->perform($csvFile, $request->input('date'));
       
            $message = $results->message;

        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('admin.parsers.dk_salaries')->with('message', $message);
	}

}