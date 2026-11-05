<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstrumentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'teacher' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Views
    |--------------------------------------------------------------------------
    */

    public function test_instrument_index_view_200(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('teacher.instruments'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.instrument');
    }

    public function test_instrument_index_url_200(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/teacher/instruments');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.instrument');
    }

    public function test_instrument_index_web_url_200(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/web/instrument');

        $response->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Factory
    |--------------------------------------------------------------------------
    */

    public function test_instrument_factory(): void
    {
        Instrument::factory()->create();

        $this->assertDatabaseCount('instruments', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Show
    |--------------------------------------------------------------------------
    */

    public function test_instrument_show(): void
    {
        $instrument = Instrument::factory()->create([
            'teacher_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/web/instrument/' . $instrument->id);

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $instrument->id,
                'name' => $instrument->name,
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Creation
    |--------------------------------------------------------------------------
    */

    public function test_instrument_create_success(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', [
                'name' => 'Drums',
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('instruments', [
            'teacher_id' => $this->user->id,
            'name' => 'Drums',
        ]);

        $this->assertDatabaseCount('instruments', 1);
    }

    public function test_instrument_create_capitalizes_name(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', [
                'name' => 'acoustic guitar',
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('instruments', [
            'teacher_id' => $this->user->id,
            'name' => 'Acoustic Guitar',
        ]);
    }

    public function test_instrument_create_trims_name(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', [
                'name' => '  Piano  ',
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('instruments', [
            'teacher_id' => $this->user->id,
            'name' => 'Piano',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Creation Validation
    |--------------------------------------------------------------------------
    */

    public function test_instrument_create_requires_name(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('instruments', 0);
    }

    public function test_instrument_create_rejects_empty_name(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', [
                'name' => '',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('instruments', 0);
    }

    public function test_instrument_create_rejects_name_over_50_characters(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', [
                'name' => str_repeat('A', 51),
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('instruments', 0);
    }

    public function test_instrument_create_rejects_non_string_name(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/web/instrument', [
                'name' => ['Piano'],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('instruments', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Update
    |--------------------------------------------------------------------------
    */

    public function test_instrument_update_success(): void
    {
        $instrument = Instrument::factory()->create([
            'teacher_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->patchJson('/web/instrument/' . $instrument->id, [
                'name' => 'Vocals',
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('instruments', [
            'id' => $instrument->id,
            'teacher_id' => $this->user->id,
            'name' => 'Vocals',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Instrument Delete
    |--------------------------------------------------------------------------
    */

    public function test_instrument_delete_success(): void
    {
        $instrument = Instrument::factory()->create([
            'teacher_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete('/web/instrument/' . $instrument->id);

        $response->assertOk();

        $this->assertDatabaseMissing('instruments', [
            'id' => $instrument->id,
        ]);

        $this->assertDatabaseCount('instruments', 0);
    }
}
