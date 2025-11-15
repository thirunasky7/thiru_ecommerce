<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Carbon\Carbon;
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
        // Current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Monthly Revenue Calculations
        $monthlyRevenue = Order::where('status', 'delivered')
            ->whereMonth('order_date', $currentMonth)
            ->whereYear('order_date', $currentYear)
            ->sum('total_amount');
            
        $lastMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('order_date', $currentMonth - 1)
            ->whereYear('order_date', $currentYear)
            ->sum('total_amount');
            
        $revenueGrowth = $lastMonthRevenue > 0 ? 
            round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 100;

        // Total Sales (All time delivered orders)
        $totalSales = Order::where('status', 'delivered')->sum('total_amount');
        
        // Order Statistics
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('order_date', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        
        // Order Type Breakdown
        $preorderCount = Order::whereHas('orderItems', function($q) {
            $q->where('meal_type', '!=', 'regular');
        })->count();
        
        $regularOrderCount = Order::whereHas('orderItems', function($q) {
            $q->where('meal_type', 'regular');
        })->count();

        // Customer Statistics
        $totalCustomers = Customer::count();
        $newCustomers = Customer::whereDate('created_at', today())->count();
        
        // Product Statistics
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        
        // Low stock calculation from product_variants
        $lowStockProducts = ProductVariant::where('stock', '<=', 10)->count();
        
        // Growth calculations (compared to last month)
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $thisMonthStart = now()->startOfMonth();
        
        $lastMonthOrders = Order::whereBetween('order_date', [$lastMonthStart, $lastMonthEnd])->count();
        $orderGrowth = $lastMonthOrders > 0 ? 
            round((($totalOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) : 100;
        
        $lastMonthCustomers = Customer::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $customerGrowth = $lastMonthCustomers > 0 ? 
            round((($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1) : 100;
        
        // Average Order Value
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Recent Orders with proper relationships
        $recentOrders = Order::with(['customer', 'orderItems'])
            ->latest()
            ->take(8)
            ->get();
        
        // Today's Pre-orders for kitchen
        $todaysPreorders = OrderItem::with(['order.customer', 'product'])
            ->whereDate('order_for_date', today())
            ->where('meal_type', '!=', 'regular')
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['confirmed', 'preparing']);
            })
            ->orderBy('meal_type')
            ->orderBy('created_at')
            ->get()
            ->groupBy('meal_type');

        // Top Selling Products
        $topProducts = Product::select([
                'products.id',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.quantity * order_items.unit_price), 0) as total_revenue')
            ])
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Low Stock Items
        $lowStockItems = ProductVariant::with(['product'])
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Monthly Revenue Chart Data (Last 6 months)
        $revenueChart = [
            'labels' => [],
            'data' => [],
            'preorder_data' => [],
            'regular_data' => []
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $revenueChart['labels'][] = $date->format('M');
            
            // Total monthly revenue
            $monthlyTotal = Order::where('status', 'delivered')
                ->whereMonth('order_date', $date->month)
                ->whereYear('order_date', $date->year)
                ->sum('total_amount');
            $revenueChart['data'][] = $monthlyTotal;
            
            // Pre-order revenue
            $preorderRevenue = Order::where('status', 'delivered')
                ->whereMonth('order_date', $date->month)
                ->whereYear('order_date', $date->year)
                ->whereHas('orderItems', function($q) {
                    $q->where('meal_type', '!=', 'regular');
                })
                ->sum('total_amount');
            $revenueChart['preorder_data'][] = $preorderRevenue;
            
            // Regular order revenue
            $regularRevenue = Order::where('status', 'delivered')
                ->whereMonth('order_date', $date->month)
                ->whereYear('order_date', $date->year)
                ->whereHas('orderItems', function($q) {
                    $q->where('meal_type', 'regular');
                })
                ->sum('total_amount');
            $revenueChart['regular_data'][] = $regularRevenue;
        }

        // Order Status Distribution
        $orderStatusData = [
            'labels' => ['Pending', 'Confirmed', 'Preparing', 'Out for Delivery', 'Delivered', 'Cancelled'],
            'data' => [
                Order::where('status', 'pending')->count(),
                Order::where('status', 'confirmed')->count(),
                Order::where('status', 'preparing')->count(),
                Order::where('status', 'out_for_delivery')->count(),
                Order::where('status', 'delivered')->count(),
                Order::where('status', 'cancelled')->count(),
            ]
        ];

        // Today's Revenue
        $todayRevenue = Order::where('status', 'delivered')
            ->whereDate('order_date', today())
            ->sum('total_amount');

        // Weekly Revenue
        $weeklyRevenue = Order::where('status', 'delivered')
            ->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');

        return view('admin.home', compact(
            'monthlyRevenue',
            'revenueGrowth',
            'totalSales',
            'totalOrders', 
            'todayOrders', 
            'pendingOrders',
            'preparingOrders',
            'totalCustomers', 
            'averageOrderValue',
            'newCustomers', 
            'totalProducts',
            'totalCategories', 
            'lowStockProducts', 
            'orderGrowth', 
            'customerGrowth',
            'recentOrders', 
            'topProducts', 
            'lowStockItems',
            'revenueChart', 
            'orderStatusData',
            'preorderCount',
            'regularOrderCount',
            'todaysPreorders',
            'todayRevenue',
            'weeklyRevenue'
        ));
    }

    /**
     * Get dashboard statistics for AJAX updates
     */
    public function getDashboardStats()
    {
        $todayOrders = Order::whereDate('order_date', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayRevenue = Order::where('status', 'delivered')
            ->whereDate('order_date', today())
            ->sum('total_amount');

        return response()->json([
            'today_orders' => $todayOrders,
            'pending_orders' => $pendingOrders,
            'today_revenue' => $todayRevenue,
            'updated_at' => now()->format('g:i A')
        ]);
    }
}