<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      // Total Sales
    $totalSales = Order::where('status', 'completed')->sum('total_amount');
    
    // Total Orders
    $totalOrders = Order::count();
    $todayOrders = Order::whereDate('created_at', today())->count();
    $pendingOrders = Order::where('status', 'pending')->count();
    
    // Customers
    $totalCustomers = Customer::count();
    $newCustomers = Customer::whereDate('created_at', today())->count();
    
    // Products & Variants
    $totalProducts = Product::count();
    $totalCategories = Category::count();
    
    // Low stock calculation from product_variants
    $lowStockProducts = ProductVariant::where('stock', '<=', 10)->count();
    
    // Growth calculations (compared to last month)
    $lastMonthStart = now()->subMonth()->startOfMonth();
    $lastMonthEnd = now()->subMonth()->endOfMonth();
    $thisMonthStart = now()->startOfMonth();
    
    $lastMonthSales = Order::where('status', 'completed')
        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->sum('total_amount');
    $salesGrowth = $lastMonthSales > 0 ? round((($totalSales - $lastMonthSales) / $lastMonthSales) * 100, 1) : 0;
    
    $lastMonthOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
    $orderGrowth = $lastMonthOrders > 0 ? round((($totalOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) : 0;
    
    $lastMonthCustomers = Customer::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
    $customerGrowth = $lastMonthCustomers > 0 ? round((($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1) : 0;
    
    // Average Order Value
    $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
    
    // Recent Orders with safe customer relationship
    $recentOrders = Order::with(['customer' => function($query) {
        $query->select('id', 'name', 'email');
    }])->latest()->take(10)->get();
    
    // Top Selling Products - Handle translation properly
    $topProducts = ProductVariant::select([
            'product_variants.id',
            'product_variants.product_id',
            DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'),
            DB::raw('COALESCE(SUM(order_details.quantity * order_details.price), 0) as total_revenue')
        ])
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->leftJoin('order_details', 'product_variants.id', '=', 'order_details.variant_id')
        ->groupBy('product_variants.id', 'product_variants.product_id')
        ->orderBy('total_sold', 'desc')
        ->take(5)
        ->get()
        ->load(['product.translation']); // Eager load translations
    
    // Low Stock Items - Handle translation properly
    $lowStockItems = ProductVariant::select([
            'product_variants.id',
            'product_variants.product_id',
            'product_variants.stock',
            'product_variants.SKU',
            DB::raw('10 as min_stock')
        ])
        ->with(['product.translation']) // Eager load translations
        ->where('product_variants.stock', '<=', 10)
        ->orderBy('product_variants.stock', 'asc')
        ->take(5)
        ->get();
    
    // Sales Chart Data (Last 30 days)
    $salesChart = [
        'labels' => [],
        'data' => []
    ];
    
    for ($i = 29; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $salesChart['labels'][] = $date->format('M d');
        
        $dailySales = Order::where('status', 'completed')
            ->whereDate('created_at', $date)
            ->sum('total_amount');
        $salesChart['data'][] = $dailySales;
    }
    
    // Order Status Distribution
    $orderStatusData = [
        'labels' => ['Completed', 'Processing', 'Pending', 'Cancelled'],
        'data' => [
            Order::where('status', 'completed')->count(),
            Order::where('status', 'processing')->count(),
            Order::where('status', 'pending')->count(),
            Order::where('status', 'cancelled')->count(),
        ]
    ];

    return view('admin.home', compact(
        'totalSales', 'totalOrders', 'totalCustomers', 'averageOrderValue',
        'todayOrders', 'pendingOrders', 'newCustomers', 'totalProducts',
        'totalCategories', 'lowStockProducts', 'salesGrowth', 'orderGrowth',
        'customerGrowth', 'recentOrders', 'topProducts', 'lowStockItems',
        'salesChart', 'orderStatusData'
    ));
    }
}
