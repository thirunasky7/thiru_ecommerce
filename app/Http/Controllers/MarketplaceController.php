<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function home()
    {
        $services = ServiceType::active()->orderBy('sort_order')->get();
        $featuredShops = Shop::with(['serviceType', 'vendor'])
            ->active()
            ->featured()
            ->take(6)
            ->get();
        $featuredProducts = Product::with(['translation', 'thumbnail', 'shop', 'serviceType', 'primaryVariant'])
            ->active()
            ->featured()
            ->latest()
            ->take(8)
            ->get();
        $vendors = Vendor::active()->featured()->take(6)->get();

        return view('themes.xylo.marketplace-home', compact(
            'services',
            'featuredShops',
            'featuredProducts',
            'vendors'
        ));
    }

    public function services()
    {
        $services = ServiceType::active()->orderBy('sort_order')->withCount([
            'shops' => fn ($q) => $q->where('status', 'active'),
        ])->get();

        return view('themes.xylo.services', compact('services'));
    }

    public function service(Request $request, string $slug)
    {
        $service = ServiceType::where('slug', $slug)->active()->firstOrFail();

        $shops = Shop::with('vendor')
            ->active()
            ->where('service_type_id', $service->id)
            ->when($request->q, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->paginate(12);

        $categories = Category::with('translation')
            ->where('service_type_id', $service->id)
            ->where('status', 1)
            ->get();

        $products = Product::with(['translation', 'thumbnail', 'primaryVariant', 'shop'])
            ->active()
            ->where('service_type_id', $service->id)
            ->when($request->category, fn ($q) => $q->where('category_id', $request->category))
            ->when($request->shop, fn ($q) => $q->where('shop_id', $request->shop))
            ->latest()
            ->paginate(12);

        return view('themes.xylo.service-browse', compact('service', 'shops', 'categories', 'products'));
    }

    public function vendors()
    {
        $vendors = Vendor::with(['shops.serviceType'])
            ->active()
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->paginate(12);

        return view('themes.xylo.vendors', compact('vendors'));
    }

    public function vendorShow(string $id)
    {
        $vendor = Vendor::with(['shops.serviceType'])->active()->findOrFail($id);
        $products = Product::with(['translation', 'thumbnail', 'primaryVariant'])
            ->active()
            ->where('vendor_id', $vendor->id)
            ->paginate(12);

        return view('themes.xylo.vendor-detail', compact('vendor', 'products'));
    }

    public function shopShow(string $slug)
    {
        $shop = Shop::with(['vendor', 'serviceType'])->where('slug', $slug)->active()->firstOrFail();
        $products = Product::with(['translation', 'thumbnail', 'primaryVariant'])
            ->active()
            ->where('shop_id', $shop->id)
            ->paginate(12);

        return view('themes.xylo.shop-detail', compact('shop', 'products'));
    }
}
