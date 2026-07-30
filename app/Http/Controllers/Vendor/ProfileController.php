<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $vendor = Auth::guard('vendor')->user();
        $shop = Shop::where('vendor_id', $vendor->id)->orderBy('id')->first();

        return view('vendor.profile.edit', compact('vendor', 'shop'));
    }

    public function update(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:120',
            'logo' => 'nullable|image|max:4096',
            'cover_image' => 'nullable|image|max:6144',
            'shop_name' => 'nullable|string|max:255',
            'shop_logo' => 'nullable|image|max:4096',
            'shop_cover' => 'nullable|image|max:6144',
        ]);

        $payload = [
            'name' => $data['name'],
            'business_name' => $data['business_name'] ?? $vendor->business_name,
            'phone' => $data['phone'] ?? null,
            'description' => $data['description'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            $payload['logo'] = $request->file('logo')->store('vendors/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $payload['cover_image'] = $request->file('cover_image')->store('vendors/covers', 'public');
        }

        $vendor->update($payload);

        $shop = Shop::where('vendor_id', $vendor->id)->orderBy('id')->first();
        if ($shop) {
            $shopData = [];
            if (!empty($data['shop_name'])) {
                $shopData['name'] = $data['shop_name'];
            }
            if ($request->hasFile('shop_logo')) {
                $shopData['logo'] = $request->file('shop_logo')->store('shops/logos', 'public');
            }
            if ($request->hasFile('shop_cover')) {
                $shopData['cover_image'] = $request->file('shop_cover')->store('shops/covers', 'public');
            }
            if ($shopData) {
                $shop->update($shopData);
            }
        }

        return back()->with('success', 'Profile images and details updated.');
    }
}
