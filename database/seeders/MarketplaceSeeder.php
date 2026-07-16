<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Shop;
use App\Models\ServiceType;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Banner;
use App\Models\BannerTranslation;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@thaiyur.com'],
            [
                'name' => 'ThaiYur Admin',
                'password' => 'password',
            ]
        );

        // Currencies
        Currency::updateOrCreate(['code' => 'INR'], [
            'name' => 'Indian Rupee',
            'symbol' => '₹',
            'exchange_rate' => 1,
        ]);
        Currency::updateOrCreate(['code' => 'USD'], [
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 0.012,
        ]);

        // Service types
        $food = ServiceType::updateOrCreate(['slug' => 'food'], [
            'name' => 'Food Delivery',
            'icon' => 'fa-utensils',
            'description' => 'Subscribe to monthly meal packages from local kitchens with door delivery.',
            'color' => '#C45C26',
            'sort_order' => 1,
            'status' => true,
        ]);

        $grocery = ServiceType::updateOrCreate(['slug' => 'grocery'], [
            'name' => 'Grocery',
            'icon' => 'fa-basket-shopping',
            'description' => 'Everyday essentials, fresh produce, dairy, and pantry staples delivered to your door.',
            'color' => '#2D6A4F',
            'sort_order' => 2,
            'status' => true,
        ]);

        $products = ServiceType::updateOrCreate(['slug' => 'products'], [
            'name' => 'Marketplace',
            'icon' => 'fa-bag-shopping',
            'description' => 'Shop lifestyle products, home goods, and more from trusted local vendors.',
            'color' => '#1B4F72',
            'sort_order' => 3,
            'status' => true,
        ]);

        // Vendors
        $vendors = [
            [
                'name' => 'Somsak Kitchen',
                'email' => 'food@thaiyur.com',
                'business_name' => 'Somsak Thai Kitchen',
                'phone' => '9876500001',
                'city' => 'Chennai',
                'description' => 'Authentic Thai & Indian fusion meals with daily pre-order menus.',
                'is_featured' => true,
                'rating' => 4.8,
            ],
            [
                'name' => 'FreshBasket Mart',
                'email' => 'grocery@thaiyur.com',
                'business_name' => 'FreshBasket Grocery',
                'phone' => '9876500002',
                'city' => 'Chennai',
                'description' => 'Farm-fresh vegetables, fruits, dairy and household groceries.',
                'is_featured' => true,
                'rating' => 4.6,
            ],
            [
                'name' => 'Yur Lifestyle Store',
                'email' => 'shop@thaiyur.com',
                'business_name' => 'Yur Lifestyle',
                'phone' => '9876500003',
                'city' => 'Chennai',
                'description' => 'Curated home, fashion and everyday products.',
                'is_featured' => true,
                'rating' => 4.5,
            ],
        ];

        $vendorModels = [];
        foreach ($vendors as $v) {
            $vendorModels[] = Vendor::updateOrCreate(
                ['email' => $v['email']],
                array_merge($v, [
                    'password' => 'password',
                    'status' => 'active',
                    'address' => 'ThaiYur Hub, Main Road',
                    'state' => 'Tamil Nadu',
                    'pincode' => '600001',
                    'commission_rate' => 10,
                ])
            );
        }

        // Shops
        $shopFood = Shop::updateOrCreate(
            ['slug' => 'somsak-thai-kitchen'],
            [
                'vendor_id' => $vendorModels[0]->id,
                'seller_id' => $vendorModels[0]->id,
                'service_type_id' => $food->id,
                'name' => 'Somsak Thai Kitchen',
                'description' => 'Hot meals, weekly menus & door delivery.',
                'city' => 'Chennai',
                'phone' => '9876500001',
                'delivery_time' => '30-45 min',
                'min_order_amount' => 99,
                'delivery_fee' => 20,
                'rating' => 4.8,
                'is_featured' => true,
                'is_open' => true,
                'status' => 'active',
            ]
        );

        $shopGrocery = Shop::updateOrCreate(
            ['slug' => 'freshbasket-grocery'],
            [
                'vendor_id' => $vendorModels[1]->id,
                'seller_id' => $vendorModels[1]->id,
                'service_type_id' => $grocery->id,
                'name' => 'FreshBasket Grocery',
                'description' => 'Fresh produce & daily essentials.',
                'city' => 'Chennai',
                'phone' => '9876500002',
                'delivery_time' => '45-60 min',
                'min_order_amount' => 149,
                'delivery_fee' => 25,
                'rating' => 4.6,
                'is_featured' => true,
                'is_open' => true,
                'status' => 'active',
            ]
        );

        $shopProducts = Shop::updateOrCreate(
            ['slug' => 'yur-lifestyle'],
            [
                'vendor_id' => $vendorModels[2]->id,
                'seller_id' => $vendorModels[2]->id,
                'service_type_id' => $products->id,
                'name' => 'Yur Lifestyle',
                'description' => 'Products for home and everyday living.',
                'city' => 'Chennai',
                'phone' => '9876500003',
                'delivery_time' => '1-2 days',
                'min_order_amount' => 199,
                'delivery_fee' => 40,
                'rating' => 4.5,
                'is_featured' => true,
                'is_open' => true,
                'status' => 'active',
            ]
        );

        // Categories
        $categoryMap = $this->seedCategories([
            ['slug' => 'thai-meals', 'name' => 'Thai Meals', 'service' => $food, 'desc' => 'Authentic Thai dishes'],
            ['slug' => 'indian-meals', 'name' => 'Indian Meals', 'service' => $food, 'desc' => 'Homestyle Indian food'],
            ['slug' => 'snacks-beverages', 'name' => 'Snacks & Beverages', 'service' => $food, 'desc' => 'Light bites and drinks'],
            ['slug' => 'fresh-produce', 'name' => 'Fresh Produce', 'service' => $grocery, 'desc' => 'Fruits and vegetables'],
            ['slug' => 'dairy-bakery', 'name' => 'Dairy & Bakery', 'service' => $grocery, 'desc' => 'Milk, bread and more'],
            ['slug' => 'pantry', 'name' => 'Pantry Staples', 'service' => $grocery, 'desc' => 'Rice, oil, spices'],
            ['slug' => 'home-living', 'name' => 'Home & Living', 'service' => $products, 'desc' => 'Home essentials'],
            ['slug' => 'personal-care', 'name' => 'Personal Care', 'service' => $products, 'desc' => 'Health and beauty'],
        ]);

        // Sample products
        $sampleProducts = [
            // Food
            [
                'slug' => 'pad-thai-noodles',
                'name' => 'Pad Thai Noodles',
                'desc' => 'Classic stir-fried rice noodles with tamarind sauce.',
                'price' => 189,
                'shop' => $shopFood,
                'vendor' => $vendorModels[0],
                'service' => $food,
                'category' => 'thai-meals',
                'is_food_menu' => 'yes',
                'product_mode' => 'preorder',
                'product_type' => 'food',
            ],
            [
                'slug' => 'green-curry-chicken',
                'name' => 'Green Curry Chicken',
                'desc' => 'Creamy coconut green curry with jasmine rice.',
                'price' => 249,
                'shop' => $shopFood,
                'vendor' => $vendorModels[0],
                'service' => $food,
                'category' => 'thai-meals',
                'is_food_menu' => 'yes',
                'product_mode' => 'preorder',
                'product_type' => 'food',
            ],
            [
                'slug' => 'butter-chicken-meal',
                'name' => 'Butter Chicken Meal',
                'desc' => 'Rich butter chicken with soft naan.',
                'price' => 229,
                'shop' => $shopFood,
                'vendor' => $vendorModels[0],
                'service' => $food,
                'category' => 'indian-meals',
                'is_food_menu' => 'yes',
                'product_mode' => 'regular',
                'product_type' => 'food',
            ],
            [
                'slug' => 'mango-lassi',
                'name' => 'Mango Lassi',
                'desc' => 'Chilled mango yogurt drink.',
                'price' => 79,
                'shop' => $shopFood,
                'vendor' => $vendorModels[0],
                'service' => $food,
                'category' => 'snacks-beverages',
                'is_food_menu' => 'yes',
                'product_mode' => 'regular',
                'product_type' => 'food',
            ],
            // Grocery
            [
                'slug' => 'organic-tomatoes-1kg',
                'name' => 'Organic Tomatoes 1kg',
                'desc' => 'Farm-fresh organic tomatoes.',
                'price' => 60,
                'shop' => $shopGrocery,
                'vendor' => $vendorModels[1],
                'service' => $grocery,
                'category' => 'fresh-produce',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'grocery',
            ],
            [
                'slug' => 'bananas-dozen',
                'name' => 'Bananas (Dozen)',
                'desc' => 'Ripe and ready-to-eat bananas.',
                'price' => 45,
                'shop' => $shopGrocery,
                'vendor' => $vendorModels[1],
                'service' => $grocery,
                'category' => 'fresh-produce',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'grocery',
            ],
            [
                'slug' => 'fresh-milk-1l',
                'name' => 'Fresh Milk 1L',
                'desc' => 'Pasteurized full cream milk.',
                'price' => 58,
                'shop' => $shopGrocery,
                'vendor' => $vendorModels[1],
                'service' => $grocery,
                'category' => 'dairy-bakery',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'grocery',
            ],
            [
                'slug' => 'basmati-rice-5kg',
                'name' => 'Basmati Rice 5kg',
                'desc' => 'Premium long-grain basmati rice.',
                'price' => 520,
                'discount_price' => 479,
                'shop' => $shopGrocery,
                'vendor' => $vendorModels[1],
                'service' => $grocery,
                'category' => 'pantry',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'grocery',
            ],
            // Marketplace products
            [
                'slug' => 'bamboo-storage-box',
                'name' => 'Bamboo Storage Box',
                'desc' => 'Eco-friendly multipurpose storage.',
                'price' => 899,
                'discount_price' => 749,
                'shop' => $shopProducts,
                'vendor' => $vendorModels[2],
                'service' => $products,
                'category' => 'home-living',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'product',
            ],
            [
                'slug' => 'herbal-face-wash',
                'name' => 'Herbal Face Wash',
                'desc' => 'Gentle cleanser with natural extracts.',
                'price' => 299,
                'shop' => $shopProducts,
                'vendor' => $vendorModels[2],
                'service' => $products,
                'category' => 'personal-care',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'product',
            ],
            [
                'slug' => 'cotton-kitchen-towel-set',
                'name' => 'Cotton Kitchen Towel Set',
                'desc' => 'Pack of 3 absorbent cotton towels.',
                'price' => 399,
                'shop' => $shopProducts,
                'vendor' => $vendorModels[2],
                'service' => $products,
                'category' => 'home-living',
                'is_food_menu' => 'no',
                'product_mode' => 'regular',
                'product_type' => 'product',
            ],
        ];

        foreach ($sampleProducts as $p) {
            $product = Product::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'shop_id' => $p['shop']->id,
                    'vendor_id' => $p['vendor']->id,
                    'service_type_id' => $p['service']->id,
                    'category_id' => $categoryMap[$p['category']]->id,
                    'product_type' => $p['product_type'],
                    'price' => $p['price'],
                    'discount_price' => $p['discount_price'] ?? null,
                    'stock' => 100,
                    'status' => 1,
                    'is_featured' => true,
                    'is_food_menu' => $p['is_food_menu'],
                    'product_mode' => $p['product_mode'],
                ]
            );

            ProductTranslation::updateOrCreate(
                ['product_id' => $product->id, 'language_code' => 'en'],
                [
                    'name' => $p['name'],
                    'description' => $p['desc'],
                ]
            );

            ProductVariant::updateOrCreate(
                ['variant_slug' => $p['slug'] . '-default'],
                [
                    'product_id' => $product->id,
                    'price' => $p['discount_price'] ?? $p['price'],
                    'discount_price' => $p['discount_price'] ?? null,
                    'stock' => 100,
                    'SKU' => strtoupper(Str::slug($p['slug'], '-')),
                    'is_primary' => true,
                ]
            );
        }

        // Customer demo
        Customer::updateOrCreate(
            ['email' => 'customer@thaiyur.com'],
            [
                'name' => 'Demo Customer',
                'phone' => '9876543210',
                'password' => 'password',
                'address' => '12, Lake View Apartments, Chennai',
                'status' => 'active',
            ]
        );

        // Coupons
        Coupon::updateOrCreate(['code' => 'WELCOME50'], [
            'discount' => 50,
            'type' => 'fixed',
            'expires_at' => now()->addMonths(3),
        ]);
        Coupon::updateOrCreate(['code' => 'SAVE10'], [
            'discount' => 10,
            'type' => 'percentage',
            'expires_at' => now()->addMonths(2),
        ]);

        $banner = Banner::updateOrCreate(
            ['title' => 'ThaiYur Marketplace Hero'],
            [
                'type' => 'featured',
                'status' => 1,
            ]
        );

        BannerTranslation::updateOrCreate(
            ['banner_id' => $banner->id, 'language_code' => 'en'],
            [
                'title' => 'Food, Grocery & More',
                'description' => 'One marketplace. Multiple vendors. Premium delivery.',
                'image_url' => 'banners/marketplace-hero.jpg',
            ]
        );

        // Site settings refresh
        DB::table('site_settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_name' => 'ThaiYur',
                'tagline' => 'Food · Grocery · Marketplace',
                'meta_title' => 'ThaiYur — Multivendor Food, Grocery & Shopping',
                'meta_description' => 'Order monthly food packages, groceries and products from local vendors on ThaiYur.',
                'meta_keywords' => 'food delivery, grocery, marketplace, multivendor, thaiyur, monthly package',
                'contact_email' => 'hello@thaiyur.com',
                'contact_phone' => '+91 98765 43210',
                'address' => 'ThaiYur Hub, Chennai, India',
                'footer_text' => '© ' . date('Y') . ' ThaiYur. All rights reserved.',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Monthly food packages
        \App\Models\FoodPackage::updateOrCreate(
            ['slug' => 'lunch-monthly-plan'],
            [
                'vendor_id' => $vendorModels[0]->id,
                'shop_id' => $shopFood->id,
                'name' => 'Lunch Monthly Plan',
                'description' => '30 days of fresh lunch delivery from Somsak Kitchen. Perfect for busy weekdays.',
                'includes' => 'Daily lunch box, spice customization, door delivery Mon–Sat, weekly menu rotation.',
                'price' => 4499,
                'compare_price' => 5400,
                'duration_days' => 30,
                'meals_per_day' => 1,
                'meal_types' => ['lunch'],
                'sample_menu' => [
                    'Week 1: Pad Thai, Green Curry, Butter Chicken',
                    'Week 2: Basil Chicken, Veg Biryani, Thai Soup',
                    'Week 3: Paneer Bowl, Fried Rice, Red Curry',
                    'Week 4: Chef specials rotation',
                ],
                'badge' => 'Most popular',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 1,
            ]
        );

        \App\Models\FoodPackage::updateOrCreate(
            ['slug' => 'full-day-meal-plan'],
            [
                'vendor_id' => $vendorModels[0]->id,
                'shop_id' => $shopFood->id,
                'name' => 'Full Day Meal Plan',
                'description' => 'Breakfast + lunch + dinner for a complete monthly food package.',
                'includes' => '3 meals/day, balanced portions, delivery window selection, pause up to 3 days.',
                'price' => 9999,
                'compare_price' => 12000,
                'duration_days' => 30,
                'meals_per_day' => 3,
                'meal_types' => ['breakfast', 'lunch', 'dinner'],
                'sample_menu' => [
                    'Breakfast: Idli / Toast bowls / Smoothies',
                    'Lunch: Thai & Indian mains with sides',
                    'Dinner: Lighter curries and rice bowls',
                ],
                'badge' => 'Best value',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 2,
            ]
        );

        \App\Models\FoodPackage::updateOrCreate(
            ['slug' => 'weekend-family-feast'],
            [
                'vendor_id' => $vendorModels[0]->id,
                'shop_id' => $shopFood->id,
                'name' => 'Weekend Family Feast',
                'description' => '4 weekends of family-size dinner packages delivered to your door.',
                'includes' => '4 feast boxes (serves 4), dessert add-on once a month.',
                'price' => 5999,
                'compare_price' => 7200,
                'duration_days' => 30,
                'meals_per_day' => 1,
                'meal_types' => ['dinner'],
                'sample_menu' => [
                    'Weekend 1: Thai banquet tray',
                    'Weekend 2: South Indian feast',
                    'Weekend 3: Grill night special',
                    'Weekend 4: Chef choice celebration box',
                ],
                'badge' => 'Family',
                'is_featured' => true,
                'status' => true,
                'sort_order' => 3,
            ]
        );
    }

    private function seedCategories(array $items): array
    {
        $map = [];
        foreach ($items as $item) {
            $category = Category::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'parent_category_id' => null,
                    'service_type_id' => $item['service']->id,
                    'status' => true,
                ]
            );

            CategoryTranslation::updateOrCreate(
                ['category_id' => $category->id, 'language_code' => 'en'],
                [
                    'name' => $item['name'],
                    'description' => $item['desc'],
                    'image_url' => $item['slug'] . '.jpg',
                ]
            );

            $map[$item['slug']] = $category;
        }

        return $map;
    }
}
