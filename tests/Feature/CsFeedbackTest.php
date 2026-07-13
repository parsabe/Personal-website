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
     * Test the feedback portal main page loads (shows email entry screen).
     */
    public function test_feedback_page_loads_verification_screen(): void
    {
        $response = $this->get(route('cs.feedback.create'));
        $response->assertStatus(200);
        $response->assertSee('Feedback Ledger');
        $response->assertSee('State Your Registered Email Address');
    }

    /**
     * Test email verification redirecting to Scenario 2 for registered student.
     */
    public function test_registered_student_email_verifies_to_scenario_2(): void
    {
        $student = CsStudent::first();
        $this->assertNotNull($student);

        $response = $this->post(route('cs.feedback.verify'), [
            'email' => $student->email,
        ]);

        $response->assertRedirect(route('cs.feedback.create'));
        $response->assertSessionHas('cs_feedback_email', strtolower($student->email));
        $response->assertSessionHas('cs_feedback_scenario', 2);
        $response->assertSessionHas('cs_feedback_student_id', $student->id);
    }

    /**
     * Test email verification redirecting to Scenario 1 for unregistered student.
     */
    public function test_unregistered_student_email_verifies_to_scenario_1(): void
    {
        $response = $this->post(route('cs.feedback.verify'), [
            'email' => 'unknown.student@example.com',
        ]);

        $response->assertRedirect(route('cs.feedback.create'));
        $response->assertSessionHas('cs_feedback_email', 'unknown.student@example.com');
        $response->assertSessionHas('cs_feedback_scenario', 1);
        $response->assertSessionMissing('cs_feedback_student_id');
    }

    /**
     * Test testing email parsabe99@gmail.com triggers test selector.
     */
    public function test_testing_email_requires_scenario_selection(): void
    {
        $response = $this->post(route('cs.feedback.verify'), [
            'email' => 'parsabe99@gmail.com',
        ]);

        $response->assertRedirect(route('cs.feedback.create'));
        $response->assertSessionHas('cs_feedback_email', 'parsabe99@gmail.com');
        $response->assertSessionMissing('cs_feedback_scenario');

        // Access page - should see the test selector
        $pageResponse = $this->get(route('cs.feedback.create'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Select which scenario to examine');
    }

    /**
     * Test Scenario 1 submission (expectations only).
     */
    public function test_scenario_1_submission_succeeds(): void
    {
        $response = $this->withSession([
            'cs_feedback_email' => 'unregistered@test.com',
            'cs_feedback_scenario' => 1
        ])->post(route('cs.feedback.store'), [
            'ideas' => 'I expected better swag, but the portal is cool.',
        ]);

        $response->assertRedirect(route('cs.feedback.create'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cs_feedbacks', [
            'cs_student_id' => null,
            'email' => 'unregistered@test.com',
            'ideas' => 'I expected better swag, but the portal is cool.',
            'feedback' => 'Scenario 1 (Did not show up)',
        ]);
    }

    /**
     * Test Scenario 2 submission succeeds with all fields.
     */
    public function test_scenario_2_submission_succeeds(): void
    {
        $student = CsStudent::first();
        $this->assertNotNull($student);

        $response = $this->withSession([
            'cs_feedback_email' => $student->email,
            'cs_feedback_scenario' => 2,
            'cs_feedback_student_id' => $student->id
        ])->post(route('cs.feedback.store'), [
            'ideas' => 'Slightly longer slots next time.',
            'feedback' => 'Wonderful execution!',
            'questions' => 'How long does the certification print take?',
            'received_all_files' => 'yes',
        ]);

        $response->assertRedirect(route('cs.feedback.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cs_feedbacks', [
            'cs_student_id' => $student->id,
            'email' => $student->email,
            'ideas' => 'Slightly longer slots next time.',
            'feedback' => 'Wonderful execution!',
            'questions' => 'How long does the certification print take?',
            'received_all_files' => true,
        ]);
    }
}
