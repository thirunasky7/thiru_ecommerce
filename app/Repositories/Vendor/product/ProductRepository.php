<?php

namespace App\Repositories\Vendor\Product;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Services\Vendor\ImageService;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use App\Models\StoreSetting;
use App\Models\Shop;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ProductRepository implements ProductRepositoryInterface
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
   
    public function all()
    {
        return Product::all();
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function store($data)
    { 
        $sku = $data['SKU'];
        $skuCounter = 1;
        
        while (Product::where('SKU', $sku)->exists()) {
            $sku = $data['SKU'] . '-' . $skuCounter;
            $skuCounter++;
        }

        $defaultCurrencyCode = getWebConfig('default_currency', 'USD');

        $shop = Shop::where('vendor_id', 1)->first();

        if (!$shop) {
            throw new Exception('No shop found for this vendor.');
        }

        $product = Product::create([
            'vendor_id' => 1,
            'shop_id' => $shop->id, 
            'category_id' => $data['category_id'],
            'price' => currency_to_usd($data['price'], $defaultCurrencyCode),
            'stock' => $data['stock'],
            'status' => $data['status'] ?? true,
            'slug' => $data['slug'],
            'currency' => $data['currency'],
            'SKU' => $sku,
            'weight' => $data['weight'],
            'dimensions' => $data['dimensions'],
            'product_type' => $data['product_type'],
        ]);

        if (isset($data['product_image_url']) && $data['product_image_url'] instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = $this->imageService->uploadImage($data['product_image_url'], 'products'); 
            
            $productImage = new ProductImage([
                'name' => basename($imagePath), 
                'image_url' => $imagePath,                
                'product_id' => $product->id, 
                'type' => $data['image_type'] ?? 'thumb',
            ]);
            
            $productImage->save();
        }

        return $product; 
    }

    public function update($id, array $data)
    {
      
        \Log::info('Attempting to update product with ID ' . $id, ['data' => $data]);

        $product = Product::findOrFail($id);
    
        if (isset($data['image_url']) && $data['image_url'] instanceof \Illuminate\Http\UploadedFile) {
            if ($product->images->isNotEmpty()) {
                $this->imageService->deleteImage($product->images->first()->image_url);
                $product->images->first()->delete();  
            }
    
            $imagePath = $this->imageService->uploadImage($data['image_url'], 'products');
    
            $productImage = new ProductImage([
                'name' => basename($imagePath), 
                'image_url' => $imagePath, 
                'product_id' => $product->id, 
                'type' => $data['image_type'] ?? 'thumb',
            ]);
    
            $productImage->save();
        } else {
            if (isset($data['image_type']) && $product->images->isNotEmpty()) {
                $product->images->first()->update(['type' => $data['image_type']]);
            }
        }
    
        $slug = Str::slug($data['name'] ?? $product->name);
        $slugBase = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }
    
        $sku = $data['SKU'];
        $skuCounter = 1;
        while (Product::where('SKU', $sku)->where('id', '!=', $id)->exists()) {
            $sku = $data['SKU'] . '-' . $skuCounter;
            $skuCounter++;
        }
    
        $updated = $product->update([
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'status' => $data['status'] ?? true,
            'slug' => $slug,
            'currency' => $data['currency'],
            'SKU' => $sku,
            'weight' => $data['weight'],
            'dimensions' => $data['dimensions'],
            'product_type' => $data['product_type'],
        ]);
    
        if ($updated) {
            \Log::info('Product updated successfully with ID ' . $id);
        } else {
            \Log::error('Failed to update product with ID ' . $id);
        }
    
        return $product;  

    }

    public function destroy($id)
    {      
        $product = $this->find($id);

        $productImages = $product->images;

        foreach ($productImages as $productImage) {
            if ($productImage->image_url) {
                $this->imageService->deleteImage($productImage->image_url);
            }

            $productImage->delete();
        }

        return $product->delete();
    } 

}


