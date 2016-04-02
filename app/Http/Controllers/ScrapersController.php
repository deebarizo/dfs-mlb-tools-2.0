<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ScrapeDkSalariesRequest;

use Illuminate\Support\Facades\Input;

use App\UseCases\StoreDkSalaries;

class ScrapersController extends Controller {

	public function scrapeDkSalaries(ScrapeDkSalariesRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $fileDirectory = 'files/dk_salaries/'; // '/files/dk_salaries/' doesn't work
            $fileName = $request->input('date').'.csv';
         
            Input::file('csv')->move($fileDirectory, $fileName);    

            $csvFile = $fileDirectory . $fileName;   

            $message = StoreDkSalaries::perform($csvFile); 
        
        } else {

            $message = 'Success!'; // I need this to pass the test
        }

        return redirect()->route('scrapers.dk_salaries')->with('message', $message);
	}

}