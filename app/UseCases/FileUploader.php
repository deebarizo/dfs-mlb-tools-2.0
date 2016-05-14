<?php namespace App\UseCases;

trait FileUploader {

    private function uploadCsvFile($timePeriod, $site, $date, $fileDirectory) {

        $timePeriodInUrl = preg_replace('/ /', '-', strtolower($timePeriod));
        $siteInUrl = strtolower($site);

        $fileName = $date.'-'.$timePeriodInUrl.'-'.$siteInUrl.'.csv';

        if ($fileDirectory !== 'test_folder/') {

        	Input::file('csv')->move($fileDirectory, $fileName);    
        }

        return $fileDirectory . $fileName;   
    }

}