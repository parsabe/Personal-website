<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Determine intended URL before logging out temporary session
        $intendedUrl = null;
        if ($request->filled('redirect')) {
            $intendedUrl = $request->input('redirect');
        } elseif (session()->has('url.intended')) {
            $intendedUrl = session()->get('url.intended');
        } else {
            $prev = url()->previous();
            if ($prev && !\Illuminate\Support\Str::contains($prev, ['/login', '/auth/two-factor', '/register', '/parsa/2fa'])) {
                $intendedUrl = $prev;
            }
        }

        Auth::logout();

        $request->session()->regenerate();
        session(['2fa_user_id' => $user->id]);

        if ($intendedUrl) {
            session(['url.intended' => $intendedUrl]);
        }

        return redirect()->route('2fa.show');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
