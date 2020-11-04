<?php

use Illuminate\Database\Seeder;
use App\Plan;

class PlanTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::insert([
            [
                'id' => '1',
                'name' => 'premium',
                'slug' => 'premium',
                'stripe_plan' => 'plan_ESlBP8bL5WeV0I',
                'cost' => '9.95',
                'description' => null,
                'created_at' => '2020-11-01 00:00:01',
                'updated_at' => '2020-11-01 00:00:01',
            ],
//            [
//                'id' => '2',
//                'name' => 'enterprise',
//                'slug' => 'enterprise',
//                'stripe_plan' => 'prod_GOf0DIfXMKrACE',
//                'cost' => '99.95',
//                'description' => null,
//                'created_at' => '2020-11-01 00:00:01',
//                'updated_at' => '2020-11-01 00:00:01',
//            ]
        ]);
    }
}
