<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'otp' => null,
        'otp_at' => \Carbon\Carbon::now(),
    ];
});

$factory->state(User::class, 'otp', function (Faker $faker) {
    return [
        'otp' => $faker->unique()->numberBetween($min = 1000, $max = 9999),
    ];
});

$factory->state(User::class, 'authorized', function (Faker $faker) {
    return [
        'token_key' => 'someRandomToken'.$faker->unique()->numberBetween(0, 999999),
    ];
});
