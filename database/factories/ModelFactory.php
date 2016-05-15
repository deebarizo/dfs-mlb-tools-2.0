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
        
        'name_dk' => 'LAD',
        'name_espn' => 'lad',
        'name_fg' => 'Dodgers',
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\PlayerPool::class, function ($faker) {
    
    return [
        
        'date' => '2016-01-01',
        'time_period' => 'All Day',
        'site' => 'DK',
        'buy_in' => 100,
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

$factory->define(App\DkPlayer::class, function ($faker) {
    
    return [
        
        'player_pool_id' => 1,
        'player_id' => 1,
        'dk_id' => 528369,
        'team_id' => 1,
        'opp_team_id' => 2,
        'position' => 'SP',
        'salary' => '12000',
        'created_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'updated_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});

$factory->define(App\DkActualLineup::class, function ($faker) {
    
    return [
        
        'created_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'updated_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});

$factory->define(App\DkActualLineupPlayer::class, function ($faker) {
    
    return [
        
        'created_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'updated_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
