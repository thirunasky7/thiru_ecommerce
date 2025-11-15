<?php

use App\Helpers\FoodMenuHelper;

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