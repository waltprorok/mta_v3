<?php

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::query()->insert(
            [
                ['name' => 'Cash', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Check', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Credit Card', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Cash App', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Apple Pay', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Google Pay', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Stripe', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'PayPal', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Venmo', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Zelle', 'created_at' => now(), 'updated_at' => now()],
            ]
        );
    }
}
