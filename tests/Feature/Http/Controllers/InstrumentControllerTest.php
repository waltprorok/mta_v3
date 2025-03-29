<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\InstrumentController;
use App\Models\Instrument;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstrumentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected InstrumentController $instrumentController;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['teacher' => true]);
        $this->instrumentController = new InstrumentController();
    }

    public function test_instrument_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('teacher.instruments'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.instrument');
    }

    public function test_instrument_index_url_200()
    {
        $response = $this->actingAs($this->user)->get('/teacher/instruments');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.instrument');
    }

    public function test_instrument_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/instrument');

        $response->assertOk();
    }

    public function test_instrument_factory()
    {
        Instrument::factory()->create();

        $this->assertDatabaseCount('instruments', 1);
    }

    public function test_instrument_show()
    {
        $instrument = Instrument::factory()->create();

        $response = $this->actingAs($this->user)->get('/web/instrument/' . $instrument->id);

        $response->assertOk();
        $response->assertJsonCount(5);
    }

    public function test_instrument_create_success()
    {
        $this->actingAs($this->user)->post('/web/instrument', [
            'teacher_id' => $this->user->id,
            'name' => 'Drums'
        ]);

        $this->assertDatabaseCount('instruments', 1);
    }

    public function test_instrument_update_success()
    {
        $instrument = Instrument::factory()->create();

        $response = $this->actingAs($this->user)->put('/web/instrument/' . $instrument->id, [
           'name' => 'Vocals'
        ]);

        $response->assertOk();
    }

    public function test_instrument_delete_success()
    {
        $instrument = Instrument::factory()->create();

        $response = $this->actingAs($this->user)->delete('/web/instrument/' . $instrument->id);

        $response->assertOk();

        $this->assertDatabaseCount('instruments', 0);
    }

    public function test_destroy_method_success()
    {
        $mockInstrument = $this->createMock(Instrument::class);
        $mockInstrument->id = 1;
        $mockInstrument->name = 'Drums';

        $mockController = $this->getMockBuilder(InstrumentController::class)
            ->onlyMethods(['destroy'])
            ->getMock();

        $mockController->method('destroy')->willThrowException(new Exception());

        $this->app->instance(InstrumentController::class, $mockController);

        $result = $this->instrumentController->destroy($mockInstrument);

        $this->assertEquals(200, $result->status());
    }
}
