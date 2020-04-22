<?php

/** @var Factory $factory */

use App\Cause;
use App\Image;
use App\Organization;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
//        'logo' => factory(Image::class),
//        'cause_id' => Cause::all()->random()->id,
    ];
});
