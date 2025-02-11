<?php

namespace Database\Seeders;

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
    public function run(): void
    {
        if (App::environment('local')) {
            // Create 7 business hour records Mon through Sun.
            BusinessHours::factory()->count(7)->create();
        }
    }
}
