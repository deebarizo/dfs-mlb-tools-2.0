<?php namespace App\UseCases;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkSalariesRequest;
use App\Http\Requests\ParseDkLineupsRequest;

use Illuminate\Support\Facades\Input;

trait FileUploader {

    public function uploadCsvFile($timePeriod, $site, $date, $fileDirectory, $request) {

        $timePeriodInUrl = preg_replace('/ /', '-', strtolower($timePeriod));
        $siteInUrl = strtolower($site);

        $fileName = $date.'-'.$timePeriodInUrl.'-'.$siteInUrl.'.csv';

        if ($fileDirectory !== 'test_folder/') {

        	Input::file('csv')->move($fileDirectory, $fileName);    
        }

        return $fileDirectory . $fileName;   
    }

}