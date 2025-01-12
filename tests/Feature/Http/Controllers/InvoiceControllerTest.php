<?php

namespace Feature\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_payment_types_json()
    {
        $this->withoutMiddleware();

        factory(PaymentType::class)->create();

        $response = $this->get('/web/payment-types');

        $response->assertStatus(200);

        $response->assertJson([[
            'id' => 1,
            'name' => "Cash",
        ]]);
    }
}
