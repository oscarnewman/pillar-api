<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;


$causes = [
    'Emergency Response',
    "Climate Change",
    'COVID-19 Response',
    'Reproductive Rights',
    'Homelessness & Hunger'
];

$factory->define(\App\Cause::class, function (Faker $faker) use ($causes) {
    return [
        'name' => $faker->randomElement($causes),
//        'image' => factory(\App\Image::class)
    ];
});
