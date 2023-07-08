<?php

use App\Models\BusinessHours;
use Carbon\Carbon;
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
            BusinessHours::insert([
                [
                    'id' => '1',
                    'teacher_id' => '3',
                    'day' => '0',
                    'active' => '0',
                    'open_time' => '08:00',
                    'close_time' => '08:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '2',
                    'teacher_id' => '3',
                    'day' => '1',
                    'active' => '1',
                    'open_time' => '16:00',
                    'close_time' => '20:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '3',
                    'teacher_id' => '3',
                    'day' => '2',
                    'active' => '1',
                    'open_time' => '16:00',
                    'close_time' => '20:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '4',
                    'teacher_id' => '3',
                    'day' => '3',
                    'active' => '1',
                    'open_time' => '16:00',
                    'close_time' => '20:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '5',
                    'teacher_id' => '3',
                    'day' => '4',
                    'active' => '1',
                    'open_time' => '16:00',
                    'close_time' => '20:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '6',
                    'teacher_id' => '3',
                    'day' => '5',
                    'active' => '1',
                    'open_time' => '10:00',
                    'close_time' => '14:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id' => '7',
                    'teacher_id' => '3',
                    'day' => '6',
                    'active' => '0',
                    'open_time' => '08:00',
                    'close_time' => '08:00',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
