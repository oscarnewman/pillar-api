<?php

use App\Cause;
use App\Image;
use App\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class CauseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        factory(Cause::class, 5)->create();
        factory(Organization::class, 12)->create();


    }
}
