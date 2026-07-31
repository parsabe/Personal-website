<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($user->first_name || $user->last_name) {
            $user->name = trim($user->first_name . ' ' . $user->last_name);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account with reason survey.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Handle user self-deletion with reason survey (for SPA / Chat modal profile).
     */
    public function deleteAccountWithReason(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $user = Auth::user();

        $request->validate([
            'reason' => 'required|string',
            'custom_reason' => 'nullable|string|max:1000',
        ]);

        $user->deleted_reason = $request->input('reason');
        $user->deleted_custom_reason = $request->input('custom_reason');
        $user->save();

        // Soft-delete user's published articles
        \App\Models\BlogPost::where('author_id', $user->id)->delete();

        // Soft-delete user account
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => 'Your account and associated articles have been deleted successfully.'
        ]);
    }
}
