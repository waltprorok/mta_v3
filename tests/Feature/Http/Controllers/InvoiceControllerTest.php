<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PaymentTypeTableSeeder;
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

    public function test_index_page_route_view()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get(route('invoice.index'));

        $response->assertOk()
            ->assertViewIs('webapp.invoice.index');
    }

    public function test_index_page_url_view()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get('/invoice');

        $response->assertOk()
            ->assertViewIs('webapp.invoice.index');
    }

    public function test_index_json_response()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create(['teacher' => 1]);
        $student = factory(Student::class)->create(['teacher_id' => $user->id]);
        $paymentType = factory(PaymentType::class)->create();

        factory(Invoice::class)->create(['teacher_id' => $user->id, 'student_id' => $student->id, 'payment_type_id' => $paymentType->id]);

        $response = $this->actingAs($user)->get('/web/invoice');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'id' => 1,
                "student_id" => "1",
                "teacher_id" => "1",
                "lesson_id" => "",
                "subtotal" => 0,
                "discount" => 0,
                "total" => 0,
                "balance_due" => 0,
                "payment" => 0,
                "adjustments" => 0,
                "payment_type_id" => 1,
                "check_number" => null,
                "payment_information" => null,
                "due_date" => null,
                "is_paid" => false,
            ]
        ]);
    }


}
