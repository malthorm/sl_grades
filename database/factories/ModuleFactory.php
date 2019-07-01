<?php

use Faker\Generator as Faker;

$factory->define(App\Module::class, function (Faker $faker) {
    return [
        'number' => (string ) $faker->randomNumber(6),
        'title' => $faker->word
    ];
});
