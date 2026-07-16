<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $sellers = Seller::query()
            ->with(['shops.serviceType'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $pendingCount = Seller::where('status', 'pending')->count();

        return view('admin.sellers.index', compact('sellers', 'status', 'pendingCount'));
    }

    public function create()
    {
        return view('admin.sellers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => ['required', Rule::in(['pending', 'active', 'inactive', 'banned', 'rejected'])],
        ]);

        Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Vendor created successfully.');
    }

    public function edit(Seller $seller)
    {
        $seller->load('shops.serviceType');

        return view('admin.sellers.edit', compact('seller'));
    }

    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('vendors')->ignore($seller->id)],
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => ['required', Rule::in(['pending', 'active', 'inactive', 'banned', 'rejected'])],
        ]);

        $data = [
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $seller->update($data);

        if ($request->status === 'active') {
            Shop::where('vendor_id', $seller->id)->update(['status' => 'active', 'is_open' => true]);
        }

        if (in_array($request->status, ['inactive', 'banned', 'rejected', 'pending'], true)) {
            Shop::where('vendor_id', $seller->id)->update(['status' => 'inactive', 'is_open' => false]);
        }

        return redirect()->route('admin.sellers.index')->with('success', 'Vendor updated successfully.');
    }

    public function approve(Seller $seller)
    {
        $seller->update(['status' => 'active']);
        Shop::where('vendor_id', $seller->id)->update(['status' => 'active', 'is_open' => true]);

        return back()->with('success', $seller->business_name ?: $seller->name . ' approved. They can now sign in.');
    }

    public function reject(Seller $seller)
    {
        $seller->update(['status' => 'rejected']);
        Shop::where('vendor_id', $seller->id)->update(['status' => 'inactive', 'is_open' => false]);

        return back()->with('success', ($seller->business_name ?: $seller->name) . ' application rejected.');
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return redirect()->route('admin.sellers.index')->with('success', 'Vendor deleted successfully.');
    }
}
