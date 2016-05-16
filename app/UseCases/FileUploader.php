<?php namespace App\UseCases;

use Illuminate\Http\Request;
use App\Http\Requests\ParseDkPlayersRequest;
use App\Http\Requests\ParseDkActualLineupsRequest;

use Illuminate\Support\Facades\Input;

trait FileUploader {

	public function uploadCsvFileForProjectionsParser($playerPoolId, $projectionSource, $playerType, $fileDirectory, $request) {

		if ($projectionSource === 'Razzball' && $playerType === 'pitchers') {

			$inputName = 'razzball-pitchers-csv';
		}

		if ($projectionSource === 'Razzball' && $playerType === 'hitters') {

			$inputName = 'razzball-hitters-csv';
		}

		if ($projectionSource === 'BAT' && $playerType === 'N/A') {

			$inputName = 'bat-csv';
		}		

		$fileName = 'player-pool-id-'.$playerPoolId.'-'.strtolower($projectionSource).'-'.strtolower($playerType).'.csv';
		$fileName = preg_replace('/-n\/a/', '', $fileName);

        if ($fileDirectory !== 'test_folder/') {

        	Input::file($inputName)->move($fileDirectory, $fileName);    
        }

        return $fileDirectory . $fileName;   
	}	

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