<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateVendor
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.login');
        }

        $vendor = Auth::guard('vendor')->user();
        if ($vendor && $vendor->status !== 'active') {
            Auth::guard('vendor')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($vendor->status) {
                'pending' => 'Your partner application is pending admin approval.',
                'rejected' => 'Your partner application was rejected.',
                default => 'Your vendor account is inactive.',
            };

            return redirect()->route('vendor.login')->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}