<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    /**
     * Display a listing of the sellers.
     */
    public function index()
    {
        $sellers = Seller::latest()->paginate(10);
        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Show the form for creating a new seller.
     */
    public function create()
    {
        return view('admin.sellers.create');
    }

    /**
     * Store a newly created seller in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => ['required', Rule::in(['active', 'inactive', 'banned'])],
        ]);

        Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Seller created successfully.');
    }

    /**
     * Show the form for editing the specified seller.
     */
    public function edit(Seller $seller)
    {
        return view('admin.sellers.edit', compact('seller'));
    }

    /**
     * Update the specified seller in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('sellers')->ignore($seller->id)],
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => ['required', Rule::in(['active', 'inactive', 'banned'])],
        ]);

        $seller->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $seller->password,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Seller updated successfully.');
    }

    /**
     * Remove the specified seller from storage.
     */
    public function destroy(Seller $seller)
    {
        $seller->delete();
        return redirect()->route('admin.sellers.index')->with('success', 'Seller deleted successfully.');
    }
}
