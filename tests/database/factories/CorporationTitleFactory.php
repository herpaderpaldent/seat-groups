<?php

use Faker\Generator as Faker;
use Seat\Eveapi\Models\Corporation\CorporationTitle;

$factory->define(CorporationTitle::class, function (Faker $faker) {
    return [
        'corporation_id'  => $faker->numberBetween(98000000,99000000),
        'title_id'        => $faker->randomNumber(),
        'name'            => $faker->name
    ];
});
