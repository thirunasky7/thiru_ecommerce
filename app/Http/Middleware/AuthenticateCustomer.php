<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }*/

        if (!auth('customer')->check()) {
            // Detect if this is an AJAX/JS call expecting JSON
            if ($request->expectsJson() || $request->isJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
    
            return redirect()->route('customer.login');
        }

        return $next($request);
    }
}
