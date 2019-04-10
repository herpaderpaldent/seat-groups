<?php

use Faker\Generator as Faker;
use Seat\Web\Models\Acl\Permission;

$factory->define(Permission::class, function (Faker $faker) {

    return [
        'title'    => $faker->domainWord,
    ];
});
