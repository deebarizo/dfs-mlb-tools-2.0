<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function($table)
        {
            $table->increments('id');
            $table->string('name_dk'); 
            $table->string('name_espn'); 
            $table->string('name_fg');      
            $table->date('created_at');
            $table->date('updated_at');
        });

        DB::insert("INSERT INTO `teams` VALUES (1,'LAD','lad','Dodgers','2015-03-13','2015-03-13'),(2,'SD','sd','Padres','2015-03-13','2015-03-13'),(3,'Was','wsh','Nationals','2015-03-13','2015-03-13'),(4,'NYM','nym','Mets','2015-03-13','2015-03-13'),(5,'Sea','sea','Mariners','2015-03-13','2015-03-13'),(6,'LAA','laa','Angels','2015-03-13','2015-03-13'),(7,'Oak','oak','Athletics','2015-03-13','2015-03-13'),(8,'Tex','tex','Rangers','2015-03-13','2015-03-13'),(9,'KC','kc','Royals','2015-03-13','2015-03-13'),(10,'CWS','chw','White Sox','2015-03-13','2015-03-13'),(11,'Det','det','Tigers','2015-03-13','2015-03-13'),(12,'Min','min','Twins','2015-03-13','2015-03-13'),(13,'Phi','phi','Phillies','2015-03-13','2015-03-13'),(14,'Bos','bos','Red Sox','2015-03-13','2015-03-13'),(15,'Ari','ari','Diamondbacks','2015-03-13','2015-03-13'),(16,'SF','sf','Giants','2015-03-13','2015-03-13'),(17,'Hou','hou','Astros','2015-03-13','2015-03-13'),(18,'Cle','cle','Indians','2015-03-13','2015-03-13'),(19,'NYY','nyy','Yankees','2015-03-13','2015-03-13'),(20,'Tor','tor','Blue Jays','2015-03-13','2015-03-13'),(21,'Cin','cin','Reds','2015-03-13','2015-03-13'),(22,'Pit','pit','Pirates','2015-03-13','2015-03-13'),(23,'Mia','mia','Marlins','2015-03-13','2015-03-13'),(24,'Atl','atl','Braves','2015-03-13','2015-03-13'),(25,'TB','tb','Rays','2015-03-13','2015-03-13'),(26,'Bal','bal','Orioles','2015-03-13','2015-03-13'),(27,'Mil','mil','Brewers','2015-03-13','2015-03-13'),(28,'Col','col','Rockies','2015-03-13','2015-03-13'),(29,'ChC','chc','Cubs','2015-04-07','2015-04-07'),(30,'StL','stl','Cardinals','2015-04-07','2015-04-07')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
