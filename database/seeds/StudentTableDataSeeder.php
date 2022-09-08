<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class StudentTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            // Create 100 students.
            factory(App\Models\Student::class, 100)->create();
        }
    }
}
