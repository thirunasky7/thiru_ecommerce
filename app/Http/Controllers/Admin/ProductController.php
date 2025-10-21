<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use App\Models\Vendor;
use App\Models\ProductVariantAttributeValue;
use App\Models\Product;
use App\Models\Category;
use App\Models\Language;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ProductService;
use App\Services\Admin\CategoryService;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use App\Models\Attribute;
use Illuminate\Support\Facades\DB;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use App\Models\ProductVariantTranslation;
use App\Models\ProductAttributeValue;
use App\Models\AttributeValue;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    protected $categoryService;
    protected $productService;
  

    public function __construct(CategoryService $categoryService,ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        
    }

    public function index()
    {
        return view('admin.products.index');
    }

    public function getProducts(Request $request)
    {
        try {
            return $this->productService->getProductsForDataTable($request);
        } catch (\Exception $e) {
            \Log::error("Error fetching product data: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching product data.'], 500);
        }
    }

    public function create()
    {
        
        $languages = Language::where('active', 1)->get(); 
        
       // $categories = Category::all(); 
        
       // $brands = Brand::all(); 

        $categories = Category::with('translations')->get();
        $brands = Brand::with('translations')->get();
        
        $attributes = Attribute::with('values.translations')->get(); 
        
        $sizes = Attribute::where('name', 'Size')->first()?->values ?? collect();
        $colors = Attribute::where('name', 'Color')->first()?->values ?? collect();

        $attributeSizeMap = [
            'small' => AttributeValue::where('attribute_id', $sizes->firstWhere('name', 'Small')->id ?? 0)->pluck('id')->first(),
            'medium' => AttributeValue::where('attribute_id', $sizes->firstWhere('name', 'Medium')->id ?? 0)->pluck('id')->first(),
            'large' => AttributeValue::where('attribute_id', $sizes->firstWhere('name', 'Large')->id ?? 0)->pluck('id')->first(),
        ];

        return view('admin.products.create', compact('languages', 'categories', 'brands', 'attributes', 'sizes', 'colors', 'attributeSizeMap'));
    }


    public function store(Request $request)
{    
    $defaultLang = config('app.locale');

    // Base validation rules
    $validationRules = [
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'product_type' => 'required|in:simple,variable',
        'translations.' . $defaultLang . '.name' => 'required|string|max:255',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ];

    // Add validation rules based on product type
    if ($request->product_type === 'simple') {
        $validationRules = array_merge($validationRules, [
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'stock' => 'required|integer|min:0',
            'SKU' => 'required|string|max:255|unique:product_variants,SKU',
            'barcode' => 'nullable|string|max:255|unique:product_variants,barcode',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'size_id' => 'nullable|exists:attribute_values,id',
            'color_id' => 'nullable|exists:attribute_values,id',
        ]);
    } else {
        $validationRules = array_merge($validationRules, [
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.SKU' => 'required|string|max:255|unique:product_variants,SKU',
            'variants.*.barcode' => 'nullable|string|max:255|unique:product_variants,barcode',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.dimension' => 'nullable|string|max:255',
            'variants.*.language_code' => 'required|string|size:2',
            'variants.*.size_id' => 'nullable|exists:attribute_values,id',
            'variants.*.color_id' => 'nullable|exists:attribute_values,id',
        ]);
    }

    // Validate the request
    $validated = $request->validate($validationRules);

    try {
        DB::transaction(function () use ($request, $defaultLang) {
            $defaultName = $request->translations[$defaultLang]['name'] ?? 'product';
            $slug = $this->generateUniqueSlug($defaultName);

            // 1. Create product
            $product = Product::create([
                'shop_id' => 1, // You might want to get this from auth or session
                'vendor_id' => 1, // You might want to get this from auth or session
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'product_type' => $request->product_type,
            ]);

            // 2. Store translations
            foreach ($request->translations as $lang => $data) {
                $product->translations()->create([
                    'language_code' => $lang,
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'short_description' => $data['short_description'] ?? null,
                    'tags' => $data['tags'] ?? null,
                ]);
            }

            // 3. Store images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public'); 

                    $product->images()->create([
                        'name' => $image->getClientOriginalName(),
                        'image_url' => $path,
                        'type' => 'thumb', 
                    ]);
                }
            }         

            // 4. Handle product variants based on type
            if ($request->product_type === 'simple') {
                // Create single variant for simple product
                $variant = $product->variants()->create([
                    'variant_slug' => Str::slug($defaultName) . '-simple-' . uniqid(),
                    'price' => $request->price,
                    'discount_price' => $request->discount_price ?? null,
                    'stock' => $request->stock,
                    'SKU' => $request->SKU,
                    'barcode' => $request->barcode ?? null,
                    'weight' => $request->weight ?? null,
                    'dimensions' => $request->dimensions ?? null,
                    'is_primary' => 1,
                ]);

                // Variant Translation
                $variant->translations()->create([
                    'language_code' => $defaultLang,
                    'name' => $defaultName,
                ]);

                // Handle attributes for simple product
                if (!empty($request->size_id)) {
                    DB::table('product_variant_attribute_values')->insert([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $request->size_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_value_id' => $request->size_id,
                    ]);
                }

                if (!empty($request->color_id)) {
                    DB::table('product_variant_attribute_values')->insert([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $request->color_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_value_id' => $request->color_id,
                    ]);
                }

            } else {
                // Handle variable product with multiple variants
                $variantIndex = 0;
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'variant_slug' => Str::slug($variantData['name']) . '-' . uniqid(),
                        'price' => $variantData['price'],
                        'discount_price' => $variantData['discount_price'] ?? null,
                        'stock' => $variantData['stock'],
                        'SKU' => $variantData['SKU'],
                        'barcode' => $variantData['barcode'] ?? null,
                        'weight' => $variantData['weight'] ?? null,
                        'dimensions' => $variantData['dimension'] ?? null,
                        'is_primary' => ($variantIndex === 0), // First variant is primary
                    ]);

                    // Variant Translation
                    $variant->translations()->create([
                        'language_code' => $variantData['language_code'] ?? 'en',
                        'name' => $variantData['name'],
                    ]);

                    // Handle size
                    if (!empty($variantData['size_id'])) {
                        DB::table('product_variant_attribute_values')->insert([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $variantData['size_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        ProductAttributeValue::firstOrCreate([
                            'product_id' => $product->id,
                            'attribute_value_id' => $variantData['size_id'],
                        ]);
                    }

                    // Handle color
                    if (!empty($variantData['color_id'])) {
                        DB::table('product_variant_attribute_values')->insert([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $variantData['color_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        ProductAttributeValue::firstOrCreate([
                            'product_id' => $product->id,
                            'attribute_value_id' => $variantData['color_id'],
                        ]);
                    }

                    $variantIndex++;
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', __('cms.products.success_create'));

    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
    }
}
    
    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug exists, if so, append a number to make it unique
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }


   public function edit($id)
    {
        $product = Product::with([
            'translations', 
            'variants.translations',
            'variants.attributeValues',
            'images'
        ])->findOrFail($id);

        $activeLanguages = Language::where('active', 1)->get();
        $categories = Category::all();
        $brands = Brand::all();
        
        $sizes = Attribute::where('name', 'Size')->first()?->values ?? collect();
        $colors = Attribute::where('name', 'Color')->first()?->values ?? collect();

        return view('admin.products.edit', compact(
            'product', 
            'activeLanguages', 
            'categories', 
            'brands', 
            'sizes', 
            'colors'
        ));
    }

  
    public function update(Request $request, $id)
{                  
    $product = Product::findOrFail($id);
    $defaultLang = config('app.locale');

    // Base validation rules
    $validationRules = [
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'product_type' => 'required|in:simple,variable',
        'translations.' . $defaultLang . '.name' => 'required|string|max:255',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ];

    // Add validation rules based on product type
    if ($request->product_type === 'simple') {
        $validationRules = array_merge($validationRules, [
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'stock' => 'required|integer|min:0',
            'SKU' => 'required|string|max:255|unique:product_variants,SKU,' . $product->id . ',product_id',
            'barcode' => 'nullable|string|max:255|unique:product_variants,barcode,' . $product->id . ',product_id',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'size_id' => 'nullable|exists:attribute_values,id',
            'color_id' => 'nullable|exists:attribute_values,id',
        ]);
    } else {
        $validationRules = array_merge($validationRules, [
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.SKU' => 'required|string|max:255|unique:product_variants,SKU,' . $product->id . ',product_id',
            'variants.*.barcode' => 'nullable|string|max:255|unique:product_variants,barcode,' . $product->id . ',product_id',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.dimension' => 'nullable|string|max:255',
            'variants.*.language_code' => 'required|string|size:2',
            'variants.*.size_id' => 'nullable|exists:attribute_values,id',
            'variants.*.color_id' => 'nullable|exists:attribute_values,id',
        ]);
    }

    $validated = $request->validate($validationRules);

    try {
        DB::transaction(function () use ($request, $product, $defaultLang) {
            $defaultName = $request->translations[$defaultLang]['name'] ?? 'product';
            $slug = $this->generateUniqueSlug($defaultName, $product->id);

            // Update product
            $product->update([
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'product_type' => $request->product_type,
            ]);

            // Update translations
            foreach ($request->translations as $lang => $data) {
                $product->translations()->updateOrCreate(
                    ['language_code' => $lang],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'] ?? null,
                        'short_description' => $data['short_description'] ?? null,
                        'tags' => $data['tags'] ?? null,
                    ]
                );
            }

            // Handle images (append new images, don't delete old ones)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public'); 

                    $product->images()->create([
                        'name' => $image->getClientOriginalName(),
                        'image_url' => $path,
                        'type' => 'thumb', 
                    ]);
                }
            }

            // Delete existing variants and attribute values
            $product->variants()->delete();
            DB::table('product_variant_attribute_values')->where('product_id', $product->id)->delete();
            ProductAttributeValue::where('product_id', $product->id)->delete();

            // Handle product variants based on type
            if ($request->product_type === 'simple') {
                // Create single variant for simple product
                $variant = $product->variants()->create([
                    'variant_slug' => Str::slug($defaultName) . '-simple-' . uniqid(),
                    'price' => $request->price,
                    'discount_price' => $request->discount_price ?? null,
                    'stock' => $request->stock,
                    'SKU' => $request->SKU,
                    'barcode' => $request->barcode ?? null,
                    'weight' => $request->weight ?? null,
                    'dimensions' => $request->dimensions ?? null,
                    'is_primary' => 1,
                ]);

                // Variant Translation
                $variant->translations()->create([
                    'language_code' => $defaultLang,
                    'name' => $defaultName,
                ]);

                // Handle attributes for simple product
                if (!empty($request->size_id)) {
                    DB::table('product_variant_attribute_values')->insert([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $request->size_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_value_id' => $request->size_id,
                    ]);
                }

                if (!empty($request->color_id)) {
                    DB::table('product_variant_attribute_values')->insert([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $request->color_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_value_id' => $request->color_id,
                    ]);
                }

            } else {
                // Handle variable product with multiple variants
                $variantIndex = 0;
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'variant_slug' => Str::slug($variantData['name']) . '-' . uniqid(),
                        'price' => $variantData['price'],
                        'discount_price' => $variantData['discount_price'] ?? null,
                        'stock' => $variantData['stock'],
                        'SKU' => $variantData['SKU'],
                        'barcode' => $variantData['barcode'] ?? null,
                        'weight' => $variantData['weight'] ?? null,
                        'dimensions' => $variantData['dimension'] ?? null,
                        'is_primary' => ($variantIndex === 0),
                    ]);

                    // Variant Translation
                    $variant->translations()->create([
                        'language_code' => $variantData['language_code'] ?? 'en',
                        'name' => $variantData['name'],
                    ]);

                    // Handle size
                    if (!empty($variantData['size_id'])) {
                        DB::table('product_variant_attribute_values')->insert([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $variantData['size_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        ProductAttributeValue::firstOrCreate([
                            'product_id' => $product->id,
                            'attribute_value_id' => $variantData['size_id'],
                        ]);
                    }

                    // Handle color
                    if (!empty($variantData['color_id'])) {
                        DB::table('product_variant_attribute_values')->insert([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $variantData['color_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        ProductAttributeValue::firstOrCreate([
                            'product_id' => $product->id,
                            'attribute_value_id' => $variantData['color_id'],
                        ]);
                    }

                    $variantIndex++;
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', __('cms.products.success_update'));

    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Error updating product: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
       
        try {
            $result = $this->productService->destroy($id);
    
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' =>  __('cms.products.success_delete'),
                ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product!'
            ]);
        } catch (\Exception $e) {
            \Log::error("Error deleting product with ID {$id}: " . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the product.'
            ]);
        }
    } 

    public function updateStatus(Request $request)
    {
       // Validate the incoming request
    $request->validate([
        'id' => 'required|exists:products,id', 
        'status' => 'required|boolean', 
    ]);

    $product = Product::find($request->id);
    $product->status = $request->status;
    $product->save();

    if ($product) {
        return response()->json([
            'success' => true,
            'message' => __('cms.products.status_updated'),
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Product status could not be updated.',
        ]);
    }

    }

    public function deleteImage($imageId)
    {
        try {
            $image = ProductImage::findOrFail($imageId);
            
            // Delete the file from storage
            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            // Delete the image record from database
            $image->delete();
            
            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting image: ' . $e->getMessage()], 500);
        }
    }
}