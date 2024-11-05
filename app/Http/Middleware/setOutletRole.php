<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class setOutletRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $outlet = null;

        if ($user?->hasRole('superadmin') && !$request->route('outlet')) {
            return $next($request);
        }

        if ($user?->hasRole('superadmin') && $request->route('outlet')) {
            $slug = $request->route('outlet')->slug ?? $request->route('outlet');
            $outlet = Outlet::where('slug', $slug)->firstOrFail();
        } else if ($user?->hasRole('admin') && $user?->outlet_id) {
            $outlet = Outlet::where('id', $user->outlet_id)->firstOrFail();
        } else if ($user?->hasRole('staff') && $user?->outlet_id) {
            $outlet = Outlet::where('id', $user->outlet_id)->firstOrFail();
        } else {
            return abort(403, 'Unauthorized');
        }

        if (!$outlet)
            if ($user?->hasRole('superadmin')) {
                return abort(404, 'Outlet not found');
            } else {
                return abort(403);
            }

        // $request->merge(['outlet' => $outlet]);
        $request->route()->setParameter('outlet', $outlet);

        return $next($request);
    }
}
