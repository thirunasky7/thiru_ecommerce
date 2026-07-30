<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class MarketplaceImagesSeeder extends Seeder
{
    public function run(): void
    {
        $vendorImages = [
            'food@thaiyur.com' => [
                'logo' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=200&q=60',
                'cover' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=60',
            ],
            'grocery@thaiyur.com' => [
                'logo' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=200&q=60',
                'cover' => 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?auto=format&fit=crop&w=1200&q=60',
            ],
            'shop@thaiyur.com' => [
                'logo' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=200&q=60',
                'cover' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=60',
            ],
        ];

        foreach ($vendorImages as $email => $imgs) {
            Vendor::where('email', $email)->update([
                'logo' => $imgs['logo'],
                'cover_image' => $imgs['cover'],
            ]);
        }

        $shopImages = [
            'somsak-thai-kitchen' => [
                'logo' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=200&q=60',
                'cover' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=60',
            ],
            'freshbasket-grocery' => [
                'logo' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=200&q=60',
                'cover' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=1200&q=60',
            ],
            'yur-lifestyle' => [
                'logo' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=200&q=60',
                'cover' => 'https://images.unsplash.com/photo-1484101403633-562f8913981e?auto=format&fit=crop&w=1200&q=60',
            ],
        ];

        foreach ($shopImages as $slug => $imgs) {
            Shop::where('slug', $slug)->update([
                'logo' => $imgs['logo'],
                'cover_image' => $imgs['cover'],
            ]);
        }

        $productImages = [
            'pad-thai-noodles' => 'https://images.unsplash.com/photo-1559314809-0d155014e29e?auto=format&fit=crop&w=800&q=60',
            'green-curry-chicken' => 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?auto=format&fit=crop&w=800&q=60',
            'butter-chicken-meal' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=60',
            'mango-lassi' => 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?auto=format&fit=crop&w=800&q=60',
            'organic-tomatoes-1kg' => 'https://images.unsplash.com/photo-1546470427-227c7369a4d0?auto=format&fit=crop&w=800&q=60',
            'bananas-dozen' => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=800&q=60',
            'fresh-milk-1l' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=800&q=60',
            'basmati-rice-5kg' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=60',
            'bamboo-storage-box' => 'https://images.unsplash.com/photo-1616046229478-9901c5536a45?auto=format&fit=crop&w=800&q=60',
            'herbal-face-wash' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=800&q=60',
            'cotton-kitchen-towel-set' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=60',
        ];

        $defaults = [
            'food' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=60',
            'grocery' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=60',
            'product' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=60',
        ];

        Product::with('serviceType')->chunkById(50, function ($products) use ($productImages, $defaults) {
            foreach ($products as $product) {
                $url = $productImages[$product->slug]
                    ?? $defaults[$product->product_type ?? 'product']
                    ?? $defaults['product'];

                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'type' => 'thumb',
                    ],
                    [
                        'name' => $product->slug . '-thumb',
                        'image_url' => $url,
                    ]
                );

                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'type' => 'slide',
                        'name' => $product->slug . '-slide-1',
                    ],
                    [
                        'image_url' => $url,
                    ]
                );
            }
        });
    }
}
