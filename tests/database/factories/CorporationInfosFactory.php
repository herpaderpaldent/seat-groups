<?php

use Faker\Generator as Faker;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

$factory->define(CorporationInfo::class, function (Faker $faker) {

    return [
        'corporation_id'    => $faker->numberBetween(98000000, 99000000),
        'name'            => $faker->company,
        'ticker'  => $faker->uuid ,
        'member_count'        => $faker->randomNumber(),
        'ceo_id'          => $faker->numberBetween(9000000, 98000000),
        'tax_rate'         => $faker->randomFloat(3,0,1),
        'creator_id'    => $faker->numberBetween(9000000, 98000000),
    ];
});