<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Check if user has any of the allowed roles
            if (!$user->hasAnyRole($roles)) {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
