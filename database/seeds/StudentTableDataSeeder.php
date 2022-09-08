<?php

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class StudentTableDataSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            // Create 100 students.
            factory(Student::class, 100)->create();
        }
    }
}
