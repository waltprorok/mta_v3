<?php

namespace Feature\Http\Controllers;

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstrumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
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
        factory(Instrument::class, 1)->create();

        $this->assertDatabaseCount('instruments', 1);
    }

    public function test_instrument_show()
    {
        $instrument = factory(Instrument::class)->create();

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
        $instrument = factory(instrument::class)->create();

        $response = $this->actingAs($this->user)->put('/web/instrument/' . $instrument->id, [
           'name' => 'Vocals'
        ]);

        $response->assertOk();
    }

    public function test_instrument_delete_success()
    {
        $instrument = factory(Instrument::class)->create();

        $response = $this->actingAs($this->user)->delete('/web/instrument/' . $instrument->id);

        $response->assertOk();

        $this->assertDatabaseCount('instruments', 0);
    }
}
