<?php

use Faker\Generator as Faker;

$factory->define(App\Job::class, function (Faker $faker) {
    return [
        'floor_salary' => $faker->randomFloat($nbMaxDecimals = 2, $min = 10000, $max = 15000), // 48.8932
        'ceiling_salary' => $faker->randomFloat($nbMaxDecimals = 2, $min = 20000, $max = 35000), // 48.8932
    ];
});
