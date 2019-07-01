<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
        'uni_identifier' => encrypt($faker->word)
    ];
});
