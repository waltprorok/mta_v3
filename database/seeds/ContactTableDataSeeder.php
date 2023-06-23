<?php

use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class ContactTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            // Create 100 contacts.
            factory(Contact::class, 100)->create();
        }
    }
}
