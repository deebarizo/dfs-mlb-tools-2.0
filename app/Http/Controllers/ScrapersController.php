<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

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
	}

}