<?php

namespace App\Http\Middleware;

use App\Services\DefaultAdminService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedMiddleware
{
    public function __construct(
        private DefaultAdminService $adminService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        if ($user->is_verified) {
            return $next($request);
        }

        if ($this->adminService->isDefaultAdmin($user)) {
            $user->is_verified = true;
            $user->save();

            return $next($request);
        }

        if ($request->routeIs('verification.*')) {
            return $next($request);
        }

        return redirect()->route('verification.show');
    }
}
