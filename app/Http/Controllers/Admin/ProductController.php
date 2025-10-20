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

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'translations.' . $defaultLang . '.name' => 'required|string|max:255',

            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.SKU' => 'required|string|max:255',
            'variants.*.barcode' => 'nullable|string|max:255',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.dimensions' => 'nullable|string|max:255',
            'variants.*.language_code' => 'nullable|string|size:2',
            'variants.*.size_id' => 'nullable|exists:attribute_values,id',
            'variants.*.color_id' => 'nullable|exists:attribute_values,id',
        ]);

        DB::transaction(function () use ($request, $defaultLang) {

        $defaultName = $request->translations[$defaultLang]['name'] ?? 'product';
        $slug = $this->generateUniqueSlug($defaultName);


            // 1. Create product
            $product = Product::create([
                'shop_id' => 1,
                'vendor_id' => 1,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'product_type' => 'variable',
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
                    'is_primary'     => 1, 
                ]);
            
                // Variant Translation (optional)
                $variant->translations()->create([
                    'language_code' => $variantData['language_code'] ?? 'en',
                    'name' => $variantData['name'],
                ]);
            
                // --- Handle size ---
                if (!empty($variantData['size_id'])) {
                    DB::table('product_variant_attribute_values')->insert([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $variantData['size_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            
                    // 👇 Also ensure it's added to product_attribute_values (no duplicate)
                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_value_id' => $variantData['size_id'],
                    ]);
                }
            
                // --- Handle color ---
                if (!empty($variantData['color_id'])) {
                    DB::table('product_variant_attribute_values')->insert([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $variantData['color_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            
                    // 👇 Also ensure it's added to product_attribute_values (no duplicate)
                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_value_id' => $variantData['color_id'],
                    ]);
                }

                $variantIndex++;
            }
            
        });
    
        return redirect()->route('admin.products.index')->with('success',  __('cms.products.success_create'));
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
        $product = Product::with(['translations', 'variants', 'images', 'variants.translations'])->findOrFail($id);
    
        $languages = Language::where('active', 1)->get();

        $categories = Category::all();

        $brands = Brand::all();

        $attributes = Attribute::with('values.translations')->get();

        $sizes = Attribute::where('name', 'Size')->first()?->values ?? collect();
        $colors = Attribute::where('name', 'Color')->first()?->values ?? collect();

        $attributeSizeMap = [
            'small' => AttributeValue::where('attribute_id', $sizes->firstWhere('name', 'Small')->id ?? 0)->pluck('id')->first(),
            'medium' => AttributeValue::where('attribute_id', $sizes->firstWhere('name', 'Medium')->id ?? 0)->pluck('id')->first(),
            'large' => AttributeValue::where('attribute_id', $sizes->firstWhere('name', 'Large')->id ?? 0)->pluck('id')->first(),
        ];

        return view('admin.products.edit', compact('product', 'languages', 'categories', 'brands', 'attributes', 'sizes', 'colors', 'attributeSizeMap'));
    }

  
    public function update(Request $request, $id)
    {                  
        $product = Product::findOrFail($id);
        $defaultLang = config('app.locale');

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'translations.' . $defaultLang . '.name' => 'required|string|max:255',
    
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.SKU' => 'required|string|max:255',
            'variants.*.barcode' => 'nullable|string|max:255',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.dimensions' => 'nullable|string|max:255',
            'variants.*.language_code' => 'nullable|string|size:2',
            'variants.*.size_id' => 'nullable|exists:attribute_values,id',
            'variants.*.color_id' => 'nullable|exists:attribute_values,id',
        ]);
    
        DB::transaction(function () use ($request, $product, $defaultLang) {
            $defaultName = $request->translations[$defaultLang]['name'] ?? 'product';
            $slug = $this->generateUniqueSlug($defaultName, $product->id);
    
            $product->update([
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
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
    
            // 2. Update images (delete old images if necessary)
            if ($request->hasFile('images')) {
                foreach ($product->images as $image) {
                    // Optionally delete old images
                    Storage::disk('public')->delete($image->image_url);
                    $image->delete();
                }
    
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public'); 
    
                    $product->images()->create([
                        'name' => $image->getClientOriginalName(),
                        'image_url' => $path,
                        'type' => 'thumb', 
                    ]);
                }
            }
    
            $product->variants()->delete();
            DB::table('product_variant_attribute_values')->where('product_id', $product->id)->delete();
    
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
                    'is_primary'     => 1,
                ]);
    
                $variant->translations()->create([
                    'language_code' => $variantData['language_code'] ?? $defaultLang,
                    'name' => $variantData['name'],
                ]);
    
                // --- Size ---
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
    
                // --- Color ---
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
            }
        });
    
        return redirect()->route('admin.products.index')->with('success',  __('cms.products.success_update'));
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
}