<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableDataSeeder::class);
        $this->call(PlanTableDataSeeder::class);
        $this->call(BlogTableDataSeeder::class);
        $this->call(ContactTableDataSeeder::class);
        $this->call(StudentTableDataSeeder::class);
        $this->call(TeachersTableDataSeeder::class);
        $this->call(BusinessHoursTableDataSeeder::class);
        $this->call(PaymentTypeTableSeeder::class);
        $this->call(BillingRateDataSeeder::class);
    }
}
