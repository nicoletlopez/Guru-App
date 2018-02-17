<?php

use Faker\Generator as Faker;

$factory->define(App\Profile::class, function (Faker $faker) {
    return [
        'picture' => $faker->imageUrl($width = 640, $height = 480), // 'http://lorempixel.com/640/480/'
        'description' => $faker->paragraphs($nb = rand(1,2), $asText = true),
        'dob' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
        'city' => $faker->city,
        'street_address' => $faker->streetAddress,
        'contact_number' => $faker->phoneNumber,
    ];
});
