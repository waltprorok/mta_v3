<?php

namespace Database\Seeders;

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
    public function run(): void
    {
        if (App::environment('local')) {
            // Create 100 contacts.
            Contact::factory()->count(100)->create();
        }
    }
}
