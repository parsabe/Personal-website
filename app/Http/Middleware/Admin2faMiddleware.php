<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin2faMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Restrict to parsabe99@gmail.com
        if (auth()->user()->email !== 'parsabe99@gmail.com') {
            abort(403, 'Unauthorized access. Only the designated administrator can access this section.');
        }

        // 3. Ensure 2FA verification has been completed for this session
        if (session('parsa_2fa_verified') !== true && session('2fa_verified') !== true) {
            // If the route is already parsa.2fa.show or parsa.2fa.verify, allow to prevent infinite redirect loop
            if ($request->routeIs('parsa.2fa.show') || $request->routeIs('parsa.2fa.verify')) {
                return $next($request);
            }
            return redirect()->route('parsa.2fa.show');
        }

        return $next($request);
    }
}
