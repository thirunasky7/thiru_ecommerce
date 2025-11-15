<?php
namespace App\Http\Controllers;

use App\Models\WeeklyMenu;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Api\HomeService;
use App\Traits\ApiResponseTrait;

class WeeklyMenuController extends Controller
{
    use ApiResponseTrait;

    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function showThreeDayMenu()
    {
        $today = Carbon::today();
        $currentTime = Carbon::now();
        $currentHour = $currentTime->hour;
        
        $threeDays = [];
        
        // Determine starting day based on current time
        $startDay = $today;
        
        // If it's after 10 PM, show next day's menus
        if ($currentHour >= 22) {
            $startDay = $today->copy()->addDay();
        } elseif ($today->englishDayOfWeek === 'Friday' && $currentHour >= 12) {
            // Friday afternoon - show Friday dinner + Saturday
            $startDay = $today;
        }
        
        // Get menus for current day and next 2 days
        for ($i = 0; $i < 3; $i++) {
            $date = $startDay->copy()->addDays($i);
            $dayName = strtolower($date->englishDayOfWeek);
            
            $dayMenus = WeeklyMenu::where('day', $dayName)
                                ->where('status', 1)
                                ->get();
            
            // Load products for each menu - FIXED: Use get() to execute query
            $menusWithProducts = $dayMenus->map(function($menu) {
                return $this->loadMenuWithProducts($menu);
            });
            
            $threeDays[] = [
                'date' => $date,
                'day_name' => $dayName,
                'display_name' => $this->getDisplayName($date, $i),
                'menus' => $menusWithProducts
            ];
        }

        // Get regular sale items (non-food menu items or regular products)
        $saleItems = Product::where(function($query) {
            $query->where('is_food_menu', 'no')
                  ->orWhere('product_mode', 'regular');
        })
        ->active() // Use direct where instead of scope
        ->get();
         $banners = $this->homeService->getBanners();

        return view('themes.xylo.menu', compact('threeDays', 'saleItems', 'currentTime','banners'));
    }

    private function loadMenuWithProducts($menu)
    {
        // FIXED: Add ->get() to execute the query
        $products = $menu->products()->get();
        
        // Add products to menu object as a collection
        $menu->products = $products;
        
        return $menu;
    }

    // ... rest of your methods remain the same
    private function filterMenusByTime($menus, $date, $currentTime)
    {
        $currentDay = $currentTime->englishDayOfWeek;
        $targetDay = $date->englishDayOfWeek;
        $currentHour = $currentTime->hour;
        
        // If looking at today's menu
        if ($currentDay === $targetDay) {
            return $menus->filter(function($menu) use ($currentHour) {
                return $this->isMenuAvailableNow($menu->meal_type, $currentHour);
            });
        }
        
        // For future days, show all menus
        return $menus;
    }

    private function isMenuAvailableNow($mealType, $currentHour)
    {
        // Define time ranges for each meal type
        $timeRanges = [
            'breakfast' => [6, 11],   // 6 AM to 11 AM
            'lunch' => [11, 15],      // 11 AM to 3 PM
            'snacks' => [15, 19],     // 3 PM to 7 PM
            'dinner' => [19, 23]      // 7 PM to 11 PM
        ];

        if (!isset($timeRanges[$mealType])) {
            return true; // If meal type not defined, show it
        }

        [$startHour, $endHour] = $timeRanges[$mealType];
        
        return $currentHour >= $startHour && $currentHour < $endHour;
    }

    private function getDisplayName($date, $index)
    {
        $today = Carbon::today();
        
        if ($date->isToday()) {
            return 'Today';
        } elseif ($date->isTomorrow()) {
            return 'Tomorrow';
        } else {
            return $date->format('D, M j');
        }
    }

   
public function cartPage()
{
    $cartItems = session()->get('cart', []);

    // Remove null values
    $cartItems = array_filter($cartItems, function ($item) {
        return isset($item['product_id']) && $item['product_id'] != null;
    });

    session()->put('cart', $cartItems);

    // Group cart items by delivery date for better display
    $groupedCartItems = collect($cartItems)->groupBy('order_for_date');

    return view('themes.xylo.cart', compact('cartItems', 'groupedCartItems'));
}

private function calculateDeliveryDate()
{
    $now = now();

    // If order placed after 7 PM → deliver next to next day
    if ($now->hour >= 19) {
        return $now->copy()->addDays(2)->format('d M, D');
    }

    // Otherwise deliver tomorrow
    return $now->copy()->addDay()->format('d M, D');
}
}