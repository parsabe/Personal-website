<?php

namespace App\Http\Controllers;

use App\Models\CsFeedback;
use App\Models\CsStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CsFeedbackController extends Controller
{
    /**
     * Display the feedback form.
     */
    public function create()
    {
        return view('cs.feedback');
    }

    /**
     * Store a newly created feedback.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'         => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'ideas'              => 'required|string',
            'feedback'           => 'required|string',
            'questions'          => 'required|string',
            'received_all_files' => 'required|in:yes,no',
        ]);

        $firstName = trim(Str::lower($request->input('first_name')));
        $lastName  = trim(Str::lower($request->input('last_name')));
        $email     = trim(Str::lower($request->input('email')));

        // Find the student matching the name and email
        $student = CsStudent::whereRaw('LOWER(first_name) = ?', [$firstName])
            ->whereRaw('LOWER(last_name) = ?', [$lastName])
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (!$student) {
            return back()->withErrors([
                'email' => 'We could not find a Campus Specialist matching the provided First Name, Last Name, and Email in our records. Please make sure to check for typos.'
            ])->withInput();
        }

        // Store the feedback entry
        CsFeedback::create([
            'cs_student_id'      => $student->id,
            'ideas'              => $request->input('ideas'),
            'feedback'           => $request->input('feedback'),
            'questions'          => $request->input('questions'),
            'received_all_files' => $request->input('received_all_files') === 'yes',
        ]);

        return back()->with('success', 'Thank you! Your ideas, feedback, and questions have been recorded successfully.');
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
