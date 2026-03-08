<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // If user is not logged in, let them through to login
        if (!$user) {
            return $next($request);
        }
        
        // If user is already verified, let them through
        if ($user->is_verified) {
            return $next($request);
        }
        
        // If user is on the verification page, let them through
        if ($request->routeIs('verification.*')) {
            return $next($request);
        }
        
        // Redirect to verification page
        return redirect()->route('verification.show');
    }
}
