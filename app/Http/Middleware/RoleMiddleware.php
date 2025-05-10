<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check for multiple roles (separated by |)
        if (strpos($role, '|') !== false) {
            $roles = explode('|', $role);
            if (in_array(Auth::user()->role, $roles)) {
                return $next($request);
            }
        }
        // Single role check
        else if (Auth::user()->role === $role) {
            return $next($request);
        }

        // Redirect admin to admin dashboard if trying to access customer routes
        if (Auth::user()->role === 'admin' && $role === 'customer') {
            return redirect()->intended(config('filament.path', '/admin'));
        }

        // Redirect customer to customer dashboard if trying to access admin routes
        if (Auth::user()->role === 'customer' && $role === 'admin') {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Fallback untuk user tanpa role yang sesuai
        abort(403, 'Unauthorized action.');
    }
}
