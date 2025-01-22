<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PaymentTypeTableSeeder;
use Tests\TestCase;

class PaymentTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_payment_type_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/payment-types');

        $response->assertOk();
    }

    public function test_payment_types_json()
    {
        $this->withoutMiddleware();

        $this->seed(PaymentTypeTableSeeder::class);

        $response = $this->get('/web/payment-types');

        $response->assertOk();
        $response->assertJsonCount(11);
        $response->assertJson([
            [
                'id' => 1,
                'name' => "Cash",
            ],
            [
                'id' => 2,
                'name' => "Check",
            ],
            [
                'id' => 3,
                'name' => "Credit Card",
            ],
            [
                'id' => 4,
                'name' => "Cash App",
            ],
            [
                'id' => 5,
                'name' => "Apple Pay",
            ],
            [
                'id' => 6,
                'name' => "Google Pay",
            ],
            [
                'id' => 7,
                'name' => "Stripe",
            ],
            [
                'id' => 8,
                'name' => "PayPal",
            ],
            [
                'id' => 9,
                'name' => "Venmo",
            ],
            [
                'id' => 10,
                'name' => "Zelle",
            ],
            [
                'id' => 11,
                'name' => "Other",
            ],
        ]);
    }

}
