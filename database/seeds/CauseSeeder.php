<?php

use App\Cause;
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
        $causes = collect(([
            ['name' => 'Emergency Response'],
            ['name' => "Climate Change"],
            ['name' => 'COVID-19 Response'],
            ['name' => 'Reproductive Rights'],
            ['name' => 'Homelessness & Hunger']
        ]));

        $causes = $causes->map(function ($cause) use (&$faker) {
            $cause =  Cause::create($cause);
            $orgs = factory(Organization::class, rand(3, 8))->create();

            foreach ($orgs as $org) {
                $cause->organizations()->attach($org);
            }
        });
    }
}
