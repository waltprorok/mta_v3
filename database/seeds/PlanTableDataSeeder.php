<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;

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
                'slug' => 'monthly',
                'stripe_plan' => 'plan_ESlBP8bL5WeV0I',
                'cost' => '9.95',
                'description' => null,
                'created_at' => '2021-12-29 00:00:01',
                'updated_at' => '2021-12-29 00:00:01',
            ],
            [
                'id' => '2',
                'name' => 'premium',
                'slug' => 'yearly',
                'stripe_plan' => 'plan_GOf0lJeuhZmpqS',
                'cost' => '99.95',
                'description' => null,
                'created_at' => '2021-12-29 00:00:01',
                'updated_at' => '2021-12-29 00:00:01',
            ]

        ]);
    }
}
