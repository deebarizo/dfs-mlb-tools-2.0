<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\Request;

class MasterViewTest extends TestCase {

	/** @test */
    public function shows_navigation_links() {

       	$this->visit('/');
       	$this->see('Scrapers');
    }

	/** @test */
    public function shows_title_tag() {

       	$this->visit('/');
       	$this->assertViewHas('titleTag');
    }

  	/** @test */
    public function shows_active_navigation_link_for_active_page() {

       	$this->visit('/scrapers');
       	$this->see('<li class="active"><a href="/scrapers">Scrapers</a></li>');
    }  


}