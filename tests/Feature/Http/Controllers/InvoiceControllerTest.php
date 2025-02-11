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

        $user = User::factory()->create(['teacher' => true]);

        $response = $this->actingAs($user)->get(route('invoice.index'));

        $response->assertOk()
            ->assertViewIs('webapp.invoice.index');
    }

    public function test_index_page_url_view()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create(['teacher' => true]);

        $response = $this->actingAs($user)->get('/invoice');

        $response->assertOk()
            ->assertViewIs('webapp.invoice.index');
    }

    public function test_index_json_response()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create(['teacher' => true]);
        $student = Student::factory()->create(['teacher_id' => $user->id]);
        $paymentType = PaymentType::factory()->create();

        Invoice::factory()->create(['teacher_id' => $user->id, 'student_id' => $student->id, 'payment_type_id' => $paymentType->id]);

        $response = $this->actingAs($user)->get('/web/invoice');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }


}
