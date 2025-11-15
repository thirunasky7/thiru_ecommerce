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