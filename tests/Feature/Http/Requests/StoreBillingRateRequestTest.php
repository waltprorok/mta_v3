<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreBillingRateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreBillingRateRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreBillingRateRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'type' => 'lesson',
            'amount' => 25.00,
            'description' => $this->faker->text,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_type_fail()
    {
        $validator = Validator::make([
            'type' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('type', $validator->errors()->keys());
    }

    public function test_type_num_fail()
    {
        $validator = Validator::make([
            'type' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('type', $validator->errors()->keys());
    }

    public function test_amount_fail()
    {
        $validator = Validator::make([
            'amount' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('amount', $validator->errors()->keys());
    }

    public function test_amount_format_fail()
    {
        $validator = Validator::make([
            'amount' => 10.101,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('amount', $validator->errors()->keys());
    }
}
