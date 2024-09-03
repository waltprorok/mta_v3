<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'id' => '1',
                'first_name' => 'Walter',
                'last_name' => 'Prorok',
                'email' => 'waltprorok@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('Admin213!'),
                'admin' => 1,
                'teacher' => 0,
                'student' => 0,
                'parent' => 0,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => null,
            ],
            [
                'id' => '2',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@domain.com',
                'email_verified_at' => null,
                'password' => bcrypt('Admin213!'),
                'admin' => 1,
                'teacher' => 0,
                'student' => 0,
                'parent' => 0,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => null,
            ],
            [
                'id' => '3',
                'first_name' => 'Teacher',
                'last_name' => 'Test',
                'email' => 'teacher@domain.com',
                'email_verified_at' => null,
                'password' => bcrypt('123456'),
                'admin' => 0,
                'teacher' => 1,
                'student' => 0,
                'parent' => 0,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => Carbon::now()->addDays(30),
            ],
            [
                'id' => '4',
                'first_name' => 'Teacher1',
                'last_name' => 'Test',
                'email' => 'teacher1@domain.com',
                'email_verified_at' => null,
                'password' => bcrypt('123456'),
                'admin' => 0,
                'teacher' => 1,
                'student' => 0,
                'parent' => 0,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => Carbon::now()->addDays(30),
            ],
            [
                'id' => '5',
                'first_name' => 'Teacher2',
                'last_name' => 'Test',
                'email' => 'teacher2@domain.com',
                'email_verified_at' => null,
                'password' => bcrypt('123456'),
                'admin' => 0,
                'teacher' => 1,
                'student' => 0,
                'parent' => 0,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => Carbon::now()->addDays(30),
            ],
            [
                'id' => '6',
                'first_name' => 'Student',
                'last_name' => 'Test',
                'email' => 'student@domain.com',
                'email_verified_at' => null,
                'password' => bcrypt('123456'),
                'admin' => 0,
                'teacher' => 0,
                'student' => 1,
                'parent' => 0,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => null,
            ],
            [
                'id' => '7',
                'first_name' => 'Parent',
                'last_name' => 'Test',
                'email' => 'parent@domain.com',
                'email_verified_at' => null,
                'password' => bcrypt('123456'),
                'admin' => 0,
                'teacher' => 0,
                'student' => 0,
                'parent' => 1,
                'terms' => 1,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'stripe_id' => null,
                'card_brand' => null,
                'card_last_four' => null,
                'trial_ends_at' => null,
            ]
        ]);

        if (App::environment('local')) {
            // Create 150 student users.
            factory(User::class, 150)->create(['student' => true]);
        }
    }
}
