<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'guard_name' => 'api',
        'name' => $faker->title . helpers::getRandomString(0, 4),
        'alias_name' => $faker->title . helpers::getRandomString(0, 4),
        'created_at' => now()
    ];
});
