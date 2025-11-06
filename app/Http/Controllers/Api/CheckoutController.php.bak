<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\CheckoutService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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

    
  public function myOrder(Request $request){
         try {
         
            $checkout = $this->checkoutService->myOrders($request->all());
            return $this->successResponse($checkout, 'Your Orders list!');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch products', 500, $e->getMessage());
        }
    }
    
}