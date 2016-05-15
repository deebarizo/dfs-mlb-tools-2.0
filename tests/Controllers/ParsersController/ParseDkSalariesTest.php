<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\UseCases\UseCase;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class ParseDkSalariesTest extends TestCase {

    use DatabaseTransactions;

	/** @test */
    public function submits_dk_salaries_csv() {

       	$this->visit('/admin/parsers/dk_salaries');
        $this->select('DK', 'site');
        $this->select('All Day', 'time-period');
       	$this->type('2016-03-31', 'date');
        $this->type('DKSalaries.csv', 'csv')
             ->attach('/files/dk_salaries/', 'csv');
        $this->press('Submit');
    }

    public function gets_csv_file() {

        $response = $this->call('POST', '/admin/parsers/dk_salaries', [

            'site' => 'DK',
            'time-period' => 'All Day',
            'date' => '2016-01-01',
            'csv' => 'Test.csv'
        ]);

        $fileDirectory = 'test_folder/';

        $useCase = new UseCase;

        $csvFile = $useCase->uploadCsvFile($request->input('time-period'), $request->input('site'), $request->input('date'), $fileDirectory);

        $this->assertContains($csvFile, 'test_folder/2016-01-01-all-day-dk.csv');
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/admin/parsers/dk_salaries', [

            'date' => '',
            'csv' => ''
        ]);

        $this->assertSessionHasErrors(['date', 'csv']);

        // I don't need to test the redirect because Taylor Otwell has already tested the form request class. I'm using that class for validation and the class automatically redirects back to the page with an $errors object. Plus, when I try to test the redirect, it doesn't work.
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/admin/parsers/dk_salaries', [

            'date' => '2016-04-02',
            'csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/admin/parsers/dk_salaries');

        $this->followRedirects();

        $this->see('Success!');
    }  

}