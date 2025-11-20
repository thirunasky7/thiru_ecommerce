<?php
namespace App\Http\Controllers;

use App\Models\WeeklyMenu;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Api\HomeService;
use App\Traits\ApiResponseTrait;
use App\Models\Category;

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
        $categories = Category::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();

        $banners = $this->homeService->getBanners();

        return view('themes.xylo.pre-order', compact('threeDays', 'currentTime', 'banners','categories'));
    }

    public function regularOrderPage()
    {
        $currentTime = Carbon::now();

        // Get only ACTIVE categories
        $categories = Category::where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Get only active category IDs
        $activeCategoryIds = $categories->pluck('id');

        // Get ONLY active products that belong to active categories
        $saleItems = Product::where(function($query) {
                    $query->where('is_food_menu', 'no')
                        ->orWhere('product_mode', 'regular');
                })
                ->where('status', 1)                // product active
                ->whereIn('category_id', $activeCategoryIds) // category active
                ->get();

        $banners = $this->homeService->getBanners();

        return view('themes.xylo.regular-order', compact(
            'saleItems',
            'currentTime',
            'banners',
            'categories'
        ));
    }


    public function regularCategoryFilter($slug)
    {
        $currentTime = Carbon::now();

        // Get only ACTIVE category
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Get ONLY ACTIVE products inside ACTIVE category
        $saleItems = Product::where(function ($query) {
                $query->where('is_food_menu', 'no')
                    ->orWhere('product_mode', 'regular');
            })
            ->where('category_id', $category->id)
            ->where('status', 1)  // product active
            ->get();

        $banners = $this->homeService->getBanners();

        // Get only ACTIVE categories for filter/sidebar
        $categories = Category::where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->get();

        return view('themes.xylo.regular-order', compact(
            'saleItems',
            'currentTime',
            'banners',
            'categories',
            'category'
        ));
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