<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FormHeadingTest extends TestCase {

	/** @test */
    public function shows_h2_tag() {

       	$this->visit('/admin/parsers/projections');
       	$this->assertViewHas('h2Tag');
    }

}