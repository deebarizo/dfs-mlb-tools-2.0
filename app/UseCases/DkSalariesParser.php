<?php namespace App\UseCases;

use DkSalariesParser;

use App\Team;
use App\Player;
use App\DkSalary;

trait DkSalariesParser {

	public function parseDkSalaries($csvFile, $date) {

		if (($handle = fopen($csvFile, 'r')) !== false) {
			
			$i = 0; // index

			$this->players = [];

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($i > 7) { 
				
				    $this->players[$i] = array( 

				    	'position' => $row[15],
				       	'nameDk' => convertAccentLettersToEnglish($row[17]),
				       	'idDk' => $row[18],
				       	'salary' => $row[19],
				       	'teamNameDk' => $row[21]
				    );

				    if (is_numeric($this->players[$i]['position'])) {

						$this->message = 'The CSV format has changed. The position field has numbers.'; 

						return $this;				    	
				    }
				}

				$i++;
			}
		} 
	}

}