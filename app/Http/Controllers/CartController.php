<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        dd($cart);
        return response()->json($cart);
    }

    public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'order_for_date' => 'required|date',
        'meal_type' => 'required|in:breakfast,lunch,dinner,snack,regular'
    ]);

    $id = $request->product_id;
    $product = Product::find($id);
    $quantity = $request->quantity;
    $orderForDate = $request->order_for_date;
    $mealType = $request->meal_type;

    $cart = session()->get('cart', []);

    // Generate unique cart item ID based on product + date + meal type
    $cartItemId = $this->generateCartItemId($id, $orderForDate, $mealType);

    $productImage = product_image($product);
    $productName = product_name($product);
    $primaryVariant = $product->primaryVariant;

    $originalPrice = $primaryVariant->converted_price ?? $product->price ?? 0;
    $discountPrice = $primaryVariant->converted_discount_price ?? $product->discount_price ?? 0;

    $displayPrice = $discountPrice && $originalPrice > $discountPrice ? $discountPrice : $originalPrice;

    // Calculate expected delivery date
    $expectedDeliveryDate = $this->calculateExpectedDeliveryDate($orderForDate, $mealType);

    if(isset($cart[$cartItemId])) {
        $cart[$cartItemId]['quantity'] += $quantity;
    } else {
        $cart[$cartItemId] = [
            "cart_item_id" => $cartItemId,
            "product_id" => $id,
            "name" => $productName,
            "price" => $displayPrice,
            "image" => $productImage,
            "quantity" => $quantity,
            "order_for_date" => $orderForDate,
            "meal_type" => $mealType,
            "expected_delivery_date" => $expectedDeliveryDate,
            "display_order_date" => $this->getDisplayDate($orderForDate),
            "has_discount" => $discountPrice && $originalPrice > $discountPrice,
            "discount_percent" => $discountPrice && $originalPrice > $discountPrice ? 
                round((($originalPrice - $discountPrice) / $originalPrice) * 100) : 0,
            "original_price" => $originalPrice
        ];
    }

    session()->put('cart', $cart);
    
    // Calculate current cart count
    $cartCount = $this->getCartCount($cart);

    return response()->json([
        'status' => 'success',
        'cart_count' => $cartCount,
        'message' => 'Product added to cart successfully'
    ]);
}

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        $cartItemId = $request->cart_item_id;
        $quantity = $request->quantity;

        $cart = session()->get('cart', []);

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        // Calculate item subtotal
        $itemSubtotal = $cart[$cartItemId]['price'] * $quantity;

        // Calculate cart totals
        $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $cartCount = $this->getCartCount($cart);

        return response()->json([
            'status' => true,
            'item_subtotal' => $itemSubtotal,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
        ]);
    }

    public function removeItem($cartItemId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'status' => true,
            'cart' => $cart,
            'cart_count' => $this->getCartCount($cart)
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['status' => true]);
    }

    /**
     * Generate unique cart item ID based on product, date and meal type
     */
    private function generateCartItemId($productId, $orderForDate, $mealType)
    {
        return md5($productId . $orderForDate . $mealType);
    }

    /**
     * Calculate expected delivery date based on order date and meal type
     */
    private function calculateExpectedDeliveryDate($orderForDate, $mealType)
    {
        $orderDate = Carbon::parse($orderForDate);
        $now = Carbon::now();
        
        // For regular products, use standard delivery logic
        if ($mealType === 'regular') {
            return $this->calculateRegularDeliveryDate($now);
        }
        
        // For pre-order meals, delivery is on the ordered date
        return $orderDate->format('Y-m-d');
    }

    /**
     * Calculate delivery date for regular products
     */
    private function calculateRegularDeliveryDate($orderTime)
    {
        // If order placed after 7 PM → deliver next to next day
        if ($orderTime->hour >= 19) {
            return $orderTime->copy()->addDays(2)->format('Y-m-d');
        }

        // Otherwise deliver tomorrow
        return $orderTime->copy()->addDay()->format('Y-m-d');
    }

    /**
     * Get display date format (Today, Tomorrow, or formatted date)
     */
    private function getDisplayDate($date)
    {
        $carbonDate = Carbon::parse($date);
        $today = Carbon::today();
        
        if ($carbonDate->isToday()) {
            return 'Today';
        } elseif ($carbonDate->isTomorrow()) {
            return 'Tomorrow';
        } else {
            return $carbonDate->format('D, M j');
        }
    }

    /**
     * Get total count of items in cart
     */
    private function getCartCount($cart)
    {
        return collect($cart)->sum('quantity');
    }
}