<?php

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Course::class, function (Faker $faker) {
    $year = (string) $faker->randomNumber(2);
    $semesters = array('WS ', 'SS ');
    $key = array_rand($semesters);
    $semester =  $semesters[$key] . $year;
    return [
        'module_id' => factory(\App\Module::class),
        'semester' => $semester
    ];
});
