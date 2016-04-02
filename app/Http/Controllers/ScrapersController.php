<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use Illuminate\Support\Facades\Input;

class ScrapersController extends Controller {

	public function storeDkSalaries(Request $request) {

        $rules = [

			'date' => 'required',
			'csv' => 'required'
        ];

        $messages = [

            'date.required' => 'The date field is required.',
            'csv.required' => 'The csv field is required.'
        ];        

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return redirect()->route('scrapers.dk_salaries')
                             ->withErrors($validator)
                             ->withInput();
        }

        if ($request->input('csv') !== 'Test.csv') {

            $imagesDirectory = 'files/dk_salaries/'; // '/files/dk_salaries/' doesn't work
            $fileName = $request->input('date').'.csv';
         
            Input::file('csv')->move($imagesDirectory, $fileName);        
        }

        return redirect()->route('scrapers.dk_salaries')->with('message', 'Success!');
	}

}