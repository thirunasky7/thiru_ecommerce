<?php

namespace App\Repositories\Vendor;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Services\Vendor\ImageService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
    protected ImageService $imageService;

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

        $defaultCurrencyCode = function_exists('getWebConfig')
            ? getWebConfig('default_currency', 'USD')
            : 'USD';

        $vendorId = auth('vendor')->id() ?: 1;
        $shop = Shop::where('vendor_id', $vendorId)->first()
            ?: Shop::where('vendor_id', 1)->first();

        if (!$shop) {
            throw new Exception('No shop found for this vendor.');
        }

        $product = Product::create([
            'vendor_id' => $vendorId,
            'shop_id' => $shop->id,
            'category_id' => $data['category_id'],
            'price' => function_exists('currency_to_usd')
                ? currency_to_usd($data['price'], $defaultCurrencyCode)
                : $data['price'],
            'stock' => $data['stock'],
            'status' => $data['status'] ?? true,
            'slug' => $data['slug'],
            'currency' => $data['currency'],
            'SKU' => $sku,
            'weight' => $data['weight'] ?? null,
            'dimensions' => $data['dimensions'] ?? null,
            'product_type' => $data['product_type'] ?? 'product',
        ]);

        if (isset($data['product_image_url']) && $data['product_image_url'] instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = $this->imageService->uploadImage($data['product_image_url'], 'products');

            ProductImage::create([
                'name' => basename($imagePath),
                'image_url' => $imagePath,
                'product_id' => $product->id,
                'type' => $data['image_type'] ?? 'thumb',
            ]);
        }

        return $product;
    }

    public function update($id, array $data)
    {
        Log::info('Attempting to update product with ID ' . $id, ['data' => $data]);

        $product = Product::findOrFail($id);

        if (isset($data['image_url']) && $data['image_url'] instanceof \Illuminate\Http\UploadedFile) {
            if ($product->images->isNotEmpty()) {
                $this->imageService->deleteImage($product->images->first()->image_url);
                $product->images->first()->delete();
            }

            $imagePath = $this->imageService->uploadImage($data['image_url'], 'products');

            ProductImage::create([
                'name' => basename($imagePath),
                'image_url' => $imagePath,
                'product_id' => $product->id,
                'type' => $data['image_type'] ?? 'thumb',
            ]);
        } elseif (isset($data['image_type']) && $product->images->isNotEmpty()) {
            $product->images->first()->update(['type' => $data['image_type']]);
        }

        $slug = Str::slug($data['name'] ?? $product->slug);
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

        $product->update([
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'status' => $data['status'] ?? true,
            'slug' => $slug,
            'currency' => $data['currency'],
            'SKU' => $sku,
            'weight' => $data['weight'] ?? $product->weight,
            'dimensions' => $data['dimensions'] ?? $product->dimensions,
            'product_type' => $data['product_type'] ?? $product->product_type,
        ]);

        return $product;
    }

    public function destroy($id)
    {
        $product = $this->find($id);

        foreach ($product->images as $productImage) {
            if ($productImage->image_url) {
                $this->imageService->deleteImage($productImage->image_url);
            }
            $productImage->delete();
        }

        return $product->delete();
    }
}
