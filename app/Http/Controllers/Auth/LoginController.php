<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;            // ? Add this line
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $admin = User::where('email', $credentials['email'])->first();

       

        if ($admin) {
            // Check bcrypt
            if (Hash::check($credentials['password'], $admin->password)) {
                Auth::guard('web')->login($admin);
                $request->session()->regenerate();
                return redirect()->to('/admin/dashboard');
            }

            // Optional: handle MD5 legacy hashes
            if ($admin->password === md5($credentials['password'])) {
                $admin->password = Hash::make($credentials['password']);
                $admin->save();

                Auth::guard('admin')->login($admin);
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
