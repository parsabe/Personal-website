<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CsFeedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminPortalController extends Controller
{
    /**
     * Display the 2FA Setup/Login Form.
     */
    public function show2faForm()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->email !== 'parsabe99@gmail.com') {
            abort(403, 'Unauthorized access.');
        }

        $user = auth()->user();
        $isSetup = empty($user->google2fa_secret);
        $secret = '';

        if ($isSetup) {
            // Generate a temporary secret if not already generated in this session
            if (!session()->has('temp_2fa_secret')) {
                session(['temp_2fa_secret' => $this->generateSecretKey()]);
            }
            $secret = session('temp_2fa_secret');
        }

        return view('admin.2fa', compact('isSetup', 'secret'));
    }

    /**
     * Verify the 2FA Code.
     */
    public function verify2fa(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->email !== 'parsabe99@gmail.com') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();
        $code = $request->input('code');

        if (empty($user->google2fa_secret)) {
            // Registering new 2FA
            $tempSecret = session('temp_2fa_secret');
            if (empty($tempSecret)) {
                return redirect()->route('parsa.2fa.show')->withErrors(['code' => 'Session expired. Please try again.']);
            }

            if ($this->verifyKey($tempSecret, $code)) {
                // Save secret to database
                $dbUser = User::find($user->id);
                $dbUser->google2fa_secret = $tempSecret;
                $dbUser->save();

                session()->forget('temp_2fa_secret');
                session(['parsa_2fa_verified' => true]);

                return redirect()->route('parsa.dashboard')->with('success', 'Two-Factor Authentication registered successfully!');
            }
        } else {
            // Verifying existing 2FA
            if ($this->verifyKey($user->google2fa_secret, $code)) {
                session(['parsa_2fa_verified' => true]);
                return redirect()->route('parsa.dashboard');
            }
        }

        return back()->withErrors(['code' => 'The provided 6-digit verification code is invalid. Please try again.']);
    }

    /**
     * Admin Dashboard main page.
     */
    public function dashboard()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        $feedbacks = CsFeedback::with('student')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('contacts', 'feedbacks'));
    }

    /**
     * Delete contact message.
     */
    public function deleteContact($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Contact message deleted successfully.');
    }

    /**
     * Purge all contact messages.
     */
    public function purgeAllContacts()
    {
        Contact::truncate();

        return back()->with('success', 'All contact messages purged successfully.');
    }

    /**
     * Delete feedback submission.
     */
    public function deleteFeedback($id)
    {
        $feedback = CsFeedback::findOrFail($id);
        $feedback->delete();

        return back()->with('success', 'Specialist feedback submission deleted successfully.');
    }

    /**
     * Reply to a contact message via email.
     */
    public function replyContact(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $contact = Contact::findOrFail($id);
        $replyText = $request->input('reply');

        $body = "Dear " . $contact->name . ",\n\n" .
                $replyText . "\n\n" .
                "Best regards,\n" .
                "Parsa Besharat";

        try {
            Mail::to($contact->email)->send(new \App\Mail\AdminReplyMail(
                'Response to your message - Parsa Besharat',
                $body
            ));

            $contact->update([
                'reply' => $replyText,
                'replied_at' => now(),
            ]);

            return back()->with('success', 'Reply email sent successfully and logged.');
        } catch (\Exception $e) {
            return back()->withErrors(['reply' => 'Failed to send reply email: ' . $e->getMessage()]);
        }
    }

    /**
     * Reply to a specialist feedback via email.
     */
    public function replyFeedback(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $feedback = CsFeedback::with('student')->findOrFail($id);
        $replyText = $request->input('reply');

        $name = 'Campus Specialist';
        $email = $feedback->email;
        if ($feedback->student) {
            $name = $feedback->student->first_name . " " . $feedback->student->last_name;
            $email = $feedback->student->email;
        }

        $body = "Dear " . $name . ",\n\n" .
                $replyText . "\n\n" .
                "Best regards,\n" .
                "Parsa Besharat";

        try {
            Mail::to($email)->send(new \App\Mail\AdminReplyMail(
                'Response to your Campus Specialists Feedback',
                $body
            ));

            $feedback->update([
                'reply' => $replyText,
                'replied_at' => now(),
            ]);

            return back()->with('success', 'Reply email sent successfully and logged.');
        } catch (\Exception $e) {
            return back()->withErrors(['reply' => 'Failed to send reply email: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // TOTP Cryptographic Verification Utilities (Self-Contained)
    // ==========================================

    /**
     * Decodes a Base32 encoded string.
     */
    private function base32Decode($secret)
    {
        if (empty($secret)) {
            return '';
        }

        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $base32charsFlipped = array_flip(str_split($base32chars));

        $secret = strtoupper($secret);
        $secret = str_replace('=', '', $secret);
        
        $buf = '';
        foreach (str_split($secret) as $char) {
            if (!isset($base32charsFlipped[$char])) {
                continue;
            }
            $buf .= str_pad(decbin($base32charsFlipped[$char]), 5, '0', STR_PAD_LEFT);
        }

        $decoded = '';
        $chunks = str_split($buf, 8);
        foreach ($chunks as $chunk) {
            if (strlen($chunk) < 8) {
                break;
            }
            $decoded .= chr(bindec($chunk));
        }

        return $decoded;
    }

    /**
     * Verifies a 6-digit TOTP key against a Base32 secret.
     */
    private function verifyKey($secret, $key, $drift = 1)
    {
        $decodedSecret = $this->base32Decode($secret);
        if (!$decodedSecret) {
            return false;
        }

        $currentTimeSlice = floor(time() / 30);

        for ($i = -$drift; $i <= $drift; $i++) {
            $timeSlice = $currentTimeSlice + $i;
            
            // Pack time slice into a 64-bit binary string (big endian)
            $timePacked = pack('N*', 0) . pack('N*', $timeSlice);
            
            // HMAC-SHA1
            $hash = hash_hmac('sha1', $timePacked, $decodedSecret, true);
            
            // Dynamic truncation
            $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
            $truncatedHash = substr($hash, $offset, 4);
            
            // Unpack 32-bit int
            $num = unpack('N', $truncatedHash)[1];
            $num = $num & 0x7FFFFFFF;
            
            $calculatedCode = str_pad($num % 1000000, 6, '0', STR_PAD_LEFT);
            
            if ($calculatedCode === trim($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generates a random Base32 secret key (16 characters).
     */
    private function generateSecretKey($length = 16)
    {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $base32chars[random_int(0, 31)];
        }
        return $secret;
    }
}
