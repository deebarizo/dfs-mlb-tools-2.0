<?php namespace App\UseCases;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkPlayersRequest;
use App\Http\Requests\ParseDkActualLineupsRequest;

use Illuminate\Support\Facades\Input;

trait FileUploader {

    public function uploadCsvFileForDkParsers($timePeriod, $site, $date, $fileDirectory, $request) {

        $timePeriodInUrl = preg_replace('/ /', '-', strtolower($timePeriod));
        $siteInUrl = strtolower($site);

        $fileName = $date.'-'.$timePeriodInUrl.'-'.$siteInUrl.'.csv';

        if ($fileDirectory !== 'test_folder/') {

        	Input::file('csv')->move($fileDirectory, $fileName);    
        }

        return $fileDirectory . $fileName;   
    }

}