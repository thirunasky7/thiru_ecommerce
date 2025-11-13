<?php

use Carbon\Carbon;

  function checkFoodMenuAvailability($product)
    {
        $isAvailable = true;
        $availabilityMessage = '';
        $isFoodMenu = $product->is_food_menu === 'yes';

        if ($isFoodMenu) {
            $currentDateTime = Carbon::now();

            $availableFrom = null;
            $availableTo = null;

            if (!empty($product->available_from_date) && !empty($product->available_from_time)) {
                $availableFrom = Carbon::parse($product->available_from_date . ' ' . $product->available_from_time);
            } else {
                $availableFrom = null;
            }

            if (!empty($product->available_to_date) && !empty($product->available_to_time)) {
                $availableTo = Carbon::parse($product->available_to_date . ' ' . $product->available_to_time);
            } else {
                $availableTo = null;
            }

            if ($availableFrom && $availableTo) {
                if ($currentDateTime->between($availableFrom, $availableTo)) {
                    $isAvailable = true;
                } else {
                    $isAvailable = false;

                    if ($currentDateTime->lt($availableFrom)) {
                        $availabilityMessage = "Available from " . $availableFrom->format('M j, Y g:i A');
                    } elseif ($currentDateTime->gt($availableTo)) {
                        $availabilityMessage = "Availability ended on " . $availableTo->format('M j, Y g:i A');
                    }
                }
            } else {
                $isAvailable = false;
                $availabilityMessage = "Availability time not set.";
            }
        } else {
            $isAvailable = true;
        }

        return [
            'isAvailable' => $isAvailable,
            'message' => $availabilityMessage,
        ];
    }
