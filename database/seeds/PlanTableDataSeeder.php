<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class PlanTableDataSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            Plan::insert([
                [
                    'id' => '1',
                    'name' => 'premium',
                    'slug' => 'monthly',
                    'stripe_plan' => 'plan_ESlBP8bL5WeV0I',
                    'cost' => '9.95',
                    'description' => 'Monthly Plan',
                    'created_at' => '2021-12-29 00:00:01',
                    'updated_at' => '2021-12-29 00:00:01',
                ],
                [
                    'id' => '2',
                    'name' => 'premium',
                    'slug' => 'yearly',
                    'stripe_plan' => 'plan_GOf0lJeuhZmpqS',
                    'cost' => '99.95',
                    'description' => 'Yearly Plan',
                    'created_at' => '2021-12-29 00:00:01',
                    'updated_at' => '2021-12-29 00:00:01',
                ]
            ]);
        }

        if (App::environment('production')) {
            Plan::insert([
                [
                    'id' => '1',
                    'name' => 'premium',
                    'slug' => 'monthly',
                    'stripe_plan' => 'price_1K2yoYC8yeL47nHbxowd8PhA',
                    'cost' => '9.95',
                    'description' => 'Monthly Plan',
                    'created_at' => '2024-10-01 00:00:01',
                    'updated_at' => '2024-10-01 00:00:01',
                ],
                [
                    'id' => '2',
                    'name' => 'premium',
                    'slug' => 'yearly',
                    'stripe_plan' => 'plan_GOdnLMq26Y9vFa',
                    'cost' => '99.95',
                    'description' => 'Yearly Plan',
                    'created_at' => '2024-10-01 00:00:01',
                    'updated_at' => '2024-10-01 00:00:01',
                ]


            ]);
        }
    }
}
