<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect('/admin/dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        )) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Legacy MD5 upgrade path
        $admin = User::where('email', $credentials['email'])->first();
        if ($admin && $admin->getRawOriginal('password') === md5($credentials['password'])) {
            $admin->password = $credentials['password'];
            $admin->save();
            Auth::guard('web')->login($admin, $remember);
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form');
    }
}
