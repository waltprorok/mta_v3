<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use CustodiaForks\Laravel10FullCalendar\Calendar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $anotherTeacher;
    private Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->lesson = Lesson::factory()->create([
            'teacher_id' => $this->teacher->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDay()->addMinutes(30),
            'complete' => false,
            'status' => Lesson::STATUS[0],
        ]);

        $this->actingAs($this->teacher);
    }

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */

    public function test_calendar_index_view_200(): void
    {
        $response = $this->get(route('calendar.index'));

        $response->assertOk()
            ->assertViewIs('webapp.calendar.index')
            ->assertViewHas('calendar');
    }

    public function test_calendar_returns_calendar_instance(): void
    {
        $response = $this->get(route('calendar.index'));

        $response->assertOk()
            ->assertViewHas('calendar', function ($calendar) {
                return $calendar instanceof Calendar;
            });
    }

    public function test_calendar_requires_authentication(): void
    {
        auth()->logout();

        $response = $this->get(route('calendar.index'));

        $response->assertRedirect(route('login'));
    }

    /*
    |--------------------------------------------------------------------------
    | Lesson List
    |--------------------------------------------------------------------------
    */

    public function test_teacher_can_list_own_lessons(): void
    {
        Lesson::factory()->create([
            'teacher_id' => $this->anotherTeacher->id,
            'start_date' => now()->addDay(),
        ]);

        $fromDate = now()->format('D M d Y');
        $toDate = now()->addDays(7)->format('D M d Y');

        $response = $this->getJson(
            "/lessons/list/{$fromDate}/{$toDate}"
        );

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_teacher_cannot_list_another_teachers_lessons(): void
    {
        $this->actingAs($this->anotherTeacher);

        $fromDate = now()->format('D M d Y');
        $toDate = now()->addDays(7)->format('D M d Y');

        $response = $this->getJson(
            "/lessons/list/{$fromDate}/{$toDate}"
        );

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Lesson
    |--------------------------------------------------------------------------
    */

    public function test_teacher_can_mark_lesson_complete(): void
    {
        $response = $this->patchJson(
            '/lessons/update/' . $this->lesson->id,
            [
                'complete' => true,
            ]
        );

        $response->assertOk()
            ->assertJsonPath('complete', true);

        $this->assertDatabaseHas('lessons', [
            'id' => $this->lesson->id,
            'complete' => true,
        ]);
    }

    public function test_teacher_can_mark_lesson_incomplete(): void
    {
        $this->lesson->update([
            'complete' => true,
        ]);

        $response = $this->patchJson(
            '/lessons/update/' . $this->lesson->id,
            [
                'complete' => false,
            ]
        );

        $response->assertOk()
            ->assertJsonPath('complete', false);

        $this->assertDatabaseHas('lessons', [
            'id' => $this->lesson->id,
            'complete' => false,
        ]);
    }

    public function test_teacher_cannot_update_another_teachers_lesson(): void
    {
        $response = $this->actingAs($this->anotherTeacher)
            ->patchJson(
                '/lessons/update/' . $this->lesson->id,
                [
                    'complete' => true,
                ]
            );

        $response->assertNotFound();

        $this->assertDatabaseHas('lessons', [
            'id' => $this->lesson->id,
            'complete' => false,
        ]);
    }

    public function test_update_requires_complete_field(): void
    {
        $response = $this->patchJson(
            '/lessons/update/' . $this->lesson->id,
            []
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['complete']);
    }

    /*
    |--------------------------------------------------------------------------
    | Complete Past Lessons
    |--------------------------------------------------------------------------
    */

    public function test_complete_past_marks_past_lesson_complete(): void
    {
        $pastLesson = Lesson::factory()->create([
            'teacher_id' => $this->teacher->id,
            'start_date' => now()->subDays(2),
            'complete' => false,
            'status' => Lesson::STATUS[0],
        ]);

        $response = $this->putJson(
            '/lessons/update/past',
            [
                ['id' => $pastLesson->id],
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('lessons', [
            'id' => $pastLesson->id,
            'complete' => true,
        ]);
    }

    public function test_complete_past_does_not_complete_future_lessons(): void
    {
        $response = $this->putJson(
            '/lessons/update/past',
            [
                ['id' => $this->lesson->id],
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('lessons', [
            'id' => $this->lesson->id,
            'complete' => false,
        ]);
    }

    public function test_complete_past_skips_cancelled_lessons(): void
    {
        $cancelledLesson = Lesson::factory()->create([
            'teacher_id' => $this->teacher->id,
            'start_date' => now()->subDays(2),
            'complete' => false,
            'status' => Lesson::STATUS[2],
        ]);

        $response = $this->putJson(
            '/lessons/update/past',
            [
                ['id' => $cancelledLesson->id],
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('lessons', [
            'id' => $cancelledLesson->id,
            'complete' => false,
        ]);
    }

    public function test_complete_past_does_not_update_another_teachers_lessons(): void
    {
        $pastLesson = Lesson::factory()->create([
            'teacher_id' => $this->anotherTeacher->id,
            'start_date' => now()->subDays(2),
            'complete' => false,
            'status' => Lesson::STATUS[0],
        ]);

        $response = $this->putJson(
            '/lessons/update/past',
            [
                ['id' => $pastLesson->id],
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('lessons', [
            'id' => $pastLesson->id,
            'complete' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Update
    |--------------------------------------------------------------------------
    */

    public function test_bulk_update_marks_multiple_lessons_complete(): void
    {
        $secondLesson = Lesson::factory()->create([
            'teacher_id' => $this->teacher->id,
            'complete' => false,
            'status' => Lesson::STATUS[0],
        ]);

        $response = $this->patchJson(
            '/lessons/bulk-update',
            [
                'ids' => [
                    $this->lesson->id,
                    $secondLesson->id,
                ],
                'complete' => true,
            ]
        );

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('updated', 2);

        $this->assertDatabaseHas('lessons', [
            'id' => $this->lesson->id,
            'complete' => true,
        ]);

        $this->assertDatabaseHas('lessons', [
            'id' => $secondLesson->id,
            'complete' => true,
        ]);
    }

    public function test_bulk_update_excludes_cancelled_lessons(): void
    {
        $cancelledLesson = Lesson::factory()->create([
            'teacher_id' => $this->teacher->id,
            'complete' => false,
            'status' => Lesson::STATUS[2],
        ]);

        $response = $this->patchJson(
            '/lessons/bulk-update',
            [
                'ids' => [
                    $this->lesson->id,
                    $cancelledLesson->id,
                ],
                'complete' => true,
            ]
        );

        $response->assertOk()
            ->assertJsonPath('updated', 1);

        $this->assertDatabaseHas('lessons', [
            'id' => $cancelledLesson->id,
            'complete' => false,
        ]);
    }

    public function test_bulk_update_only_updates_own_lessons(): void
    {
        $otherLesson = Lesson::factory()->create([
            'teacher_id' => $this->anotherTeacher->id,
            'complete' => false,
            'status' => Lesson::STATUS[0],
        ]);

        $response = $this->patchJson(
            '/lessons/bulk-update',
            [
                'ids' => [
                    $this->lesson->id,
                    $otherLesson->id,
                ],
                'complete' => true,
            ]
        );

        $response->assertOk()
            ->assertJsonPath('updated', 1);

        $this->assertDatabaseHas('lessons', [
            'id' => $otherLesson->id,
            'complete' => false,
        ]);
    }

    public function test_bulk_update_requires_ids(): void
    {
        $response = $this->patchJson(
            '/lessons/bulk-update',
            [
                'complete' => true,
            ]
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    }

    public function test_bulk_update_requires_complete(): void
    {
        $response = $this->patchJson(
            '/lessons/bulk-update',
            [
                'ids' => [$this->lesson->id],
            ]
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['complete']);
    }

    public function test_bulk_update_rejects_duplicate_ids(): void
    {
        $response = $this->patchJson(
            '/lessons/bulk-update',
            [
                'ids' => [
                    $this->lesson->id,
                    $this->lesson->id,
                ],
                'complete' => true,
            ]
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids.0', 'ids.1']);
    }
}
