<?php

namespace App\Http\Controllers;

use App\Models\FoodPackage;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FoodPackageController extends Controller
{
    public function index()
    {
        $packages = FoodPackage::with(['vendor', 'shop'])
            ->active()
            ->orderBy('sort_order')
            ->orderByDesc('is_featured')
            ->get();

        $service = ServiceType::where('slug', 'food')->first();

        return view('themes.xylo.food-packages', compact('packages', 'service'));
    }

    public function show(string $slug)
    {
        $package = FoodPackage::with(['vendor', 'shop'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $related = FoodPackage::active()
            ->where('id', '!=', $package->id)
            ->take(3)
            ->get();

        $minStart = Carbon::tomorrow()->toDateString();

        return view('themes.xylo.food-package-detail', compact('package', 'related', 'minStart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:food_packages,id',
            'start_date' => 'required|date|after_or_equal:tomorrow',
            'quantity' => 'nullable|integer|min:1|max:5',
        ]);

        $package = FoodPackage::active()->findOrFail($request->package_id);

        if (!$package->isAvailable()) {
            return response()->json(['status' => false, 'message' => 'This package is not available right now.'], 422);
        }

        $quantity = (int) ($request->quantity ?? 1);
        $startDate = Carbon::parse($request->start_date)->toDateString();
        $endDate = $package->getEndDateFor($startDate)->toDateString();
        $cartItemId = 'pkg_' . md5($package->id . $startDate);

        $cart = session()->get('cart', []);

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $quantity;
        } else {
            $cart[$cartItemId] = [
                'cart_item_id' => $cartItemId,
                'item_type' => 'package',
                'product_id' => null,
                'food_package_id' => $package->id,
                'name' => $package->name . ' (' . $package->duration_days . '-day plan)',
                'price' => (float) $package->price,
                'image' => $package->image,
                'quantity' => $quantity,
                'order_for_date' => $startDate,
                'package_start_date' => $startDate,
                'package_end_date' => $endDate,
                'meal_type' => 'package',
                'expected_delivery_date' => $startDate,
                'display_order_date' => Carbon::parse($startDate)->format('M j') . ' – ' . Carbon::parse($endDate)->format('M j, Y'),
                'has_discount' => $package->compare_price && $package->compare_price > $package->price,
                'original_price' => (float) ($package->compare_price ?? $package->price),
                'duration_days' => $package->duration_days,
                'meals_per_day' => $package->meals_per_day,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'cart_count' => collect($cart)->sum('quantity'),
            'message' => 'Monthly package added to cart',
        ]);
    }
}
