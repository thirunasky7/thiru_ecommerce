<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $filters = [
            'category' => $request->input('category', []),
            'brand' => $request->input('brand', []),
            'price_min' => $request->input('price_min', 0),
            'price_max' => $request->input('price_max', 1000),
            'color' => $request->input('color', []),
            'size' => $request->input('size', []),
        ];
        
        $products = Product::with('translation')
            ->when(!empty($filters['category']), function ($query) use ($filters) {
                return $query->whereIn('category_id', $filters['category']);
            })
            ->when(!empty($filters['brand']), function ($query) use ($filters) {
                return $query->whereIn('brand_id', $filters['brand']);
            })
            ->when($filters['price_min'], function ($query) use ($filters) {
                return $query->where('price', '>=', $filters['price_min']);
            })
            ->when($filters['price_max'], function ($query) use ($filters) {
                return $query->where('price', '<=', $filters['price_max']);
            })
            ->when(!empty($filters['color']), function ($query) use ($filters) {
                return $query->whereIn('color', $filters['color']);
            })
            ->when(!empty($filters['size']), function ($query) use ($filters) {
                return $query->whereIn('size', $filters['size']);
            })
            ->paginate(12);

        $categories = Category::with('translation')->get();
        $brands = Brand::with('translations')->get();

        if ($request->ajax()) {
            return view('themes.xylo.partials.product-list', compact('products'))->render();
        }        

        return view('themes.xylo.shop', compact('products', 'categories', 'brands'));
    }
}
