<?php

use Illuminate\Database\Seeder;
use App\Student;

class StudentTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::insert([
            [
                'id' => '1',
                'teacher_id' => '3',
                'first_name' => 'Dave',
                'last_name' => 'Matthews',
                'email' => 'davem_teacher1@email.com',
                'status' => 'Active',
            ],
            [
                'id' => '2',
                'teacher_id' => '3',
                'first_name' => 'Dimebag',
                'last_name' => 'Darrell',
                'email' => 'dimebag_teacher1@email.com',
                'status' => 'Active',
            ],
            [
                'id' => '3',
                'teacher_id' => '4',
                'first_name' => 'James',
                'last_name' => 'Hetfield',
                'email' => 'james_h_teacher2@email.com',
                'status' => 'Active',
            ],
            [
                'id' => '4',
                'teacher_id' => '4',
                'first_name' => 'Dave',
                'last_name' => 'Mustaine',
                'email' => 'dave_m_teacher2@email.com',
                'status' => 'Active',
            ]
        ]);
    }
}
