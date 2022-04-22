<?php

use Illuminate\Database\Seeder;

class StudentTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 100 students.
        factory(App\Models\Student::class, 100)->create();
    }
}
