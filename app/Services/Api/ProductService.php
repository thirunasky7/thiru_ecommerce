<?php

namespace App\Services\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Get all products
     */
    public function getAllProducts()
    {
        $products = Product::where('status', 1)
            ->with(['thumbnail', 'primaryVariant', 'reviews', 'category', 'translation'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('id', 'desc')
            ->get();

        // Transform the collection
        return $products->map(function ($product) {

            $productImage = optional($product->thumbnail)->image_url ?? null;
            $productImage = $productImage ? Storage::url($productImage) : null;

            $productName = $product->translation->name
                ?? $product->name
                ?? 'Product';

            $primaryVariant = $product->primaryVariant;
            $originalPrice  = $primaryVariant->converted_price ?? 0;
            $discountPrice  = $primaryVariant->converted_discount_price ?? 0;
            $averageRating  = round($product->reviews_avg_rating ?? 4.5, 1);
            $reviewCount    = $product->reviews_count ?? 0;

            return [
                'id' => $product->id,
                'name' => $productName,
                'image' => $productImage,
                'category' => optional($product->category)->translation->name ?? 'Uncategorized',
                'original_price' => $originalPrice,
                'discount_price' => $discountPrice,
                'average_rating' => $averageRating,
                'review_count' => $reviewCount,
                'is_coming_soon' => ($product->is_coming_soon == 0) ? true :false ,
                'is_on_discount' => $discountPrice > 0 && $discountPrice < $originalPrice,
                'currency_symbol'  =>  '₹'
            ];
        });
    }

    public function getAllCategories(){
       return  Category::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->get();
    }
   
}
