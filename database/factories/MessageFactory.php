<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Message::class, function (Faker $faker) {
    $sender = factory('App\User')->states(['otp', 'authorized'])->create();
    $message = factory('App\Text')->create();

    return [
        'sender_id' => $sender->id,
        'receiver_id' => factory('App\User')->states(['otp', 'authorized'])->create()->id,
        'messageable_id' => $message->id,
        'messageable_type' => 'text',
    ];
});
