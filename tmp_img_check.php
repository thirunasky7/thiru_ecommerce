<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo 'product_images=' . App\Models\ProductImage::count() . PHP_EOL;
echo 'vendors_logo=' . App\Models\Vendor::whereNotNull('logo')->count() . PHP_EOL;
echo 'shops_cover=' . App\Models\Shop::whereNotNull('cover_image')->count() . PHP_EOL;
