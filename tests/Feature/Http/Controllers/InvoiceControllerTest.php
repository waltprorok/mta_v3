<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_index_page_route_view()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get(route('invoice.index'));

        $response->assertOk()
            ->assertViewIs('webapp.invoice.index');
    }

    public function test_index_page_url_view()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get('/invoice');

        $response->assertOk()
            ->assertViewIs('webapp.invoice.index');
    }

    public function test_index_json_response()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create(['teacher' => 1]);
        $student = Student::factory()->create(['teacher_id' => $user->id]);
        $paymentType = PaymentType::factory()->create();

        Invoice::factory()->create(['teacher_id' => $user->id, 'student_id' => $student->id, 'payment_type_id' => $paymentType->id]);

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
