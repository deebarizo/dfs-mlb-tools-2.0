<?php namespace App\UseCases;

class UseCase {

	use FileUploader;

	use ProjectionsParser;

	use DkPlayersParser;
	use DkActualLineupsParser;
	use DkActualLineupPlayersParser;
	use DkOwnershipsParser;

	use VrsCalculator;
}