<?php

use Faker\Generator as Faker;

$factory->define(App\Grading::class, function (Faker $faker) {
    return [
        'student_id' => factory(\App\Student::class),
        'course_id' => factory(\App\Course::class),
        'grade' => function () {
            $grades = array(
                    '1.0', '1.3', '1.7', '2.0', '2.3',
                    '2.7', '3.0', '3.3', '3.7', '4.0', '5.0'
                );
            $key = array_rand($grades);
            return $grades[$key];
        }
    ];
});
