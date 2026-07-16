<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('color', 20)->default('#0F766E');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'business_name')) {
                $table->string('business_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('vendors', 'logo')) {
                $table->string('logo')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('vendors', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('vendors', 'description')) {
                $table->text('description')->nullable()->after('cover_image');
            }
            if (!Schema::hasColumn('vendors', 'address')) {
                $table->text('address')->nullable()->after('description');
            }
            if (!Schema::hasColumn('vendors', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('vendors', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('vendors', 'pincode')) {
                $table->string('pincode', 20)->nullable()->after('state');
            }
            if (!Schema::hasColumn('vendors', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(10)->after('pincode');
            }
            if (!Schema::hasColumn('vendors', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('commission_rate');
            }
            if (!Schema::hasColumn('vendors', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('vendors', 'total_orders')) {
                $table->unsignedInteger('total_orders')->default(0)->after('rating');
            }
        });

        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'service_type_id')) {
                $table->foreignId('service_type_id')->nullable()->after('vendor_id')
                    ->constrained('service_types')->nullOnDelete();
            }
            if (!Schema::hasColumn('shops', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('vendor_id');
            }
            if (!Schema::hasColumn('shops', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('shops', 'address')) {
                $table->text('address')->nullable()->after('description');
            }
            if (!Schema::hasColumn('shops', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('shops', 'phone')) {
                $table->string('phone')->nullable()->after('city');
            }
            if (!Schema::hasColumn('shops', 'delivery_time')) {
                $table->string('delivery_time')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('shops', 'min_order_amount')) {
                $table->decimal('min_order_amount', 10, 2)->default(0)->after('delivery_time');
            }
            if (!Schema::hasColumn('shops', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0)->after('min_order_amount');
            }
            if (!Schema::hasColumn('shops', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('delivery_fee');
            }
            if (!Schema::hasColumn('shops', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('rating');
            }
            if (!Schema::hasColumn('shops', 'is_open')) {
                $table->boolean('is_open')->default(true)->after('is_featured');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'service_type_id')) {
                $table->foreignId('service_type_id')->nullable()->after('vendor_id')
                    ->constrained('service_types')->nullOnDelete();
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('slug');
            }
            if (!Schema::hasColumn('products', 'discount_price')) {
                $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('discount_price');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'service_type_id')) {
                $table->foreignId('service_type_id')->nullable()->after('id')
                    ->constrained('service_types')->nullOnDelete();
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('customer_id')
                    ->constrained('vendors')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'shop_id')) {
                $table->foreignId('shop_id')->nullable()->after('vendor_id')
                    ->constrained('shops')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'service_type_id')) {
                $table->foreignId('service_type_id')->nullable()->after('shop_id')
                    ->constrained('service_types')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'is_guest_order')) {
                $table->boolean('is_guest_order')->default(false)->after('order_notes');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'gateway_reference')) {
                $table->string('gateway_reference')->nullable()->after('payment_gateway');
            }
            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('gateway_reference');
            }
            if (!Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 10)->default('INR')->after('amount');
            }
            if (!Schema::hasColumn('payments', 'upi_id')) {
                $table->string('upi_id')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('payments', 'metadata')) {
                $table->json('metadata')->nullable()->after('upi_id');
            }
            if (!Schema::hasColumn('payments', 'failure_reason')) {
                $table->text('failure_reason')->nullable()->after('metadata');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('failure_reason');
            }
        });

        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->nullable()->index();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2)->nullable();
                $table->json('options')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');

        Schema::table('orders', function (Blueprint $table) {
            foreach (['vendor_id', 'shop_id', 'service_type_id', 'customer_email', 'payment_status', 'is_guest_order'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            foreach (['service_type_id', 'price', 'discount_price', 'stock', 'is_featured'] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'service_type_id')) {
                $table->dropColumn('service_type_id');
            }
        });

        Schema::table('shops', function (Blueprint $table) {
            foreach ([
                'service_type_id', 'seller_id', 'cover_image', 'address', 'city', 'phone',
                'delivery_time', 'min_order_amount', 'delivery_fee', 'rating', 'is_featured', 'is_open',
            ] as $col) {
                if (Schema::hasColumn('shops', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('vendors', function (Blueprint $table) {
            foreach ([
                'business_name', 'logo', 'cover_image', 'description', 'address', 'city',
                'state', 'pincode', 'commission_rate', 'is_featured', 'rating', 'total_orders',
            ] as $col) {
                if (Schema::hasColumn('vendors', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::dropIfExists('service_types');
    }
};
