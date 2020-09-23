<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Spatie\Permission\Models\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'guard_name' => 'api',
        'name' => $faker->title,
        'slug' => $faker->title . helpers::getRandomString(0, 6),
        'type' => \App\Models\Permission::TYPE_MENU,
        'alias_name' => $faker->title,
        'parent_id' => 0,
        'icon' => 'al-icon-record',
        'created_at' => now()
    ];
});
