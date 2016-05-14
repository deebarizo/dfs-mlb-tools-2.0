<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParseDkOwnershipsTest extends TestCase {

	/** @test */
    public function submits() {

       	$this->visit('/admin/parsers/dk_ownerships');
        $this->press('Submit');
    }

}