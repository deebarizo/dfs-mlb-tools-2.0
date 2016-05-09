<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MasterViewTest extends TestCase {

	/** @test */
    public function shows_navigation_links() {

       	$this->visit('/');
       	$this->see('Admin');
    }

	/** @test */
    public function shows_title_tag() {

       	$this->visit('/');
       	$this->assertViewHas('titleTag');
    }

  	/** @test */
    public function shows_active_navigation_link_for_active_page() {

       	$this->visit('/admin');
       	$this->see('<li class="active"><a href="/admin">Admin</a></li>');
    }  

}