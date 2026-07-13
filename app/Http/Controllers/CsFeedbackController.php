<?php

namespace App\Http\Controllers;

use App\Models\CsFeedback;
use App\Models\CsStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CsFeedbackController extends Controller
{
    /**
     * Display the feedback form, depending on session state.
     */
    public function create()
    {
        $email = session('cs_feedback_email');
        $scenario = session('cs_feedback_scenario');

        if (!$email) {
            return view('cs.feedback', ['state' => 'verify']);
        }

        return view('cs.feedback', [
            'state' => 'form',
            'scenario' => $scenario,
            'email' => $email
        ]);
    }

    /**
     * Verify the entered email address.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = trim(Str::lower($request->input('email')));

        // Store email in session
        session(['cs_feedback_email' => $email]);



        // Check if student is in the database (Excel import table)
        $student = CsStudent::whereRaw('LOWER(email) = ?', [$email])->first();

        if ($student) {
            // Scenario 2: Student showed up
            session(['cs_feedback_scenario' => 2]);
            session(['cs_feedback_student_id' => $student->id]);
        } else {
            // Scenario 1: Student did not show up
            session(['cs_feedback_scenario' => 1]);
        }

        return redirect()->route('cs.feedback.create');
    }



    /**
     * Reset the feedback session.
     */
    public function resetSession()
    {
        session()->forget([
            'cs_feedback_email',
            'cs_feedback_scenario',
            'cs_feedback_student_id'
        ]);

        return redirect()->route('cs.feedback.create');
    }

    /**
     * Store a newly created feedback.
     */
    public function store(Request $request)
    {
        $email = trim(Str::lower(session('cs_feedback_email')));
        $scenario = session('cs_feedback_scenario');

        if (!$email || is_null($scenario)) {
            return redirect()->route('cs.feedback.create')
                ->withErrors(['email' => 'Please enter your email to proceed.']);
        }

        if ($scenario == 1) {
            // Scenario 1 validation: only suggestions and expectations
            $request->validate([
                'ideas' => 'required|string',
            ]);

            CsFeedback::create([
                'cs_student_id'      => null,
                'email'              => $email,
                'ideas'              => $request->input('ideas'),
                'feedback'           => 'Scenario 1 (Did not show up)',
                'questions'          => 'N/A',
                'received_all_files' => false,
            ]);
        } else {
            // Scenario 2 validation: all fields
            $request->validate([
                'ideas'              => 'required|string',
                'feedback'           => 'required|string',
                'questions'          => 'required|string',
                'received_all_files' => 'required|in:yes,no',
            ]);

            $student = CsStudent::whereRaw('LOWER(email) = ?', [$email])->first();
            $studentId = $student ? $student->id : null;

            CsFeedback::create([
                'cs_student_id'      => $studentId,
                'email'              => $email,
                'ideas'              => $request->input('ideas'),
                'feedback'           => $request->input('feedback'),
                'questions'          => $request->input('questions'),
                'received_all_files' => $request->input('received_all_files') === 'yes',
            ]);
        }

        // Clear feedback session on success
        session()->forget([
            'cs_feedback_email',
            'cs_feedback_scenario',
            'cs_feedback_student_id'
        ]);

        return redirect()->route('cs.feedback.create')
            ->with('success', 'Thank you! Your feedback has been recorded successfully.');
    }

    /**
     * Display the submissions admin page.
     */
    public function adminIndex()
    {
        // Require authentication
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Restrict to parsabe99@gmail.com
        if (auth()->user()->email !== 'parsabe99@gmail.com') {
            abort(403, 'Unauthorized access. Only parsabe99@gmail.com can access the admin dashboard.');
        }

        // Fetch submissions with related student data
        $feedbacks = CsFeedback::with('student')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cs.admin_dashboard', compact('feedbacks'));
    }
}
