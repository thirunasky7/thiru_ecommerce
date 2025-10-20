<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use App\Models\ProductVariantTranslation;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\AttributeValueTranslation;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'vendor_id' => 1,
                'shop_id' => 1,
                'slug' => 'cool-tshirt',
                'price' => 19.99,
                'currency' => 'USD',
                'stock' => 100,
                'SKU' => 'TSH123',
                'category_id' => 4,
                'brand_id' => null,
                'product_type' => 'Fashion',
                'status' => 1,
                'translations' => [
                    'en' => ['name' => 'Cool T-Shirt', 'description' => 'Stylish and comfortable.'],
                    'fr' => ['name' => 'T-shirt Cool', 'description' => 'Élégant et confortable.']
                ],
                'attributes' => [
                    'Color' => ['en' => ['Red', 'Black'], 'fr' => ['Rouge', 'Noir']],
                    'Size' => ['en' => ['Small', 'Large'], 'fr' => ['Petit', 'Grand']]
                ],
                'variants' => [
                    ['variant_slug' => 'cool-tshirt-red-small', 'price' => 25.99, 'stock' => 30, 'SKU' => 'TSH-RED-SMALL',
                        'translations' => ['en' => 'Cool T-Shirt - Red Small', 'fr' => 'T-shirt Cool - Rouge Petit']],
                    ['variant_slug' => 'cool-tshirt-black-large', 'price' => 29.99, 'stock' => 20, 'SKU' => 'TSH-BLACK-LARGE',
                        'translations' => ['en' => 'Cool T-Shirt - Black Large', 'fr' => 'T-shirt Cool - Noir Grand']]
                ]
            ],
            [
                'vendor_id' => 1,
                'shop_id' => 1,
                'slug' => 'sport-shoes',
                'price' => 49.99,
                'currency' => 'USD',
                'stock' => 50,
                'SKU' => 'SHOE123',
                'category_id' => 2,
                'brand_id' => 1,
                'product_type' => 'Footwear',
                'status' => 1,
                'translations' => [
                    'en' => ['name' => 'Sport Shoes', 'description' => 'Perfect for running.'],
                    'fr' => ['name' => 'Chaussures de sport', 'description' => 'Idéales pour courir.']
                ],
                'attributes' => [
                    'Size' => ['en' => ['7', '8', '9'], 'fr' => ['7', '8', '9']],
                    'Color' => ['en' => ['White', 'Blue'], 'fr' => ['Blanc', 'Bleu']]
                ],
                'variants' => [
                    ['variant_slug' => 'sport-shoes-white-7', 'price' => 50.99, 'stock' => 10, 'SKU' => 'SHOE-WHITE-7',
                        'translations' => ['en' => 'Sport Shoes - White 7', 'fr' => 'Chaussures de sport - Blanc 7']]
                ]
            ],
            [
                'vendor_id' => 1,
                'shop_id' => 1,
                'slug' => 'wireless-headphones',
                'price' => 79.99,
                'currency' => 'USD',
                'stock' => 30,
                'SKU' => 'HEAD123',
                'category_id' => 5,
                'brand_id' => 2,
                'product_type' => 'Electronics',
                'status' => 1,
                'translations' => [
                    'en' => ['name' => 'Wireless Headphones', 'description' => 'Noise-canceling audio.'],
                    'fr' => ['name' => 'Casque sans fil', 'description' => 'Audio avec suppression de bruit.']
                ],
                'attributes' => [
                    'Color' => ['en' => ['Black', 'White'], 'fr' => ['Noir', 'Blanc']]
                ],
                'variants' => []
            ]
        ];

        foreach ($products as $productData) {
            $translations = $productData['translations'];
            $attributes = $productData['attributes'];
            $variants = $productData['variants'];
            unset($productData['translations'], $productData['attributes'], $productData['variants']);

            // Create or update product
            $product = Product::updateOrCreate(['slug' => $productData['slug']], $productData);

            // Insert translations
            foreach ($translations as $languageCode => $translationData) {
                ProductTranslation::updateOrCreate(
                    ['product_id' => $product->id, 'language_code' => $languageCode], 
                    $translationData
                );
            }

            // Insert attributes and link them to the product
            foreach ($attributes as $attributeName => $values) {
                $attribute = Attribute::firstOrCreate(['name' => $attributeName]);

                foreach ($values['en'] as $index => $value) {
                    // Create attribute value
                    $attributeValue = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);

                    // Insert translations
                    foreach ($values as $langCode => $translations) {
                        AttributeValueTranslation::updateOrCreate([
                            'attribute_value_id' => $attributeValue->id,
                            'language_code' => $langCode
                        ], ['translated_value' => $translations[$index]]);
                    }

                    // Insert into product_attribute_values pivot table
                    DB::table('product_attribute_values')->updateOrInsert([
                        'product_id' => $product->id,
                        'attribute_value_id' => $attributeValue->id
                    ]);
                }
            }

            // Insert product variants
            foreach ($variants as $variantData) {
                $variantTranslations = $variantData['translations'];
                unset($variantData['translations']);

                $variant = ProductVariant::updateOrCreate(
                    ['variant_slug' => $variantData['variant_slug']], 
                    array_merge($variantData, ['product_id' => $product->id])
                );

                foreach ($variantTranslations as $languageCode => $variantName) {
                    ProductVariantTranslation::updateOrCreate(
                        ['product_variant_id' => $variant->id, 'language_code' => $languageCode], 
                        ['name' => $variantName]
                    );
                }
            }
        }
    }
}
