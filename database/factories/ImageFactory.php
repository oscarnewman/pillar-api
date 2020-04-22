<?php

/** @var Factory $factory */

use App\Image;
use App\Model;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'url' => $faker->imageUrl(512, 512, 'cats'),
        'width' => 512,
        'height' => 512,
    ];
});
