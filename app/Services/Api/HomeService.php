<?php

namespace App\Services\Api;

use App\Models\Category;
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
                        'image_url' => $productImage,
                    ];
                })->values(), // reset keys
            ];
        })->filter()->values(); // remove nulls & reindex

        return $data;
    }

    /**
     * Get all active categories
     */
    public function getAllCategories()
    {
        return Category::where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->get();
    }
}
