<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManualOrder;
use App\Models\ManualOrderItem;

class OrderController extends Controller
{
    public function index()
    {
        return ManualOrder::with('items')->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'door_number' => 'required|string',
            'items' => 'required|array',
            'items.*.item_name' => 'required',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric'
        ]);

        $total = collect($request->items)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $order = ManualOrder::create([
            'door_number' => $request->door_number,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        foreach ($request->items as $item) {
            $order->items()->create($item);
        }

        return response()->json([
            'success' => true,
            'order' => $order->load('items')
        ]);
    }

    public function markDelivered($id)
    {
        $order = ManualOrder::findOrFail($id);
        $order->update(['status' => 'delivered']);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered'
        ]);
    }

}
