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
        $featuredProducts = Product::with(['translation', 'thumbnail', 'images', 'shop', 'serviceType', 'primaryVariant'])
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
            ->paginate(12, ['*'], 'shops_page');

        $categories = Category::with('translation')
            ->where('service_type_id', $service->id)
            ->where('status', 1)
            ->get();

        $productsQuery = Product::with(['translation', 'thumbnail', 'images', 'primaryVariant', 'shop'])
            ->active()
            ->where('service_type_id', $service->id)
            ->when($request->category, fn ($q) => $q->where('category_id', $request->category))
            ->when($request->shop, fn ($q) => $q->where('shop_id', $request->shop))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->where(function ($inner) use ($term) {
                    $inner->where('slug', 'like', "%{$term}%")
                        ->orWhereHas('translation', fn ($t) => $t->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('price_min'), fn ($q) => $q->where('price', '>=', (float) $request->price_min))
            ->when($request->filled('price_max'), fn ($q) => $q->where('price', '<=', (float) $request->price_max));

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_low' => $productsQuery->orderBy('price', 'asc'),
            'price_high' => $productsQuery->orderBy('price', 'desc'),
            default => $productsQuery->latest(),
        };

        $products = $productsQuery->paginate(12)->withQueryString();

        if ($request->ajax() || $request->boolean('partial')) {
            return response()->json([
                'html' => view('themes.xylo.partials.product-grid-items', [
                    'products' => $products,
                    'cols' => 4,
                ])->render(),
                'hasMore' => $products->hasMorePages(),
                'nextPage' => $products->currentPage() + 1,
                'total' => $products->total(),
            ]);
        }

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

    public function vendorShow(Request $request, string $id)
    {
        $vendor = Vendor::with(['shops.serviceType'])->active()->findOrFail($id);
        $products = Product::with(['translation', 'thumbnail', 'images', 'primaryVariant', 'shop'])
            ->active()
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if ($request->ajax() || $request->boolean('partial')) {
            return response()->json([
                'html' => view('themes.xylo.partials.product-grid-items', [
                    'products' => $products,
                    'cols' => 3,
                ])->render(),
                'hasMore' => $products->hasMorePages(),
                'nextPage' => $products->currentPage() + 1,
            ]);
        }

        return view('themes.xylo.vendor-detail', compact('vendor', 'products'));
    }

    public function shopShow(Request $request, string $slug)
    {
        $shop = Shop::with(['vendor', 'serviceType'])->where('slug', $slug)->active()->firstOrFail();
        $products = Product::with(['translation', 'thumbnail', 'images', 'primaryVariant'])
            ->active()
            ->where('shop_id', $shop->id)
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->whereHas('translation', fn ($t) => $t->where('name', 'like', "%{$term}%"));
            })
            ->when($request->get('sort') === 'price_low', fn ($q) => $q->orderBy('price', 'asc'))
            ->when($request->get('sort') === 'price_high', fn ($q) => $q->orderBy('price', 'desc'))
            ->when(!in_array($request->get('sort'), ['price_low', 'price_high'], true), fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        if ($request->ajax() || $request->boolean('partial')) {
            return response()->json([
                'html' => view('themes.xylo.partials.product-grid-items', [
                    'products' => $products,
                    'cols' => 3,
                ])->render(),
                'hasMore' => $products->hasMorePages(),
                'nextPage' => $products->currentPage() + 1,
            ]);
        }

        return view('themes.xylo.shop-detail', compact('shop', 'products'));
    }
}
