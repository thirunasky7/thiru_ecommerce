<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'orderItems'])
            ->latest();

        // Filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type != 'all') {
            if ($request->type == 'preorder') {
                $query->whereHas('orderItems', function($q) {
                    $q->where('meal_type', '!=', 'regular');
                });
            } else {
                $query->whereHas('orderItems', function($q) {
                    $q->where('meal_type', 'regular');
                });
            }
        }

        if ($request->has('date') && $request->date != 'all') {
            if ($request->date == 'today') {
                $query->whereDate('order_date', Carbon::today());
            } elseif ($request->date == 'tomorrow') {
                $query->whereHas('orderItems', function($q) {
                    $q->whereDate('order_for_date', Carbon::tomorrow());
                });
            }
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        
        // Group order items by delivery date
        $groupedItems = $order->orderItems->groupBy(function($item) {
            return Carbon::parse($item->order_for_date)->format('Y-m-d');
        });

        return view('admin.orders.show', compact('order', 'groupedItems'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,out_for_delivery,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($request->status === 'delivered') {
            $order->update(['delivered_date' => Carbon::now()]);
            $order->orderItems()->update([
                'is_delivered' => true,
                'delivered_time' => Carbon::now(),
            ]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updateItemStatus(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'preparation_status' => 'required|in:pending,preparing,ready,delivered,cancelled'
        ]);

        $orderItem->update([
            'preparation_status' => $request->preparation_status,
            'is_ready' => $request->preparation_status == 'ready',
            'ready_time' => $request->preparation_status == 'ready' ? Carbon::now() : null
        ]);

        return response()->json(['success' => true, 'message' => 'Item status updated.']);
    }

    public function kitchenDisplay()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Get today's pre-orders grouped by meal type
        $todayPreorders = OrderItem::with(['order.customer', 'product', 'foodPackage'])
            ->where(function ($q) use ($today) {
                $q->whereDate('order_for_date', $today)
                  ->orWhere(function ($q2) use ($today) {
                      $q2->where('item_type', 'package')
                         ->whereDate('package_start_date', '<=', $today)
                         ->whereDate('package_end_date', '>=', $today);
                  });
            })
            ->where('meal_type', '!=', 'regular')
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['confirmed', 'preparing', 'pending']);
            })
            ->orderBy('meal_type')
            ->orderBy('created_at')
            ->get()
            ->groupBy('meal_type');

        // Get regular orders for today
        $regularOrders = OrderItem::with(['order.customer', 'product'])
            ->where('meal_type', 'regular')
            ->whereHas('order', function ($q) use ($today) {
                $q->whereIn('status', ['confirmed', 'preparing', 'pending'])
                  ->whereDate('order_date', $today);
            })
            ->orderBy('created_at')
            ->get();

        $packageOrders = OrderItem::with(['order.customer', 'foodPackage'])
            ->where('item_type', 'package')
            ->whereDate('package_start_date', '<=', $today)
            ->whereDate('package_end_date', '>=', $today)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['confirmed', 'preparing', 'pending']);
            })
            ->get();

        return view('admin.orders.kitchen', compact('todayPreorders', 'regularOrders', 'packageOrders', 'today'));
    }

    public function deliverySchedule()
    {
        $today = Carbon::today();
        $schedule = [];

        for ($i = 0; $i < 3; $i++) {
            $date = $today->copy()->addDays($i);
            $dateFormatted = $date->format('Y-m-d');

            $schedule[$dateFormatted] = OrderItem::with(['order.customer', 'product', 'foodPackage'])
                ->where(function ($q) use ($date) {
                    $q->whereDate('order_for_date', $date)
                      ->orWhere(function ($q2) use ($date) {
                          $q2->where('item_type', 'package')
                             ->whereDate('package_start_date', '<=', $date)
                             ->whereDate('package_end_date', '>=', $date);
                      });
                })
                ->whereHas('order', function ($q) {
                    $q->whereIn('status', ['confirmed', 'preparing', 'out_for_delivery', 'pending']);
                })
                ->get()
                ->groupBy(function ($item) {
                    return $item->meal_type ?: 'general';
                });
        }

        return view('admin.orders.delivery-schedule', compact('schedule'));
    }
}
