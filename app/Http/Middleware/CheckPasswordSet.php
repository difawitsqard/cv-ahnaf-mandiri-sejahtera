<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (route('set-password') === $request->url()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user && $user->hasVerifiedEmail() && !$user->is_password_set) {
            return redirect()->route('set-password');
        }

        return $next($request);
    }
}
