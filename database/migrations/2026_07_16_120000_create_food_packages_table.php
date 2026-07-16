<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('includes')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->unsignedInteger('duration_days')->default(30);
            $table->unsignedTinyInteger('meals_per_day')->default(1);
            $table->json('meal_types')->nullable();
            $table->json('sample_menu')->nullable();
            $table->string('image')->nullable();
            $table->string('badge')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('max_subscribers')->nullable();
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->timestamps();
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'food_package_id')) {
                $table->foreignId('food_package_id')->nullable()->after('product_id')
                    ->constrained('food_packages')->nullOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'package_start_date')) {
                $table->date('package_start_date')->nullable()->after('order_for_date');
            }
            if (!Schema::hasColumn('order_items', 'package_end_date')) {
                $table->date('package_end_date')->nullable()->after('package_start_date');
            }
            if (!Schema::hasColumn('order_items', 'item_type')) {
                $table->string('item_type')->default('product')->after('id');
            }
        });

        // Allow package-only line items without a product
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NULL');

        // Expand meal_type to include package subscriptions
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_items MODIFY meal_type ENUM('breakfast','lunch','dinner','snack','regular','package') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            foreach (['food_package_id', 'package_start_date', 'package_end_date', 'item_type'] as $col) {
                if (Schema::hasColumn('order_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::dropIfExists('food_packages');
    }
};
