<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CsStudent;
use App\Models\CsFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsFeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed students table for the test
        $this->seed(\Database\Seeders\CsStudentSeeder::class);
    }

    /**
     * Test the feedback form page loads.
     */
    public function test_feedback_page_loads(): void
    {
        $response = $this->get(route('cs.feedback.create'));
        $response->assertStatus(200);
        $response->assertSee('Specialist Feedback Portal');
    }

    /**
     * Test invalid details return error.
     */
    public function test_invalid_details_submission_fails(): void
    {
        $response = $this->post(route('cs.feedback.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'ideas' => 'Nice ideas',
            'feedback' => 'Nice feedback',
            'questions' => 'No questions',
            'received_all_files' => 'yes',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseEmpty('cs_feedbacks');
    }

    /**
     * Test missing mandatory feedback fields fails validation.
     */
    public function test_missing_mandatory_fields_fails(): void
    {
        $student = CsStudent::first();
        $this->assertNotNull($student);

        $response = $this->post(route('cs.feedback.store'), [
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'email' => $student->email,
            'received_all_files' => 'yes',
        ]);

        $response->assertSessionHasErrors(['ideas', 'feedback', 'questions']);
        $this->assertDatabaseEmpty('cs_feedbacks');
    }

    /**
     * Test valid details submission succeeds.
     */
    public function test_valid_details_submission_succeeds(): void
    {
        // Get a student from the seeded database
        $student = CsStudent::first();
        $this->assertNotNull($student, 'CsStudent table must be populated for this test.');

        $response = $this->post(route('cs.feedback.store'), [
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'email' => $student->email,
            'ideas' => 'Increase future durations',
            'feedback' => 'Everything was extremely well organized',
            'questions' => 'When is the next career fair?',
            'received_all_files' => 'yes',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cs_feedbacks', [
            'cs_student_id' => $student->id,
            'ideas' => 'Increase future durations',
            'received_all_files' => true,
        ]);
    }

}
