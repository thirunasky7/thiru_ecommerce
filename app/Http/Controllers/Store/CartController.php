<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $id = $request->product_id;
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found']);
        }

        $cart = session()->get('cart', []);

        $productImage = $product->image_url ?? null;
        $productName = $product->translation->name ?? $product->name;
        $primaryVariant = $product->primaryVariant;

        $originalPrice = $primaryVariant->converted_price ?? $product->price ?? 0;
        $discountPrice = $primaryVariant->converted_discount_price ?? $product->discount_price ?? 0;

        $displayPrice = $discountPrice && $originalPrice > $discountPrice
                        ? $discountPrice
                        : $originalPrice;

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            $cart[$id] = [
                "id" => $id,
                "name" => $productName,
                "price" => $displayPrice,
                "image" => $productImage,
                "quantity" => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => true,
            'cart_count' => collect($cart)->sum('quantity'),
        ]);
    }


    public function updateQuantity(Request $request)
    {
        $id = $request->product_id;
        $type = $request->action; // increase / decrease

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(['status' => false]);
        }

        if ($type === 'increase') {
            $cart[$id]['quantity'] += 1;
        } elseif ($type === 'decrease') {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] -= 1;
            }
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => true,
            'quantity' => $cart[$id]['quantity'],
            'cart_total' => $this->cartTotal($cart),
            'cart_count' => collect($cart)->sum('quantity')
        ]);
    }


    public function removeItem(Request $request)
    {
        $id = $request->product_id;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'status' => true,
            'cart_total' => $this->cartTotal($cart),
            'cart_count' => collect($cart)->sum('quantity')
        ]);
    }


    private function cartTotal($cart)
    {
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
