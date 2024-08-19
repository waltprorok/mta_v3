<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\InvoicePaymentRequest;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class InvoicePaymentRequestTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public $payment_type;
    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new InvoicePaymentRequest();
        $this->payment_type = factory(PaymentType::class)->create();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'payment_type_id' => $this->payment_type->id,
            'payment_information' => $this->faker->text,
            'payment' => 100.00,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'Payment Type null fail' =>
                ['payment_type_id', null],
            'Payment Type ID fail' =>
                ['payment_type_id', 2],
            'Payment null fail' =>
                ['payment', null],
            'Payment format fail' =>
                ['payment', 10.101],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function test_request_fail($key, $value)
    {
        $validator = Validator::make([
            $key => $value,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains($key, $validator->errors()->keys());
    }
}
