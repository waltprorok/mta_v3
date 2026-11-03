<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessHourControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'teacher' => true,
        ]);

        Teacher::factory()->create([
            'teacher_id' => $this->user->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_access_business_hours_page(): void
    {
        $this->get(route('teacher.hours'))
            ->assertRedirect();
    }

    public function test_guest_cannot_access_business_hours_data(): void
    {
        $this->getJson(route('teacher.hours.data'))
            ->assertUnauthorized();
    }

    public function test_guest_cannot_update_business_hours(): void
    {
        $this->putJson(route('teacher.hours.update'), [
            'day' => 1,
            'active' => true,
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ])->assertUnauthorized();
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function test_teacher_can_view_business_hours_page(): void
    {
        $this->actingAs($this->user)
            ->get(route('teacher.hours'))
            ->assertOk()
            ->assertViewIs('webapp.teacher.hours');
    }

    /*
    |--------------------------------------------------------------------------
    | Get Hours
    |--------------------------------------------------------------------------
    */

    public function test_teacher_can_retrieve_business_hours(): void
    {
        BusinessHours::factory()->create([
            'teacher_id' => $this->user->id,
            'day' => 1,
            'active' => true,
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ]);

        $this->actingAs($this->user)
            ->getJson(route('teacher.hours.data'))
            ->assertOk()
            ->assertJsonStructure([
                'hours' => [
                    '*' => [
                        'day',
                        'active',
                        'open_time',
                        'close_time',
                    ],
                ],
                'selectHours',
            ])
            ->assertJsonCount(1, 'hours')
            ->assertJsonPath('hours.0.day', 1);
    }

    public function test_teacher_receives_empty_hours_when_none_exist(): void
    {
        $this->actingAs($this->user)
            ->getJson(route('teacher.hours.data'))
            ->assertOk()
            ->assertJsonCount(0, 'hours');
    }

    public function test_teacher_cannot_view_another_teachers_hours(): void
    {
        $otherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        BusinessHours::factory()->create([
            'teacher_id' => $otherTeacher->id,
            'day' => 1,
        ]);

        $this->actingAs($this->user)
            ->getJson(route('teacher.hours.data'))
            ->assertOk()
            ->assertJsonCount(0, 'hours');
    }

    /*
    |--------------------------------------------------------------------------
    | Create / Update
    |--------------------------------------------------------------------------
    */

    public function test_teacher_can_create_business_hours(): void
    {
        $data = [
            'day' => 1,
            'active' => true,
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ];

        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), $data)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('hour.day', 1);

        $this->assertDatabaseHas('business_hours', [
            'teacher_id' => $this->user->id,
            'day' => 1,
            'active' => 1,
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ]);
    }

    public function test_teacher_can_update_existing_business_hours(): void
    {
        BusinessHours::factory()->create([
            'teacher_id' => $this->user->id,
            'day' => 1,
            'active' => true,
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ]);

        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 1,
                'active' => true,
                'open_time' => '10:00:00',
                'close_time' => '18:00:00',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('business_hours', [
            'teacher_id' => $this->user->id,
            'day' => 1,
            'open_time' => '10:00:00',
            'close_time' => '18:00:00',
        ]);

        $this->assertDatabaseCount('business_hours', 1);
    }

    public function test_teacher_can_deactivate_business_hours(): void
    {
        BusinessHours::factory()->create([
            'teacher_id' => $this->user->id,
            'day' => 1,
            'active' => true,
        ]);

        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 1,
                'active' => false,
                'open_time' => '09:00:00',
                'close_time' => '17:00:00',
            ])
            ->assertOk();

        $this->assertDatabaseHas('business_hours', [
            'teacher_id' => $this->user->id,
            'day' => 1,
            'active' => 0,
        ]);
    }

    public function test_updating_one_day_does_not_modify_other_days(): void
    {
        BusinessHours::factory()->create([
            'teacher_id' => $this->user->id,
            'day' => 1,
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
        ]);

        BusinessHours::factory()->create([
            'teacher_id' => $this->user->id,
            'day' => 2,
            'open_time' => '08:00:00',
            'close_time' => '16:00:00',
        ]);

        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 1,
                'active' => true,
                'open_time' => '10:00:00',
                'close_time' => '18:00:00',
            ])
            ->assertOk();

        $this->assertDatabaseHas('business_hours', [
            'teacher_id' => $this->user->id,
            'day' => 2,
            'open_time' => '08:00:00',
            'close_time' => '16:00:00',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    public function test_business_hours_requires_valid_fields(): void
    {
        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'day',
                'active',
                'open_time',
                'close_time',
            ]);
    }

    public function test_business_hours_rejects_invalid_day(): void
    {
        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 7,
                'active' => true,
                'open_time' => '09:00:00',
                'close_time' => '17:00:00',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['day']);
    }

    public function test_business_hours_rejects_invalid_time_format(): void
    {
        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 1,
                'active' => true,
                'open_time' => '9 AM',
                'close_time' => '5 PM',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'open_time',
                'close_time',
            ]);
    }

    public function test_business_hours_rejects_closing_before_opening(): void
    {
        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 1,
                'active' => true,
                'open_time' => '17:00:00',
                'close_time' => '09:00:00',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['close_time']);
    }

    /*
    |--------------------------------------------------------------------------
    | Data Isolation
    |--------------------------------------------------------------------------
    */

    public function test_teacher_cannot_update_another_teachers_hours(): void
    {
        $otherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        BusinessHours::factory()->create([
            'teacher_id' => $otherTeacher->id,
            'day' => 1,
            'open_time' => '08:00:00',
            'close_time' => '16:00:00',
        ]);

        $this->actingAs($this->user)
            ->putJson(route('teacher.hours.update'), [
                'day' => 1,
                'active' => true,
                'open_time' => '10:00:00',
                'close_time' => '18:00:00',
            ])
            ->assertOk();

        $this->assertDatabaseHas('business_hours', [
            'teacher_id' => $otherTeacher->id,
            'day' => 1,
            'open_time' => '08:00:00',
            'close_time' => '16:00:00',
        ]);

        $this->assertDatabaseHas('business_hours', [
            'teacher_id' => $this->user->id,
            'day' => 1,
            'open_time' => '10:00:00',
            'close_time' => '18:00:00',
        ]);
    }
}
