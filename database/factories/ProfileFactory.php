<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
        'avatar' => $faker->imageUrl(),
        'bio' => $faker->text
    ];
});
