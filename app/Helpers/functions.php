<?php

use App\Helpers\FoodMenuHelper;
use App\Helpers\ProductHelper;


if (!function_exists('check_food_menu_availability')) {
    function check_food_menu_availability($product)
    {
        return FoodMenuHelper::checkAvailability($product);
    }
}

if (!function_exists('is_food_menu_available')) {
    function is_food_menu_available($product)
    {
        return FoodMenuHelper::isAvailableNow($product);
    }
}

if (!function_exists('get_food_menu_availability_message')) {
    function get_food_menu_availability_message($product)
    {
        return FoodMenuHelper::getAvailabilityMessage($product);
    }
}

if (!function_exists('product_image')) {
    function product_image($product, $default = null)
    {
        return ProductHelper::getProductImage($product, $default);
    }
}

if (!function_exists('media_url')) {
    function media_url(?string $path, ?string $fallback = null): string
    {
        return ProductHelper::mediaUrl($path, $fallback);
    }
}

if (!function_exists('vendor_image')) {
    function vendor_image($vendor, string $type = 'cover'): string
    {
        $fallback = $type === 'logo'
            ? 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=200&q=60'
            : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=60';

        $path = $type === 'logo' ? ($vendor->logo ?? null) : ($vendor->cover_image ?? $vendor->logo ?? null);

        return media_url($path, $fallback);
    }
}

if (!function_exists('shop_image')) {
    function shop_image($shop, string $type = 'cover'): string
    {
        $fallback = $type === 'logo'
            ? 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=200&q=60'
            : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=60';

        $path = $type === 'logo'
            ? ($shop->logo ?? optional($shop->vendor)->logo ?? null)
            : ($shop->cover_image ?? $shop->logo ?? optional($shop->vendor)->cover_image ?? null);

        return media_url($path, $fallback);
    }
}

if (!function_exists('product_name')) {
    function product_name($product)
    {
        return ProductHelper::getProductName($product);
    }
}

if (!function_exists('product_price')) {
    function product_price($product)
    {
        return ProductHelper::getDisplayPrice($product);
    }
}

if (!function_exists('product_has_discount')) {
    function product_has_discount($product)
    {
        return ProductHelper::hasDiscount($product);
    }
}

if (!function_exists('product_discount_percent')) {
    function product_discount_percent($product)
    {
        return ProductHelper::getDiscountPercent($product);
    }
}

if (!function_exists('product_description')) {
    function product_description($product)
    {
        return ProductHelper::getProductDescription($product);
    }
}



if (!function_exists('is_product_available')) {
    function is_product_available($product)
    {
        return ProductHelper::isFoodMenuAvailable($product);
    }
}

if (!function_exists('product_availability_message')) {
    function product_availability_message($product)
    {
        return ProductHelper::getFoodMenuAvailabilityMessage($product);
    }
}