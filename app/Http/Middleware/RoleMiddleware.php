<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $activeRole = session('active_role', $request->user()->role);

        // Prevent session tampering: verify user actually has the active role
        if (!$request->user()->hasRole($activeRole)) {
            $activeRole = $request->user()->role;
            session(['active_role' => $activeRole]);
        }

        if (!in_array($activeRole, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
