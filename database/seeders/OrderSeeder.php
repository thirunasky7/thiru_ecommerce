<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import the DB facade
use App\Models\Product; 

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products from the products table
        $products = Product::all(); // Make sure you have products in your database

        // Insert 1 record into the 'orders' table
        $product = $products->random(); // Randomly select a product for the order

        DB::table('orders')->insert([
            'order_date' => now(), // Static current date and time
            'status' => 'completed', // Set status as 'completed' for this order
            'total_price' => 100.00, // Static total price of 100
            'shipping_address' => '123 Example St, Sample City, Country', // Static shipping address
            'billing_address' => '456 Another St, Sample City, Country', // Static billing address
            'payment_method' => 'credit_card', // Static payment method
            'payment_status' => 'paid', // Static payment status
            'shipping_method' => 'standard', // Static shipping method
            'tracking_number' => 'TRACK12345', // Static tracking number
            'product_id' => $product->id, // Random product from the 'products' table
            'quantity' => 1, // Static quantity of 1 for this order
            'unit_price' => $product->price, // Use the product's price as unit price
            'discount_amount' => 0.00, // Static discount amount (no discount)
            'coupon_code' => null, // No coupon code
            'created_at' => now(), // Set created_at to current time
            'updated_at' => now(), // Set updated_at to current time
        ]);
    }
    }

