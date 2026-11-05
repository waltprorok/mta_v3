<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\WelcomeNewUserMail;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $teacher;
    private Student $studentRecord;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->student = User::factory()->create([
            'student' => true,
        ]);

        $this->teacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->studentRecord = Student::factory()->create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Student Profile
    |--------------------------------------------------------------------------
    */

    public function test_profile_index_view_200(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.profile', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewIs('webapp.student.profile')
            ->assertViewHas('student')
            ->assertViewHas('studentJson');
    }

    public function test_profile_index_view_web_200(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get('/students/profile/' . $this->studentRecord->id);

        $response->assertOk()
            ->assertViewIs('webapp.student.profile');
    }

    public function test_profile_show_view_200(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.edit', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewIs('webapp.student.edit')
            ->assertViewHas('student')
            ->assertViewHas('instruments');
    }

    public function test_profile_returns_correct_student(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.profile', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewHas('student', function ($student) {
                return $student->id === $this->studentRecord->id
                    && $student->teacher_id === $this->teacher->id;
            });
    }

    public function test_edit_returns_correct_student(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.edit', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewHas('student', function ($student) {
                return $student->id === $this->studentRecord->id
                    && $student->teacher_id === $this->teacher->id;
            });
    }

    public function test_profile_returns_not_found_for_invalid_student(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.profile', [
                'id' => 999999,
            ]));

        $response->assertNotFound();
    }

    public function test_edit_returns_not_found_for_invalid_student(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.edit', [
                'id' => 999999,
            ]));

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_access_student_profile(): void
    {
        $response = $this->get(route('student.profile', [
            'id' => $this->studentRecord->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_student_edit(): void
    {
        $response = $this->get(route('student.edit', [
            'id' => $this->studentRecord->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    /*
    |--------------------------------------------------------------------------
    | Teacher Authorization
    |--------------------------------------------------------------------------
    */

    public function test_teacher_cannot_view_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->get(route('student.profile', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertNotFound();
    }

    public function test_teacher_cannot_edit_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->get(route('student.edit', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Student Creation
    |--------------------------------------------------------------------------
    */

    public function test_store_creates_student_without_parent(): void
    {
        Teacher::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        $this->actingAs($this->teacher);

        $response = $this->postJson('/web/student-save', [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'status' => Student::ACTIVE,
            'add_parent' => false,
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'The student was added.',
            ]);

        $this->assertDatabaseHas('students', [
            'teacher_id' => $this->teacher->id,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'student' => true,
        ]);

        Mail::assertQueued(WelcomeNewUserMail::class, 1);
    }

    public function test_store_creates_student_with_parent(): void
    {
        Teacher::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        $this->actingAs($this->teacher);

        $response = $this->postJson('/web/student-save', [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'status' => Student::ACTIVE,
            'add_parent' => true,
            'parent_first_name' => 'Jane',
            'parent_last_name' => 'Smith',
            'parent_email' => 'jane@example.com',
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'The student was added.',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'parent' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'student' => true,
        ]);

        $parent = User::where('email', 'jane@example.com')->firstOrFail();

        $this->assertDatabaseHas('students', [
            'teacher_id' => $this->teacher->id,
            'parent_id' => $parent->id,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
        ]);

        Mail::assertQueued(WelcomeNewUserMail::class, 2);
    }

    public function test_store_does_not_duplicate_existing_student_user(): void
    {
        Teacher::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        $this->actingAs($this->teacher);

        $email = 'existing@example.com';

        $existingUser = User::factory()->create([
            'email' => $email,
            'student' => true,
        ]);

        $response = $this->postJson('/web/student-save', [
            'first_name' => 'Existing',
            'last_name' => 'Student',
            'email' => $email,
            'status' => Student::ACTIVE,
            'add_parent' => false,
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'The student was added.',
            ]);

        $this->assertDatabaseCount('users', 3);

        $this->assertDatabaseHas('users', [
            'id' => $existingUser->id,
            'email' => $email,
            'student' => true,
        ]);

        $this->assertEquals(
            1,
            User::where('email', $email)->count()
        );

        $this->assertDatabaseHas('students', [
            'student_id' => $existingUser->id,
            'teacher_id' => $this->teacher->id,
            'email' => $email,
        ]);

        Mail::assertQueued(WelcomeNewUserMail::class, 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Student Updates
    |--------------------------------------------------------------------------
    */

    public function test_update_student_profile(): void
    {
        $this->actingAs($this->teacher);

        $response = $this->post(route('student.update'), [
            'student_id' => $this->studentRecord->id,
            'first_name' => 'Updated',
            'last_name' => 'Student',
            'email' => $this->student->email,
            'status' => Student::ACTIVE,
            'at_home' => 0,
            'at_studio' => 1,
        ]);

        $response->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas(
                'success',
                'You successfully updated the student.'
            );

        $this->assertDatabaseHas('students', [
            'id' => $this->studentRecord->id,
            'first_name' => 'Updated',
            'last_name' => 'Student',
            'at_home' => 0,
            'at_studio' => 1,
        ]);
    }

    public function test_update_student_defaults_boolean_fields_to_false(): void
    {
        $this->actingAs($this->teacher);

        $this->studentRecord->update([
            'auto_schedule' => true,
            'at_home' => true,
            'at_studio' => true,
        ]);

        $response = $this->post(route('student.update'), [
            'student_id' => $this->studentRecord->id,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => $this->student->email,
            'status' => Student::ACTIVE,
            'at_home' => 0,
            'at_studio' => 1,
        ]);

        $response->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id' => $this->studentRecord->id,
            'auto_schedule' => 0,
            'at_home' => 0,
            'at_studio' => 1,
        ]);
    }

    public function test_update_student_creates_parent_when_missing(): void
    {
        Teacher::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        $this->actingAs($this->teacher);

        $this->studentRecord->update([
            'parent_id' => null,
        ]);

        $response = $this->post(route('student.update'), [
            'student_id' => $this->studentRecord->id,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => $this->student->email,
            'status' => Student::ACTIVE,
            'at_home' => 0,
            'at_studio' => 1,
            'parent_first_name' => 'Jane',
            'parent_last_name' => 'Smith',
            'parent_email' => 'jane@example.com',
        ]);

        $response->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $parent = User::where('email', 'jane@example.com')->firstOrFail();

        $this->assertDatabaseHas('students', [
            'id' => $this->studentRecord->id,
            'parent_id' => $parent->id,
        ]);

        Mail::assertQueued(WelcomeNewUserMail::class, 1);
    }

    public function test_teacher_cannot_update_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->post(route('student.update'), [
                'student_id' => $this->studentRecord->id,
                'first_name' => 'Unauthorized',
                'last_name' => 'Update',
                'email' => $this->student->email,
                'status' => Student::ACTIVE,
                'at_home' => 0,
                'at_studio' => 1,
            ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('students', [
            'id' => $this->studentRecord->id,
            'teacher_id' => $this->teacher->id,
        ]);

        $this->assertDatabaseMissing('students', [
            'id' => $this->studentRecord->id,
            'first_name' => 'Unauthorized',
        ]);
    }
}
