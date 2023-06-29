<?php

use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            Teacher::insert([
                [
                    'id' => '1',
                    'teacher_id' => '3',
                    'studio_name' => 'Teacher Zero Studio',
                    'first_name' => 'Teacher',
                    'last_name' => 'Zero',
                    'address' => '123 Main Street',
                    'address_2' => '',
                    'city' => 'Orlando',
                    'state' => 'FL',
                    'zip' => '34718',
                    'email' => 'teacher@domain.com',
                    'phone' => '4075551234',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '2',
                    'teacher_id' => '4',
                    'studio_name' => 'Teacher One Studio',
                    'first_name' => 'Teacher1',
                    'last_name' => 'One',
                    'address' => '222 Maple Road',
                    'address_2' => 'Apt B',
                    'city' => 'Clermont',
                    'state' => 'FL',
                    'zip' => '34711',
                    'email' => 'teacher1@domain.com',
                    'phone' => '3529781234',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
