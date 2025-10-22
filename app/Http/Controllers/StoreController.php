<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\App;
use App\Models\Category;
use App\Models\Product;
use App\Models\Menu;
use App\Models\brand;

class StoreController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $send_data['banner'] = Banner::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->first();

       $send_data['categories'] = Category::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();
            
        $send_data['products'] = Product::where('status', 1)
        ->with(['translation', 'thumbnail', 'primaryVariant', 'reviews'])
        ->withCount('reviews')
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();

        return view('themes.xylo.home',$send_data);
    }

    public function allcategories(){
          $categories = Category::where('status', 1)
        ->with('translation')
        ->orderBy('id', 'desc')
        ->get();
        return view('themes.xylo.categories', compact('categories'));
    }

     public function allproducts(){
        $products = Product::where('status', 1)
        ->with(['translation', 'thumbnail', 'primaryVariant', 'reviews'])
        ->withCount('reviews')
        ->orderBy('id', 'desc')
        ->get();
        return view('themes.xylo.products', compact('products',));
    }
}
