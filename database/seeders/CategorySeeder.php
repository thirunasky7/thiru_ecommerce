<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryTranslation;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'electronics',
                'translations' => [
                    ['language_code' => 'en', 'name' => 'Electronics', 'description' => 'Electronic devices', 'image_url' => 'electronics_en.jpg'],
                ]
            ],
            [
                'slug' => 'fashion',
                'translations' => [
                    ['language_code' => 'en', 'name' => 'Fashion', 'description' => 'Clothing and accessories', 'image_url' => 'fashion_en.jpg'],
                ]
            ],
            [
                'slug' => 'smartphones',
                'parent_slug' => 'electronics',
                'translations' => [
                    ['language_code' => 'en', 'name' => 'Smartphones', 'description' => 'Latest mobile phones', 'image_url' => 'smartphones_en.jpg'],
                ]
            ],
            [
                'slug' => 't-shirts',
                'parent_slug' => 'fashion',
                'translations' => [
                    ['language_code' => 'en', 'name' => 'T-Shirts', 'description' => 'Casual wear t-shirts', 'image_url' => 'tshirts_en.jpg'],
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $parentId = isset($categoryData['parent_slug']) ? Category::where('slug', $categoryData['parent_slug'])->value('id') : null;
            
            $category = Category::firstOrCreate([
                'slug' => $categoryData['slug'],
                'parent_category_id' => $parentId
            ], [
                'status' => true
            ]);
            
            foreach ($categoryData['translations'] as $translation) {
                CategoryTranslation::firstOrCreate([
                    'category_id' => $category->id,
                    'language_code' => $translation['language_code'],
                ], [
                    'name' => $translation['name'],
                    'description' => $translation['description'],
                    'image_url' => $translation['image_url']
                ]);
            }
        }
    }
}
