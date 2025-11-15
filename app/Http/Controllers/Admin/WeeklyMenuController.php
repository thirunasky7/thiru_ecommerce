<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WeeklyMenu;
use Illuminate\Http\Request;

class WeeklyMenuController extends Controller
{
    public function index()
    {
        $menus = WeeklyMenu::orderBy('day')->get();
        return view('admin.weekly-menu.index', compact('menus'));
    }

    public function create()
    {
        $products = Product::where('product_mode', 'preorder')->get();
        return view('admin.weekly-menu.create', compact('products'));
    }

    public function store(Request $request)
{
    $request->validate([
        'day' => 'required',
        'meal_type' => 'required',
        'product_ids' => 'array|nullable',
        'status' => 'nullable'
    ]);

    // Check for existing menu with same day and meal type
    $existingMenu = WeeklyMenu::where('day', strtolower($request->day))
                             ->where('meal_type', $request->meal_type)
                             ->first();

    if ($existingMenu) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Menu already exists for ' . ucfirst($request->day) . ' ' . ucfirst($request->meal_type));
    }

    WeeklyMenu::create([
        'day' => strtolower($request->day),
        'meal_type' => $request->meal_type,
        'product_ids' => $request->product_ids,
        'status' => $request->status ? 1 : 0
    ]);

    return redirect()->route('admin.weeklymenu.index')
        ->with('success', 'Menu created successfully');
}

    public function edit($id)
    {
        $weeklymenu = WeeklyMenu::findOrFail($id);
     
        $products = Product::where('product_mode', 'preorder')->get();

        // Fix for product_ids format - handle both string and array formats
        $selectedProducts = $weeklymenu->product_ids ?? [];
        
        // If it's a string like "1" or JSON string, convert to array
        if (is_string($selectedProducts)) {
            // Try to decode JSON first
            $decoded = json_decode($selectedProducts, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $selectedProducts = $decoded;
            } else {
                // If it's a simple string like "1", convert to array
                $selectedProducts = [$selectedProducts];
            }
        }
        
        // Ensure all values are strings for consistency with checkbox values
        $selectedProducts = array_map('strval', (array)$selectedProducts);

        return view('admin.weekly-menu.edit', compact('weeklymenu', 'products', 'selectedProducts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'day' => 'required',
            'meal_type' => 'required',
            'product_ids' => 'array|nullable',
            'status' => 'nullable'
        ]);

        $menu = WeeklyMenu::findOrFail($id);

        // Check for existing menu with same day and meal type (excluding current menu)
        $existingMenu = WeeklyMenu::where('day', strtolower($request->day))
                                ->where('meal_type', $request->meal_type)
                                ->where('id', '!=', $id)
                                ->first();

        if ($existingMenu) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Menu already exists for ' . ucfirst($request->day) . ' ' . ucfirst($request->meal_type));
        }

        $menu->update([
            'day' => strtolower($request->day),
            'meal_type' => $request->meal_type,
            'product_ids' => $request->product_ids,
            'status' => $request->status ? 1 : 0
        ]);

        return redirect()->route('admin.weeklymenu.index')
            ->with('success', 'Menu updated successfully');
    }

    public function destroy($id)
    {
        WeeklyMenu::findOrFail($id)->delete();
        return back()->with('success', 'Menu deleted');
    }
}
