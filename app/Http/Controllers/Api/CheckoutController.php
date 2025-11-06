<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\CheckoutService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail as OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Storage;

class CheckoutController extends Controller
{
    use ApiResponseTrait;

    protected $checkoutService;

     public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function storeOrder(Request $request){
         try {
         
            $checkout = $this->checkoutService->store($request->all());
            return $this->successResponse($checkout, 'Order placed successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch products', 500, $e->getMessage());
        }
    }

    
  public function myOrder(Request $request)
{
    $validator = Validator::make($request->all(), [
        'mobile_number' => 'required|string|min:10|max:15',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Step 1: Find Customer
    $customer = Customer::where('phone', $request->input('mobile_number'))->first();

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Customer not found.'
        ], 404);
    }

    $pendingOrdersCount = Order::where('customer_id', $customer->id)
        ->where('status', 'pending')
        ->count();

    $deliveredOrdersCount = Order::where('customer_id', $customer->id)
        ->where('status', 'delivered')
        ->count();

    // Step 2: Fetch Order Items directly
    $orders = Order::where('customer_id', $customer->id)
        ->with(['items.product.translation', 'items.product.thumbnail'])->orderBy('created_at', 'desc')
    ->limit(5)->get();

    $orderItems = $orders->flatMap(function ($order) {
        return $order->items->map(function ($item) use ($order) {
            $product = $item->product;

            if (!$product) {
                return null;
            }

            $productName = $product->translation->name 
                ?? $product->name 
                ?? 'Product';
            
            $productImage = optional($product->thumbnail)->image_url ?? null;
            $productImage = $productImage ? Storage::url($productImage) : null;

            return [
                'order_id' => $order->id,
                'order_date' => $order->created_at?->format('M j, g:i A'),
                'order_status' => $order->status ?? null,
                'product_id' => $product->id,
                'product_name' => $productName,
                'product_image' => $productImage,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price' => $item->quantity * $item->price,
            ];
        })->filter(); // Remove null items
    })->values();

    // Step 3: Return response with proper structure
    return response()->json([
        'success' => true,
        'data' => [
            'orders' => $orderItems,
            'total_orders' => $orders->count(),
            'total_items' => $orderItems->count(),
            'pending_orders' => $pendingOrdersCount,
            'delivered_orders' => $deliveredOrdersCount,
        ]
    ], 200);
}
    
}