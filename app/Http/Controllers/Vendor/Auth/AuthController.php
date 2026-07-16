<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Models\Shop;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor.auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.dashboard');
        }

        $serviceTypes = ServiceType::query()
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('vendor.auth.register', compact('serviceTypes'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'service_type_id' => ['required', Rule::exists('service_types', 'id')],
            'shop_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:500',
            'pincode' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($data) {
            $vendor = Vendor::create([
                'name' => $data['name'],
                'business_name' => $data['business_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'status' => 'pending',
                'description' => $data['description'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'pincode' => $data['pincode'] ?? null,
            ]);

            $shopName = $data['shop_name'];
            $slug = Str::slug($shopName);
            $baseSlug = $slug;
            $i = 1;
            while (Shop::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }

            Shop::create([
                'vendor_id' => $vendor->id,
                'seller_id' => $vendor->id,
                'service_type_id' => $data['service_type_id'],
                'name' => $shopName,
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'phone' => $data['phone'] ?? null,
                'status' => 'inactive',
                'is_open' => false,
            ]);
        });

        return redirect()
            ->route('vendor.login')
            ->with('success', 'Partner application submitted. You can sign in after admin approval.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $remember = $request->boolean('remember');
        $vendor = Vendor::where('email', $credentials['email'])->first();

        if (!$vendor || !Auth::guard('vendor')->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        )) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid email or password.']);
        }

        $vendor = Auth::guard('vendor')->user();

        if ($vendor->status === 'pending') {
            Auth::guard('vendor')->logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your partner application is pending admin approval.']);
        }

        if ($vendor->status === 'rejected') {
            Auth::guard('vendor')->logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your partner application was rejected. Contact support for help.']);
        }

        if (!in_array($vendor->status, ['active', 1, '1'], true)) {
            Auth::guard('vendor')->logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your vendor account is inactive.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('vendor.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
}
