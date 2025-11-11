<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\Customer;
use App\Models\Category;

class ProductController extends Controller
{
    /*public function show($slug)
    {
        $product = Product::where('slug', $slug)
        ->with(['translation', 'thumbnail', 'reviews'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->firstOrFail();
        return view('themes.xylo.product-detail', compact('product'));
    }*/

    public function show($slug)
    {
        $product = Product::with([
            'attributeValues.attribute',
            'attributeValues.translations',
            'translations',
            'reviews',
            'primaryVariant',
            'variants.attributeValues',
            'images'
        ])->withAvg('reviews', 'rating')
          ->withCount('reviews')
          ->where('slug', $slug)
          ->firstOrFail();

        $primaryVariant = $product->variants()->where('is_primary', true)->first();
        $inStock = $primaryVariant && $primaryVariant->stock > 0;

        $variantMap = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'attributes' => $variant->attributeValues->pluck('id')->sort()->values()->toArray()
            ];
        });
        return view('themes.xylo.product-detail', compact('product', 'inStock', 'variantMap'));
    }

    public function getVariantPrice(Request $request)
    {
        $variantId = $request->input('variant_id');
        $productId = $request->input('product_id');
        $variant = ProductVariant::with('product')
                    ->where('id', $variantId)
                    ->where('product_id', $productId)
                    ->first();

        if ($variant) {
            $stockStatus = $variant->stock > 0 ? 'IN STOCK' : 'OUT OF STOCK';
            $isOutOfStock = $variant->stock <= 0;
            return response()->json([
                'success' => true,
                'price' => number_format($variant->converted_price, 2),
                'stock' => $stockStatus,
                'is_out_of_stock' => $isOutOfStock,
                'currency_symbol' => activeCurrency()->symbol,
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

public function validateCustomer(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'product_id' => 'required|exists:products,id'
    ]);

    // Clean phone number (remove spaces, dashes, etc.)
    $cleanPhone = preg_replace('/\D/', '', $request->phone);

    // Find customer by name and phone
    $customer = Customer::where('phone', $cleanPhone)
        ->where(function($query) use ($request) {
            $query->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('name', $request->name);
        })
        ->first();

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Customer not found. Please check your name and phone number.'
        ]);
    }

    // Optional: Check if customer has purchased this product
    // $hasPurchased = Order::where('customer_id', $customer->id)
    //     ->whereHas('items', function($query) use ($request) {
    //         $query->where('product_id', $request->product_id);
    //     })->exists();

    // if (!$hasPurchased) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'You need to purchase this product before reviewing it.'
    //     ]);
    // }

    return response()->json([
        'success' => true,
        'customer_id' => $customer->id,
        'customer_name' => $customer->name
    ]);
}

public function submitReview(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'customer_id' => 'required|exists:customers,id',
        'user_name' => 'required|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:1000',
        'title' => 'nullable|string|max:255'
    ]);

    // Check if customer has already reviewed this product
    $existingReview = ProductReview::where('product_id', $request->product_id)
        ->where('customer_id', $request->customer_id)
        ->first();

    if ($existingReview) {
        return response()->json([
            'success' => false,
            'message' => 'You have already reviewed this product.'
        ]);
    }

    try {
        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'customer_id' => $request->customer_id,
            'user_name' => $request->user_name,
            'rating' => $request->rating,
            'review' => $request->comment,
            'title' => $request->title,
            'is_approved' => 1 // or 'pending' for moderation
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!'
        ]);
    } catch (\Exception $e) {
        \Log::error('Review submission error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to submit review. Please try again.'
        ]);
    }
}


public function categoryProducts($slug = null)
{
    $categories = Category::where('status',1)->with('products')->get();

    $query = Product::with(['translation', 'thumbnail', 'category.translation', 'reviews']);

    // If a category slug is provided, filter products
    if ($slug) {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
    }

    $products = $query->where('status', 1)->get();

    return view('themes.xylo.products', [
        'categories' => $categories,
        'products' => $products,
        'selectedCategorySlug' => $slug, // pass slug to blade
    ]);
}

}
