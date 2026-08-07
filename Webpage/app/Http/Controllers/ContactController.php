<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Save to database
        Contact::create($validated);

        // Send email notification
        $to = 'parsabe99@gmail.com';
        $subject = 'New Contact Form Submission - ' . $validated['name'];
        $body = "You have received a new message from your website contact form:\n\n" .
                "Name: " . $validated['name'] . "\n" .
                "Email: " . $validated['email'] . "\n\n" .
                "Message:\n" . $validated['message'];
        
        // 1. Send using native PHP mail (reliable on Linux servers with postfix/sendmail configured)
        $headers = "From: noreply@parsabe.com\r\n" .
                   "Reply-To: " . $validated['email'] . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        @mail($to, $subject, $body, $headers);

        // 2. Try sending using Laravel Mailer (will write to log or send via SMTP if configured in .env)
        try {
            \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($validated) {
                $message->to('parsabe99@gmail.com')
                        ->subject('New Contact Form Submission - ' . $validated['name'])
                        ->from('noreply@parsabe.com', 'Website Contact Form')
                        ->replyTo($validated['email'], $validated['name']);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Laravel Mailer failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}