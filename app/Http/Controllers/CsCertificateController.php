<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CsCertificateController extends Controller
{
    /**
     * Display the certificate search portal.
     */
    public function index()
    {
        $student = null;
        if (session()->has('cs_student_id')) {
            $student = DB::table('cs_students')
                ->where('id', session('cs_student_id'))
                ->first();

            // Double check in case access was completed and database state changed
            if ($student && $student->downloaded_cert && $student->downloaded_zip) {
                session()->forget('cs_student_id');
                $student = null;
            }
        }

        return view('cs.certificates', compact('student'));
    }

    /**
     * Search for a student by email.
     */
    public function search(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = trim(Str::lower($request->input('email')));

        $student = DB::table('cs_students')
            ->where('email', $email)
            ->first();

        if (!$student) {
            return back()->withErrors([
                'email' => 'We could not find a student registered with this email address. Please make sure to check for typos or use the email you registered with.'
            ])->withInput();
        }

        // Security Lockout check
        if ($student->downloaded_cert && $student->downloaded_zip) {
            return back()->withErrors([
                'email' => 'All downloads (Certificate & Images) for this account have already been completed. Access is now permanently closed.'
            ])->withInput();
        }

        // Prevent session fixation
        $request->session()->regenerate();

        // Store student ID in session to allow downloads
        session(['cs_student_id' => $student->id]);

        return redirect()->route('cs.certificates.index')->with('success', 'Email verified successfully! You can now download your files below.');
    }

    /**
     * Clear search session (logout of portal).
     */
    public function clear()
    {
        session()->forget('cs_student_id');
        return redirect()->route('cs.certificates.index');
    }

    /**
     * Download the certificate PDF.
     */
    public function downloadCertificate(Request $request)
    {
        // Security checks
        if (!session()->has('cs_student_id')) {
            abort(403, 'Unauthorized access.');
        }

        $id = session('cs_student_id');
        $student = DB::table('cs_students')->where('id', $id)->first();
        if (!$student) {
            abort(404, 'Student record not found.');
        }

        // Already downloaded check
        if ($student->downloaded_cert) {
            abort(403, 'You have already downloaded your certificate.');
        }

        // Sanitize name fields to prevent directory traversal
        $firstNameSafe = str_replace(['/', '\\', '..'], '', $student->first_name);
        $lastNameSafe = str_replace(['/', '\\', '..'], '', $student->last_name);

        // Certificate naming scheme: Zertifikat_{Firstname}_{Lastname}.pdf
        $fileName = "Zertifikat_{$firstNameSafe}_{$lastNameSafe}.pdf";
        
        $baseDir = storage_path('app/cs/certificates');
        $filePath = $baseDir . DIRECTORY_SEPARATOR . $fileName;

        // Resolve absolute path and prevent boundary bypass (CWE-22)
        $realBase = realpath($baseDir);
        $realFile = realpath($filePath);

        if ($realFile === false || !str_starts_with($realFile, $realBase . DIRECTORY_SEPARATOR)) {
            abort(403, 'Invalid file path or access denied.');
        }

        // Mark as downloaded in DB
        DB::table('cs_students')->where('id', $id)->update([
            'downloaded_cert' => true,
            'updated_at' => now(),
        ]);

        // Check if both are downloaded, clear session if so
        $updatedStudent = DB::table('cs_students')->where('id', $id)->first();
        if ($updatedStudent->downloaded_cert && $updatedStudent->downloaded_zip) {
            session()->forget('cs_student_id');
        }

        return response()->download($realFile, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    /**
     * Download the shared images zip.
     */
    public function downloadImages()
    {
        // Security check
        if (!session()->has('cs_student_id')) {
            abort(403, 'Unauthorized access.');
        }

        $id = session('cs_student_id');
        $student = DB::table('cs_students')->where('id', $id)->first();
        if (!$student) {
            abort(404, 'Student record not found.');
        }

        // Already downloaded check
        if ($student->downloaded_zip) {
            abort(403, 'You have already downloaded the images archive.');
        }

        $baseDir = storage_path('app/cs');
        $filePath = $baseDir . DIRECTORY_SEPARATOR . 'images.zip';

        // Resolve absolute path and prevent boundary bypass (CWE-22)
        $realBase = realpath($baseDir);
        $realFile = realpath($filePath);

        if ($realFile === false || !str_starts_with($realFile, $realBase . DIRECTORY_SEPARATOR)) {
            abort(403, 'Invalid file path or access denied.');
        }

        // Mark as downloaded in DB
        DB::table('cs_students')->where('id', $id)->update([
            'downloaded_zip' => true,
            'updated_at' => now(),
        ]);

        // Check if both are downloaded, clear session if so
        $updatedStudent = DB::table('cs_students')->where('id', $id)->first();
        if ($updatedStudent->downloaded_cert && $updatedStudent->downloaded_zip) {
            session()->forget('cs_student_id');
        }

        return response()->download($realFile, 'images.zip', [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="images.zip"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }
}
