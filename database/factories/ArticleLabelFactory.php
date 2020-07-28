<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ArticleLabel;
use Faker\Generator as Faker;

$factory->define(ArticleLabel::class, function (Faker $faker) {
    return [
        'name' => Str::random(10),
    ];
});
