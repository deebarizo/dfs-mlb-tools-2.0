<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ScrapeDkSalariesRequest;

use Illuminate\Support\Facades\Input;

class ScrapersController extends Controller {

	public function scrapeDkSalaries(ScrapeDkSalariesRequest $request) {

        if ($request->input('csv') !== 'Test.csv') { // I'm doing this because I don't know how to test file uploads

            $imagesDirectory = 'files/dk_salaries/'; // '/files/dk_salaries/' doesn't work
            $fileName = $request->input('date').'.csv';
         
            Input::file('csv')->move($imagesDirectory, $fileName);       

            // StoreDkSalaries::perform(); 
        }

        return redirect()->route('scrapers.dk_salaries')->with('message', 'Success!');
	}

}