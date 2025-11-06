<?php

namespace App\Services\Api;

use App\Models\Category;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class HomeService
{
    /**
     * Get categories that have products
     */
    public function categoryProducts()
    {
        // ✅ Load only categories that have at least one product
        $categories = Category::whereHas('products')
            ->with([
                'translation',
                'products.translation',
                'products.thumbnail',
                'products.primaryVariant',
                'products.reviews'
            ])
            ->get();

        $data = $categories->map(function ($category) {
            // Skip if product collection is empty (double safety)
            if ($category->products->isEmpty()) {
                return null;
            }

            return [
                'category_name' => optional($category->translation)->name ?? $category->name ?? 'Unnamed Category',
                'products' => $category->products->map(function ($product) {
                    
                    // Handle image
                    $productImage = optional($product->thumbnail)->image_url ?? null;
                    $productImage = $productImage ? Storage::url($productImage) : null;

                    // Handle name
                    $productName = optional($product->translation)->name ?? $product->name ?? 'Product';

                    // Pricing and ratings
                    $primaryVariant = $product->primaryVariant;
                    $originalPrice  = $primaryVariant->converted_price ?? 0;
                    $discountPrice  = $primaryVariant->converted_discount_price ?? 0;
                    $averageRating  = round($product->reviews_avg_rating ?? 4.5, 1);
                    $reviewCount    = $product->reviews_count ?? 0;

                    return [
                        'id' => $product->id,
                        'name' => $productName,
                        'original_price' => $originalPrice,
                        'discount_price' => $discountPrice,
                        'average_rating' => $averageRating,
                        'review_count' => $reviewCount,
                        'image' => $productImage,
                    ];
                })->values(), // reset keys
            ];
        })->filter()->values(); // remove nulls & reindex

        return $data;
    }

    /**
     * Get all active categories
     */
    public function getCategories()
    {
        $categories = Category::where('status', 1)
            ->with('translation:id,category_id,name,image_url') // load only required fields
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->translation->name ?? null,
                    'image_url' => Storage::url($category->translation->image_url) ?? null,
                ];
            });
        return $categories;
    }

    public function getBanners(){
        $banners = Banner::where('status', 1)
            ->with('translation:id,banner_id,title,image_url') // Load only required fields
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'name' => $banner->translation->title ?? null,
                    'image_url' => Storage::url($banner->translation->image_url) ?? null,
                ];
            });
        
        return $banners;
    }
}
