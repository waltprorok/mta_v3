<?php

use App\Models\BillingRate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class BillingRateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            BillingRate::query()->insert([
                [
                    'id' => '1',
                    'teacher_id' => '3',
                    'type' => 'monthly',
                    'amount' => '100',
                    'description' => 'Monthly Rate',
                    'default' => '1',
                    'active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '2',
                    'teacher_id' => '3',
                    'type' => 'hourly',
                    'amount' => '50',
                    'description' => 'Hourly Rate',
                    'default' => '0',
                    'active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '3',
                    'teacher_id' => '3',
                    'type' => 'lesson',
                    'amount' => '25',
                    'description' => 'Per Lesson',
                    'default' => '0',
                    'active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
