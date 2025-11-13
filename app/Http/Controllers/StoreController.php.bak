<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\App;
use App\Models\Category;
use App\Models\Product;
use App\Models\Menu;
use App\Models\brand;

use App\Services\Api\HomeService;
use App\Traits\ApiResponseTrait;

class StoreController extends Controller
{
    use ApiResponseTrait;

    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index()
    {
        $locale = app()->getLocale();

        $send_data['banner'] = Banner::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->first();

       $send_data['categories'] = Category::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();
            
        $send_data['products'] = Product::where('status', 1)
        ->with(['translation', 'thumbnail', 'primaryVariant', 'reviews'])
        ->withCount('reviews')
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();
      
        
        $send_data['categoryProducts'] = $this->homeService->categoryProducts();
        $send_data['banners'] = $this->homeService->getBanners();
 

        return view('themes.xylo.home',$send_data);
    }

    public function allcategories(){
        $categories = Category::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->get();
        return view('themes.xylo.categories', compact('categories'));
    }

 public function allProducts(Request $request)
{
    $query = Product::with(['translation', 'thumbnail', 'primaryVariant', 'reviews', 'category'])
        ->where('status', 1);

    // Handle single category filter from query string (legacy support)
    if ($request->has('category')) {
        $category = Category::where('slug', $request->category)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
    }

    // Handle multiple category filters by slugs
    if ($request->has('categories')) {
        $categorySlugs = explode(',', $request->categories);
        $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id')->toArray();
        
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }
    }

    // Handle rating filter
    if ($request->has('rating')) {
        $minRating = (float) $request->rating;
        $query->whereHas('reviews', function($q) use ($minRating) {
            $q->select('product_id')
              ->groupBy('product_id')
              ->havingRaw('AVG(rating) >= ?', [$minRating]);
        });
    }

    // Handle sorting
    $sortBy = $request->get('sort', 'newest');
    switch ($sortBy) {
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        case 'rating':
            $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
            break;
        case 'newest':
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    $send_data['categories'] = Category::where('status', 1)->with('translation')->get();
    $send_data['products'] = $query->get();
    
    // Pass current filter values to view
    $send_data['currentFilters'] = [
        'categories' => $request->get('categories', ''),
        'rating' => $request->get('rating', ''),
        'sort' => $request->get('sort', 'newest')
    ];

    return view('themes.xylo.products', $send_data);
}
}
