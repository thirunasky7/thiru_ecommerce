<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected $productService;

    // Constructor Dependency Injection
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // GET /api/products
    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            return $this->successResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch products', 500, $e->getMessage());
        }
    }
}
