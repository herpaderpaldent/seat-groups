<?php

use Faker\Generator as Faker;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;

$factory->define(SeatGroup::class, function (Faker $faker) {
    return [
        'name'            => $faker->name,
        'description'     => $faker->text,
        'type'            => $faker->randomElement(['auto', 'open', 'managed', 'hidden']),
        'all_corporations' => $faker->boolean,
    ];
});
