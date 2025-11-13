<?php

namespace App\Helpers;

use Carbon\Carbon;

class FoodMenuHelper
{
    /**
     * Check food menu availability and return status with message
     *
     * @param mixed $product
     * @return array
     */
    public static function checkAvailability($product): array
    {
        $isFoodMenu = $product->is_food_menu === 'yes';
        $isAvailable = true;
        $availabilityMessage = '';
        
        if (!$isFoodMenu) {
            return [
                'is_available' => true,
                'message' => '',
                'is_food_menu' => false
            ];
        }
        
        $currentDateTime = now();
        $bookingFrom = $product->booking_from_datetime;
        $bookingTo = $product->booking_to_datetime;
        
        // If booking datetime range is not set, consider it unavailable
        if (!$bookingFrom || !$bookingTo) {
            return [
                'is_available' => false,
                'message' => 'Booking times not configured',
                'is_food_menu' => true
            ];
        }
        
        $bookingFromDateTime = Carbon::parse($bookingFrom);
        $bookingToDateTime = Carbon::parse($bookingTo);
        
        $isAvailable = $currentDateTime->between($bookingFromDateTime, $bookingToDateTime);
        
        if (!$isAvailable) {
            if ($currentDateTime < $bookingFromDateTime) {
                $availabilityMessage = "Available from " . $bookingFromDateTime->format('M j, Y g:i A');
            } else {
                $availabilityMessage = "Was available until " . $bookingToDateTime->format('M j, Y g:i A');
            }
        }
        
        return [
            'is_available' => $isAvailable,
            'message' => $availabilityMessage,
            'is_food_menu' => true,
            'booking_from' => $bookingFromDateTime,
            'booking_to' => $bookingToDateTime
        ];
    }
    
    /**
     * Check if product is available for ordering right now
     *
     * @param mixed $product
     * @return bool
     */
    public static function isAvailableNow($product): bool
    {
        $availability = self::checkAvailability($product);
        return $availability['is_available'];
    }
    
    /**
     * Get availability message for display
     *
     * @param mixed $product
     * @return string
     */
    public static function getAvailabilityMessage($product): string
    {
        $availability = self::checkAvailability($product);
        return $availability['message'];
    }
    
    /**
     * Get formatted booking time range
     *
     * @param mixed $product
     * @return string
     */
    public static function getBookingTimeRange($product): string
    {
        $bookingFrom = $product->booking_from_datetime;
        $bookingTo = $product->booking_to_datetime;
        
        if (!$bookingFrom || !$bookingTo) {
            return 'No booking times set';
        }
        
        return Carbon::parse($bookingFrom)->format('M j, Y g:i A') . ' - ' . 
               Carbon::parse($bookingTo)->format('M j, Y g:i A');
    }
}