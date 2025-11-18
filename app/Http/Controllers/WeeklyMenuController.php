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
        
        // Get menus for current day and next 2 days
        for ($i = 0; $i < 3; $i++) {
            $date = $today->copy()->addDays($i);
            $dayName = strtolower($date->englishDayOfWeek);
            
            $dayMenus = WeeklyMenu::where('day', $dayName)
                                ->where('status', 1)
                                ->get();
            
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

        // Get regular sale items
        $saleItems = Product::where(function($query) {
            $query->where('is_food_menu', 'no')
                  ->orWhere('product_mode', 'regular');
        })
        ->where('status', 1)
        ->get();

        $banners = $this->homeService->getBanners();

        return view('themes.xylo.menu', compact('threeDays', 'saleItems', 'currentTime', 'banners'));
    }

    public function preOrderPage()
    {
        $today = Carbon::today();
        $currentTime = Carbon::now();
        
        $threeDays = [];
        
        // Get pre-order menus for 3 days
        for ($i = 0; $i < 3; $i++) {
            $date = $today->copy()->addDays($i);
            $dayName = strtolower($date->englishDayOfWeek);
            
            $dayMenus = WeeklyMenu::where('day', $dayName)
                                ->where('status', 1)
                                ->get();
            
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

        $banners = $this->homeService->getBanners();

        return view('themes.xylo.pre-order', compact('threeDays', 'currentTime', 'banners'));
    }

    public function regularOrderPage()
    {
        $currentTime = Carbon::now();
        
        // Get regular sale items
        $saleItems = Product::where(function($query) {
            $query->where('is_food_menu', 'no')
                  ->orWhere('product_mode', 'regular');
        })
        ->where('status', 1)
        ->get();

        $banners = $this->homeService->getBanners();

        return view('themes.xylo.regular-order', compact('saleItems', 'currentTime', 'banners'));
    }

    private function loadMenuWithProducts($menu)
    {
        $products = $menu->products()->get();
        $menu->products = $products;
        return $menu;
    }

    private function getDisplayName($date, $index)
    {
        $today = Carbon::today();
        
        if ($date->isToday()) {
            return 'Today';
        } elseif ($date->isTomorrow()) {
            return 'Tomorrow';
        } else {
            return $date->format('l');
        }
    }

    public function cartPage()
    {
        $cartItems = session()->get('cart', []);
        $cartItems = array_filter($cartItems, function ($item) {
            return isset($item['product_id']) && $item['product_id'] != null;
        });
        session()->put('cart', $cartItems);
        $groupedCartItems = collect($cartItems)->groupBy('order_for_date');

        return view('themes.xylo.cart', compact('cartItems', 'groupedCartItems'));
    }
}