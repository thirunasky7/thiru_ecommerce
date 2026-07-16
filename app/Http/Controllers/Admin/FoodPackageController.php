<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodPackage;
use App\Models\Vendor;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodPackageController extends Controller
{
    public function index()
    {
        $packages = FoodPackage::with(['vendor', 'shop'])->orderBy('sort_order')->latest()->paginate(15);
        return view('admin.food-packages.index', compact('packages'));
    }

    public function create()
    {
        $vendors = Vendor::active()->orderBy('name')->get();
        $shops = Shop::active()->orderBy('name')->get();
        return view('admin.food-packages.create', compact('vendors', 'shops'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['status'] = $request->boolean('status');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['meal_types'] = $request->input('meal_types', []);
        $data['sample_menu'] = $this->parseSampleMenu($request->input('sample_menu_text'));

        FoodPackage::create($data);

        return redirect()->route('admin.food-packages.index')
            ->with('success', 'Monthly food package created successfully.');
    }

    public function edit(FoodPackage $food_package)
    {
        $vendors = Vendor::active()->orderBy('name')->get();
        $shops = Shop::active()->orderBy('name')->get();
        $package = $food_package;

        return view('admin.food-packages.edit', compact('package', 'vendors', 'shops'));
    }

    public function update(Request $request, FoodPackage $food_package)
    {
        $data = $this->validated($request, $food_package->id);
        $data['status'] = $request->boolean('status');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['meal_types'] = $request->input('meal_types', []);
        $data['sample_menu'] = $this->parseSampleMenu($request->input('sample_menu_text'));

        if ($food_package->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $food_package->id);
        }

        $food_package->update($data);

        return redirect()->route('admin.food-packages.index')
            ->with('success', 'Monthly food package updated successfully.');
    }

    public function destroy(FoodPackage $food_package)
    {
        $food_package->delete();
        return back()->with('success', 'Package deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'includes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:7|max:90',
            'meals_per_day' => 'required|integer|min:1|max:5',
            'meal_types' => 'nullable|array',
            'meal_types.*' => 'in:breakfast,lunch,dinner,snack',
            'badge' => 'nullable|string|max:50',
            'vendor_id' => 'nullable|exists:vendors,id',
            'shop_id' => 'nullable|exists:shops,id',
            'sort_order' => 'nullable|integer|min:0',
            'max_subscribers' => 'nullable|integer|min:1',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
            'sample_menu_text' => 'nullable|string',
        ]);

        unset($data['sample_menu_text'], $data['meal_types']);

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            FoodPackage::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function parseSampleMenu(?string $text): array
    {
        if (!$text) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
