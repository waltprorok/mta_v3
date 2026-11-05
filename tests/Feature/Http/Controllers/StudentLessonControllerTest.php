<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BillingRate;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\User;
use App\Services\LessonAvailabilityService;
use App\Services\LessonSchedulingService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StudentLessonControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $studentUser;
    private Student $student;
    private Lesson $lesson;
    private BillingRate $billingRate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->studentUser = User::factory()->create([
            'student' => true,
        ]);

        $this->student = Student::factory()->create([
            'student_id' => $this->studentUser->id,
            'teacher_id' => $this->teacher->id,
        ]);

        $this->billingRate = BillingRate::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        $this->lesson = Lesson::factory()->create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'billing_rate_id' => $this->billingRate->id,
        ]);

        $this->actingAs($this->teacher);
    }

    /*
    |--------------------------------------------------------------------------
    | Student Schedule
    |--------------------------------------------------------------------------
    */

    public function test_student_schedule_index_returns_200(): void
    {
        $response = $this->get(
            route('student.schedule', ['id' => $this->student->id])
        );

        $response->assertOk()
            ->assertViewIs('webapp.student.schedule')
            ->assertViewHas('student');
    }

    public function test_student_schedule_returns_correct_student(): void
    {
        $response = $this->get(
            route('student.schedule', ['id' => $this->student->id])
        );

        $response->assertOk()
            ->assertViewHas('student', function ($student) {
                return $student->id === $this->student->id
                    && $student->teacher_id === $this->teacher->id;
            });
    }

    public function test_teacher_cannot_view_another_teachers_student_schedule(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->get(route('student.schedule', [
                'id' => $this->student->id,
            ]));

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Student Schedule JSON
    |--------------------------------------------------------------------------
    */

    public function test_get_student_returns_schedule_data(): void
    {
        $this->mock(LessonAvailabilityService::class, function ($mock) {
            $mock->shouldReceive('availableTimes')
                ->once()
                ->with($this->teacher->id, now()->toDateString())
                ->andReturn([]);
        });

        $response = $this->getJson(
            '/web/student/lesson/' . $this->student->id
        );

        $response->assertOk()
            ->assertJsonStructure([
                'student',
                'businessHours',
                'billingRates',
                'holidays',
                'studentScheduled',
                'allTimes',
                'lessons',
            ])
            ->assertJsonPath('student.id', $this->student->id);
    }

    public function test_get_student_returns_404_for_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->getJson('/web/student/lesson/' . $this->student->id);

        $response->assertNotFound();
    }

    public function test_get_student_uses_selected_date(): void
    {
        $selectedDate = '2026-12-15';

        $this->mock(LessonAvailabilityService::class, function ($mock) use ($selectedDate) {
            $mock->shouldReceive('availableTimes')
                ->once()
                ->with($this->teacher->id, $selectedDate)
                ->andReturn([]);
        });

        $response = $this->getJson(
            '/web/student/lesson/' . $this->student->id . '/' . $selectedDate
        );

        $response->assertOk()
            ->assertJsonPath('student.id', $this->student->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Reschedule Lesson
    |--------------------------------------------------------------------------
    */

    public function test_lesson_reschedule_view_returns_200(): void
    {
        $response = $this->get(
            route('student.reschedule', [
                'id' => $this->lesson->id,
            ])
        );

        $response->assertOk()
            ->assertViewIs('webapp.student.reschedule')
            ->assertViewHas('student');
    }

    public function test_teacher_cannot_reschedule_another_teachers_lesson(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->get(route('student.reschedule', [
                'id' => $this->lesson->id,
            ]));

        $response->assertNotFound();
    }

    public function test_get_lesson_reschedule_returns_json(): void
    {
        $selectedDate = Carbon::parse(
            $this->lesson->start_date
        )->toDateString();

        $this->mock(LessonAvailabilityService::class, function ($mock) use ($selectedDate) {
            $mock->shouldReceive('availableTimes')
                ->once()
                ->with($this->teacher->id, $selectedDate, $this->lesson->id)
                ->andReturn([]);
        });

        $response = $this->getJson(
            '/web/lesson/reschedule/' . $this->lesson->id
        );

        $response->assertOk()
            ->assertJsonStructure([
                'lesson',
                'allTimes',
                'startDate',
                'businessHours',
                'holidays',
                'billingRates',
            ])
            ->assertJsonPath('lesson.id', $this->lesson->id)
            ->assertJsonPath('startDate', null);
    }

    public function test_get_lesson_reschedule_uses_selected_date(): void
    {
        $selectedDate = '2026-12-15';

        $this->mock(LessonAvailabilityService::class, function ($mock) use ($selectedDate) {
            $mock->shouldReceive('availableTimes')
                ->once()
                ->with($this->teacher->id, $selectedDate, $this->lesson->id)
                ->andReturn([]);
        });

        $response = $this->getJson(
            '/web/lesson/reschedule/' . $this->lesson->id . '/' . $selectedDate
        );

        $response->assertOk()
            ->assertJsonPath('lesson.id', $this->lesson->id)
            ->assertJsonPath('startDate', $selectedDate);
    }

    public function test_teacher_cannot_get_another_teachers_lesson_reschedule_data(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->getJson('/web/lesson/reschedule/' . $this->lesson->id);

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Store Lessons
    |--------------------------------------------------------------------------
    */

    public function test_store_creates_lessons_successfully(): void
    {
        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andReturn(collect([
                    new Lesson(),
                ]));
        });

        $response = $this->postJson(
            route('student.schedule.save'),
            $this->validStorePayload()
        );

        $response->assertCreated()
            ->assertJson([
                'message' => 'Lessons scheduled successfully.',
                'created' => 1,
            ]);
    }

    public function test_store_returns_400_when_scheduling_fails(): void
    {
        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andThrow(new Exception('Scheduling failed'));
        });

        $response = $this->postJson(
            route('student.schedule.save'),
            $this->validStorePayload()
        );

        $response->assertBadRequest()
            ->assertJson([
                'message' => 'Unable to schedule lessons.',
            ]);
    }

    public function test_teacher_cannot_schedule_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldNotReceive('create');
        });

        $response = $this->actingAs($anotherTeacher)
            ->postJson(
                route('student.schedule.save'),
                $this->validStorePayload()
            );

        $response->assertNotFound();
    }

    public function test_store_returns_422_when_validation_fails(): void
    {
        $response = $this->postJson(
            route('student.schedule.save'),
            []
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'student_id',
                'billing_rate_id',
                'title',
                'color',
                'start_date',
                'start_time',
                'end_time',
                'recurrence',
            ], 'error');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Lessons
    |--------------------------------------------------------------------------
    */

    public function test_update_lesson_successfully(): void
    {
        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldReceive('update')
                ->once()
                ->andReturnNull();
        });

        $response = $this->patchJson(
            '/web/lesson/reschedule/update',
            $this->validUpdatePayload()
        );

        $response->assertOk()
            ->assertExactJson([]);
    }

    public function test_update_returns_400_when_scheduling_fails(): void
    {
        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldReceive('update')
                ->once()
                ->andThrow(new Exception('Unable to update lesson'));
        });

        $response = $this->patchJson(
            '/web/lesson/reschedule/update',
            $this->validUpdatePayload()
        );

        $response->assertBadRequest()
            ->assertJson([
                'message' => 'Unable to update lesson',
            ]);
    }

    public function test_teacher_cannot_update_another_teachers_lesson(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldNotReceive('update');
        });

        $response = $this->actingAs($anotherTeacher)
            ->patchJson(
                '/web/lesson/reschedule/update',
                $this->validUpdatePayload()
            );

        $response->assertNotFound();
    }

    public function test_update_returns_422_when_validation_fails(): void
    {
        $response = $this->patchJson(
            '/web/lesson/reschedule/update',
            []
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'id',
                'student_id',
                'billing_rate_id',
                'title',
                'color',
                'start_date',
                'recurrence',
            ], 'error');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Lessons
    |--------------------------------------------------------------------------
    */

    public function test_destroy_deletes_lesson_successfully(): void
    {
        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldReceive('delete')
                ->once()
                ->andReturnNull();
        });

        $response = $this->deleteJson(
            '/web/lesson/delete/' . $this->lesson->id
        );

        $response->assertOk()
            ->assertExactJson([]);
    }

    public function test_destroy_returns_400_when_deletion_fails(): void
    {
        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldReceive('delete')
                ->once()
                ->andThrow(new Exception('Unable to delete lesson'));
        });

        $response = $this->deleteJson(
            '/web/lesson/delete/' . $this->lesson->id
        );

        $response->assertBadRequest()
            ->assertExactJson([]);
    }

    public function test_teacher_cannot_delete_another_teachers_lesson(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->mock(LessonSchedulingService::class, function ($mock) {
            $mock->shouldNotReceive('delete');
        });

        $response = $this->actingAs($anotherTeacher)
            ->deleteJson('/web/lesson/delete/' . $this->lesson->id);

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Test Payloads
    |--------------------------------------------------------------------------
    */

    private function validStorePayload(): array
    {
        return [
            'student_id' => $this->student->id,
            'billing_rate_id' => $this->billingRate->id,
            'title' => 'Piano Lesson',
            'color' => '#007bff',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'recurrence' => 'none',
        ];
    }

    private function validUpdatePayload(): array
    {
        return [
            'id' => $this->lesson->id,
            'student_id' => $this->student->id,
            'billing_rate_id' => $this->billingRate->id,
            'title' => 'Piano Lesson',
            'color' => '#007bff',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => 30,
            'interval' => 30,
            'recurrence' => 'none',
            'notes' => 'Updated piano lesson',
            'status' => 'scheduled',
        ];
    }
}
