<?php

use App\Models\BusinessHours;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class BusinessHoursTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            // Create 7 business hour records Mon through Sun.
            factory(BusinessHours::class, 7)->create();
        }
    }
}
