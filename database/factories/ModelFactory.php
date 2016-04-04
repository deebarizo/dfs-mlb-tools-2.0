<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Team::class, function ($faker) {
    
    return [
        
        'name_dk' => str_random(3),
        'name_espn' => str_random(3),
        'name_fg' => str_random(7),
        'created_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'updated_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});

$factory->define(App\Player::class, function ($faker) {
    
    return [
        
        'team_id' => rand(1, 100),
        'name_dk' => $faker->name,
        'created_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'updated_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
