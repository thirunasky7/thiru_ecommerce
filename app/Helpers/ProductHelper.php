<?php

namespace App\Helpers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
class ProductHelper
{
    /**
     * Get product image URL with fallback
     */
    public static function getProductImage($product, $default = null)
    {
        $defaultImage = $default ?? 'https://via.placeholder.com/300x300?text=No+Image';
        
        // Check thumbnail first
        if ($product->thumbnail && $product->thumbnail->image_url) {
            return asset('/public/storage/' . $product->thumbnail->image_url);
        }
        
        // Check primary variant images
        $primaryVariant = $product->primaryVariant;
        if ($primaryVariant && $primaryVariant->images->isNotEmpty()) {
            return asset('/public/storage/' . $primaryVariant->images->first()->image_url);
        }
        
        // Check product images
        if ($product->images->isNotEmpty()) {
            return asset('/public/storage/' . $product->images->first()->image_url);
        }
        
        // Check if image_url exists directly on product
        if ($product->image_url) {
            return asset('/public/storage/' . $product->image_url);
        }
        
        return $defaultImage;
    }

    /**
     * Get product name with fallback
     */
    public static function getProductName($product)
    {
        return $product->translation->name ?? $product->name ?? 'Product';
    }

    /**
     * Get product description with fallback
     */
    public static function getProductDescription($product)
    {
          $clean = html_entity_decode(strip_tags($product->translation->description));
        $clean = preg_replace('/[^A-Za-z0-9\s\.\,\-\']+/', '', $clean);
        $clean = preg_replace('/\s{2,}/', ' ', $clean);
        $clean = trim($clean);
        $excerpt = Str::limit($clean, 100);

        return  $excerpt ?? $product->description ?? '';
    }

    /**
     * Get original price with currency conversion
     */
    public static function getOriginalPrice($product)
    {
        $primaryVariant = $product->primaryVariant;
        
        if ($primaryVariant && $primaryVariant->converted_price) {
            return $primaryVariant->converted_price;
        }
        
        if ($primaryVariant && $primaryVariant->price) {
            return $primaryVariant->price;
        }
        
        return $product->price ?? 0;
    }

    /**
     * Get discount price with currency conversion
     */
    public static function getDiscountPrice($product)
    {
        $primaryVariant = $product->primaryVariant;
        
        if ($primaryVariant && $primaryVariant->converted_discount_price) {
            return $primaryVariant->converted_discount_price;
        }
        
        if ($primaryVariant && $primaryVariant->discount_price) {
            return $primaryVariant->discount_price;
        }
        
        return $product->discount_price ?? 0;
    }

    /**
     * Check if product has discount
     */
    public static function hasDiscount($product)
    {
        $originalPrice = self::getOriginalPrice($product);
        $discountPrice = self::getDiscountPrice($product);
        
        return $discountPrice && $discountPrice > 0 && $originalPrice > $discountPrice;
    }

    /**
     * Calculate discount percentage
     */
    public static function getDiscountPercent($product)
    {
        if (!self::hasDiscount($product)) {
            return 0;
        }
        
        $originalPrice = self::getOriginalPrice($product);
        $discountPrice = self::getDiscountPrice($product);
        
        return round((($originalPrice - $discountPrice) / $originalPrice) * 100);
    }

    /**
     * Get display price (discount price if available, otherwise original price)
     */
    public static function getDisplayPrice($product)
    {
        if (self::hasDiscount($product)) {
            return self::getDiscountPrice($product);
        }
        
        return self::getOriginalPrice($product);
    }

    /**
     * Check if food menu item is available
     */
   public static function isFoodMenuAvailable($product)
{
    // If not a food menu item, it's always available
    if ($product->is_food_menu == 'no') {
        return true;
    }
    
    // Check if product is coming soon
    if ($product->is_coming_soon) {
        return false;
    }
    
    // Check product status
    if ($product->status == 0) {
        return false;
    }
    
    // For food menu items, check availability based on product mode and timing
    return self::checkFoodMenuTiming($product);
}

/**
 * Check food menu timing availability based on product mode
 */
public static function checkFoodMenuTiming($product)
{
    $now = Carbon::now();
    $currentHour = $now->hour;
    $currentDay = $now->englishDayOfWeek;
    
    // Get product mode (preorder or regular)
    $productMode = $product->product_mode ?? 'regular'; // default to regular if not set
    $mealType = $product->meal_type ?? self::detectMealTypeFromProduct($product);
   
    
    if (!$mealType) {
        return true; // If no meal type defined, assume available
    }
    
    // Regular product mode availability (6AM to 10PM daily)
    if ($productMode === 'regular') {
        return $currentHour >= 6 && $currentHour < 22; // 6AM to 10PM
    }
    
    // Preorder product mode availability
    if ($productMode === 'preorder') {
        return self::checkPreOrderAvailability($mealType, $now);
    }
    
    return true;
}

/**
 * Check pre-order availability based on meal type and current time
 */
private static function checkPreOrderAvailability($mealType, $currentTime)
{
    $currentHour = $currentTime->hour;
    $currentDate = $currentTime->format('Y-m-d');
    
    // Define cutoff times for each meal type
    $cutoffTimes = [
        'breakfast' => [
            'today' => null, // Never available for today (order closed)
            'tomorrow' => 24, // Available until midnight for tomorrow
            'day_after' => 24 // Available until midnight for day after
        ],
        'lunch' => [
            'today' => 10, // Available until 10AM for today
            'tomorrow' => 24, // Available until midnight for tomorrow
            'day_after' => 24 // Available until midnight for day after
        ],
        'snacks' => [
            'today' => 18, // Available until 6PM for today
            'tomorrow' => 24, // Available until midnight for tomorrow
            'day_after' => 24 // Available until midnight for day after
        ],
        'dinner' => [
            'today' => 17, // Available until 5PM for today
            'tomorrow' => 24, // Available until midnight for tomorrow
            'day_after' => 24 // Available until midnight for day after
        ]
    ];
    
    if (!isset($cutoffTimes[$mealType])) {
        return false; // Unknown meal type
    }
    
    $mealCutoff = $cutoffTimes[$mealType];
    
    // Check if ordering for today
    if (self::isOrderingForToday($currentTime)) {
        // For breakfast today - always closed
        if ($mealType === 'breakfast') {
            return false;
        }
        
        // For other meals today - check if before cutoff time
        return $currentHour < $mealCutoff['today'];
    }
    
    // Check if ordering for tomorrow
    if (self::isOrderingForTomorrow($currentTime)) {
        return $currentHour < $mealCutoff['tomorrow'];
    }
    
    // Check if ordering for day after tomorrow
    if (self::isOrderingForDayAfterTomorrow($currentTime)) {
        return $currentHour < $mealCutoff['day_after'];
    }
    
    return false;
}

/**
 * Check if the order is for today's delivery
 */
private static function isOrderingForToday($currentTime)
{
    // Assuming delivery date is today
    // You might need to adjust this based on how you determine delivery date
    return true; // Default implementation - adjust as needed
}

/**
 * Check if the order is for tomorrow's delivery
 */
private static function isOrderingForTomorrow($currentTime)
{
    // This would depend on your delivery date selection logic
    // For now, returning false - you'll need to implement based on your cart/delivery system
    return false;
}

/**
 * Check if the order is for day after tomorrow's delivery
 */
private static function isOrderingForDayAfterTomorrow($currentTime)
{
    // This would depend on your delivery date selection logic
    // For now, returning false - you'll need to implement based on your cart/delivery system
    return false;
}

/**
 * Detect meal type from product
 */
private static function detectMealTypeFromProduct($product)
{
    // This is a sample implementation - adjust based on your product data structure
    $name = strtolower(self::getProductName($product));
    $description = strtolower(self::getProductDescription($product));
    $category = strtolower(self::getProductCategory($product));
    
    $searchText = $name . ' ' . $description . ' ' . $category;
    
    if (str_contains($searchText, 'breakfast') || 
        str_contains($searchText, 'morning') ||
        str_contains($searchText, 'break fast')) {
        return 'breakfast';
    } elseif (str_contains($searchText, 'lunch') || 
              str_contains($searchText, 'afternoon')) {
        return 'lunch';
    } elseif (str_contains($searchText, 'dinner') || 
              str_contains($searchText, 'night') ||
              str_contains($searchText, 'evening')) {
        return 'dinner';
    } elseif (str_contains($searchText, 'snack') || 
              str_contains($searchText, 'evening')) {
        return 'snacks';
    }
    
    
    return null;
}

/**
 * Get product name (adjust based on your implementation)
 */



/**
 * Get product category (adjust based on your implementation)
 */
private static function getProductCategory($product)
{
    return $product->category->name ?? '';
}

/**
 * Get availability message for display
 */
public static function getFoodMenuAvailabilityMessage($product)
{
    if ($product->is_food_menu !== 'yes') {
        return 'Available';
    }
    
    $productMode = $product->product_mode ?? 'regular';
    $mealType = $product->meal_type ?? self::detectMealTypeFromProduct($product);
    
    if ($productMode === 'regular') {
        return 'Available today (6AM - 10PM)';
    }
    
    if ($productMode === 'preorder') {
        $now = Carbon::now();
        
        if ($mealType === 'breakfast') {
            return 'Today\'s breakfast order closed';
        } elseif ($mealType === 'lunch') {
            $availableUntil = '10 AM';
            return "Today\'s lunch available until {$availableUntil}";
        } elseif ($mealType === 'snacks') {
            $availableUntil = '6 PM';
            return "Today\'s snacks available until {$availableUntil}";
        } elseif ($mealType === 'dinner') {
            $availableUntil = '5 PM';
            return "Today\'s dinner available until {$availableUntil}";
        }
    }
    
    return 'Check availability';
}

/**
 * Check if product can be added to cart with specific delivery date
 */
public static function canAddToCartWithDeliveryDate($product, $deliveryDate)
{
    if ($product->is_food_menu !== 'yes') {
        return true;
    }
    
    $productMode = $product->product_mode ?? 'regular';
    $mealType = $product->meal_type ?? self::detectMealTypeFromProduct($product);
    
    if ($productMode === 'regular') {
        // Regular products available for any delivery date within operating hours
        $currentHour = Carbon::now()->hour;
        return $currentHour >= 6 && $currentHour < 22;
    }
    
    if ($productMode === 'preorder') {
        $currentTime = Carbon::now();
        $deliveryCarbon = Carbon::parse($deliveryDate);
        
        // Check if delivery is today, tomorrow, or day after tomorrow
        $daysDiff = $currentTime->diffInDays($deliveryCarbon, false);
        
        if ($daysDiff === 0) { // Today
            return self::checkTodayPreOrderAvailability($mealType, $currentTime->hour);
        } elseif ($daysDiff === 1) { // Tomorrow
            return true; // Always available for tomorrow
        } elseif ($daysDiff === 2) { // Day after tomorrow
            return true; // Always available for day after tomorrow
        }
    }
    
    return false;
}

/**
 * Check today's pre-order availability based on current hour
 */
private static function checkTodayPreOrderAvailability($mealType, $currentHour)
{
    switch ($mealType) {
        case 'breakfast':
            return false; // Never available for today
        case 'lunch':
            return $currentHour < 10; // Available until 10AM
        case 'snacks':
            return $currentHour < 18; // Available until 6PM
        case 'dinner':
            return $currentHour < 17; // Available until 5PM
        default:
            return false;
    }
}

    /**
     * Get product rating information
     */
    public static function getProductRating($product)
    {
        return [
            'average' => round($product->reviews_avg_rating ?? 4.5, 1),
            'count' => $product->reviews_count ?? 0,
            'stars' => self::generateStarRating($product->reviews_avg_rating ?? 4.5),
        ];
    }

    /**
     * Generate star rating HTML
     */
    public static function generateStarRating($rating)
    {
        $stars = '';
        $fullStars = floor($rating);
        $hasHalfStar = ($rating - $fullStars) >= 0.5;
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullStars) {
                $stars .= '<i class="fas fa-star text-yellow-400 text-xs"></i>';
            } elseif ($hasHalfStar && $i == $fullStars + 1) {
                $stars .= '<i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>';
                $hasHalfStar = false;
            } else {
                $stars .= '<i class="far fa-star text-yellow-400 text-xs"></i>';
            }
        }
        
        return $stars;
    }

    /**
     * Check if product is in stock
     */
    public static function isInStock($product)
    {
        $primaryVariant = $product->primaryVariant;
        
        if ($primaryVariant) {
            return $primaryVariant->stock > 0;
        }
        
        return $product->stock > 0;
    }

    /**
     * Get stock quantity
     */
    public static function getStockQuantity($product)
    {
        $primaryVariant = $product->primaryVariant;
        
        if ($primaryVariant) {
            return $primaryVariant->stock;
        }
        
        return $product->stock ?? 0;
    }
}