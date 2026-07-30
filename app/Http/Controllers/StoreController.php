<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ServiceType;
use App\Models\Shop;
use App\Models\Vendor;
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
            ->with(['translation', 'thumbnail', 'primaryVariant', 'reviews', 'images'])
            ->withCount('reviews')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $send_data['categoryProducts'] = $this->homeService->categoryProducts();
        $send_data['banners'] = $this->homeService->getBanners();

        return view('themes.xylo.home', $send_data);
    }

    public function allcategories()
    {
        $categories = Category::where('status', 1)
            ->with('translation')
            ->orderBy('id', 'desc')
            ->get();

        return view('themes.xylo.categories', compact('categories'));
    }

    public function allProducts(Request $request)
    {
        $query = $this->filteredProductsQuery($request);

        $products = $query->paginate(12)->withQueryString();

        if ($request->ajax() || $request->boolean('partial')) {
            return response()->json([
                'html' => view('themes.xylo.partials.product-grid-items', [
                    'products' => $products,
                    'cols' => 3,
                ])->render(),
                'hasMore' => $products->hasMorePages(),
                'nextPage' => $products->currentPage() + 1,
                'total' => $products->total(),
            ]);
        }

        return view('themes.xylo.products', [
            'products' => $products,
            'categories' => Category::where('status', 1)->with('translation')->orderBy('id')->get(),
            'serviceTypes' => ServiceType::active()->orderBy('sort_order')->get(),
            'shops' => Shop::active()->orderBy('name')->get(['id', 'name', 'service_type_id']),
            'vendors' => Vendor::active()->orderBy('business_name')->get(['id', 'name', 'business_name']),
            'currentFilters' => [
                'q' => $request->get('q', ''),
                'categories' => array_filter(explode(',', (string) $request->get('categories', ''))),
                'category' => $request->get('category', ''),
                'service_type' => $request->get('service_type', ''),
                'shop' => $request->get('shop', ''),
                'vendor' => $request->get('vendor', ''),
                'rating' => $request->get('rating', ''),
                'price_min' => $request->get('price_min', ''),
                'price_max' => $request->get('price_max', ''),
                'featured' => $request->boolean('featured'),
                'in_stock' => $request->boolean('in_stock'),
                'sort' => $request->get('sort', 'newest'),
            ],
        ]);
    }

    protected function filteredProductsQuery(Request $request)
    {
        $query = Product::with(['translation', 'thumbnail', 'images', 'primaryVariant', 'reviews', 'category', 'shop', 'serviceType'])
            ->where('status', 1);

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('slug', 'like', "%{$term}%")
                    ->orWhereHas('translation', fn ($t) => $t->where('name', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('category')) {
            $category = Category::where('status', 1)->where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            } elseif (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            }
        }

        if ($request->filled('categories')) {
            $categorySlugs = array_filter(explode(',', $request->categories));
            $categoryIds = Category::where('status', 1)->whereIn('slug', $categorySlugs)->pluck('id')->toArray();
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('service_type')) {
            $query->where('service_type_id', $request->service_type);
        }

        if ($request->filled('shop')) {
            $query->where('shop_id', $request->shop);
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->filled('rating')) {
            $minRating = (float) $request->rating;
            $query->whereHas('reviews', function ($q) use ($minRating) {
                $q->select('product_id')
                    ->groupBy('product_id')
                    ->havingRaw('AVG(rating) >= ?', [$minRating]);
            });
        }

        if ($request->filled('price_min')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '>=', (float) $request->price_min)
                    ->orWhereHas('primaryVariant', fn ($v) => $v->where('price', '>=', (float) $request->price_min));
            });
        }

        if ($request->filled('price_max')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '<=', (float) $request->price_max)
                    ->orWhereHas('primaryVariant', fn ($v) => $v->where('price', '<=', (float) $request->price_max));
            });
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('in_stock')) {
            $query->where(function ($q) {
                $q->where('stock', '>', 0)
                    ->orWhereHas('primaryVariant', fn ($v) => $v->where('stock', '>', 0));
            });
        }

        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('slug', 'asc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'newest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        return $query;
    }
}
