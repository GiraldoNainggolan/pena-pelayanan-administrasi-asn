<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! $request->user()?->must_change_password
            || $request->routeIs('admin.profile', 'viewer.profile', 'profile.password.update', 'logout')) {
            return $next($request);
        }

        $route = $request->user()->role === 'viewer' ? 'viewer.profile' : 'admin.profile';

        return redirect()->route($route)->with(
            'warning',
            'Password sementara wajib diganti sebelum menggunakan fitur lain.'
        );
    }
}