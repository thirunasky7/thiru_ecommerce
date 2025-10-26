<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\HomeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponseTrait;

    protected $homeService;

     public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }
    public function categoryProducts(){
         try {
            $categories = $this->homeService->categoryProducts();
            return $this->successResponse($categories, 'Category Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch products', 500, $e->getMessage());
        }
    }

}