@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@php $currency = activeCurrency(); @endphp

<section class="bg-orange-600 text-white py-16">
  <div class="container mx-auto flex flex-col lg:flex-row items-center px-6">
    <div class="lg:w-1/2 space-y-4">
      <span class="bg-white text-orange-600 px-3 py-1 rounded-full text-sm font-semibold">
        {{ $banner->translation ? $banner->translation->title : $banner->title }}
      </span>
      <h1 class="text-4xl md:text-5xl font-bold leading-tight">
        {{ $banner->translation ? ($banner->translation->description ?? 'Taste Your Favorite foods and snacks') : '' }}
      </h1>
      @if($banner->translation && $banner->translation->subtitle)
      <p class="text-lg text-orange-100">{{ $banner->translation->subtitle }}</p>
      @endif
      <div class="space-x-3 pt-4">
        <a href="#" class="bg-white text-orange-600 font-semibold px-5 py-2 rounded-full hover:bg-orange-100">Shop Now</a>
        <a href="#" class="border border-white px-5 py-2 rounded-full hover:bg-white hover:text-orange-600">Learn More</a>
      </div>
    </div>

    <div class="lg:w-1/2 mt-8 lg:mt-0 text-center">
      @php
        $bannerImage = $banner->translation->image_url ?? $banner->image_url ?? null;
        $bannerAlt = $banner->translation ? $banner->translation->title : $banner->title;
        $placeholderUrl = 'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($bannerAlt);
      @endphp
      <img src="{{ $bannerImage ? Storage::url($bannerImage) : $placeholderUrl }}" 
           alt="{{ $bannerAlt }}" 
           class="mx-auto rounded-xl shadow-lg max-h-[400px] object-cover"
           onerror="this.src='{{ $placeholderUrl }}'">
    </div>
  </div>
</section>

<!-- 🔸 Shop by Category -->
<section class="container mx-auto py-12 px-4">
  <h2 class="text-2xl font-semibold mb-6 text-gray-800">Shop By Category</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    @foreach($categories as $category)
      @php
        $categoryName = $category->translation->name ?? $category->name ?? 'Category';
        $categoryImage = $category->translation->image_url ?? $category->image_url ?? null;
      @endphp
      <div class="bg-white rounded-xl shadow hover:shadow-md overflow-hidden">
        <img src="{{ $categoryImage ? Storage::url($categoryImage) : 'https://via.placeholder.com/200x200/6c757d/ffffff?text=' . urlencode($categoryName) }}"
             alt="{{ $categoryName }}" class="w-full h-40 object-cover">
        <div class="p-4 text-center">
          <h5 class="font-medium text-gray-700">{{ $categoryName }}</h5>
          <a href="#" class="inline-block mt-2 text-orange-600 border border-orange-600 px-4 py-1.5 rounded-full text-sm hover:bg-orange-600 hover:text-white transition">
            Buy Now
          </a>
        </div>
      </div>
    @endforeach
  </div>
</section>

<!-- 🔸 Featured Products -->
<section class="container mx-auto py-12 px-4">
  <h2 class="text-2xl font-semibold mb-6 text-gray-800">Featured Products</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    @foreach ($products as $product)
      @php
        $productImage = optional($product->thumbnail)->image_url ?? null;
        $productName = $product->translation->name ?? $product->name ?? 'Product';
        $primaryVariant = $product->primaryVariant;
        $originalPrice = $primaryVariant->converted_price ?? 0;
        $discountPrice = $primaryVariant->converted_discount_price ?? 0;
      @endphp

      <div class="relative bg-white rounded-xl shadow hover:shadow-md overflow-hidden group">
        <img src="{{ $productImage ? Storage::url($productImage) : 'https://via.placeholder.com/400x400/ffffff/007bff?text=' . urlencode($productName) }}"
             alt="{{ $productName }}" class="w-full h-56 object-cover">
        <!-- <button class="absolute top-3 right-3 bg-white rounded-full p-2 shadow hover:bg-red-100 transition">
          <i class="far fa-heart text-red-500"></i>
        </button>
        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded-full">-20%</span> -->

        <div class="p-4">
          <a href="{{ url('/product/'.$product->slug)}}" class="block font-medium text-gray-800 truncate hover:text-orange-600">{{ Str::limit($productName, 35) }}</a>
        <div class="flex items-center">
    <div class="flex text-yellow-400 text-lg">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
    </div>
    <span class="text-gray-500 text-sm ml-2">({{ $product->reviews_count ?? 0 }})</span>
</div>

          <div class="flex justify-between items-center mt-3">
            <div>
              @if($discountPrice)
                <span class="text-gray-400 line-through mr-1">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                <span class="text-orange-600 font-semibold">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
              @else
                <span class="text-gray-800 font-semibold">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
              @endif
            </div>
            <button onclick="addToCart({{ $product->id }})" class="bg-orange-600 text-white p-2 rounded-full hover:bg-orange-700 transition">
              <i data-feather="shopping-cart"></i>
            </button>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

<!-- 🔸 Why Choose Us -->
<section class="bg-orange-600 text-white py-12">
  <div class="container mx-auto text-center px-6">
    <h1 class="text-3xl font-bold mb-8">Why Choose Us</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-orange-500 p-6 rounded-xl">
        <h3 class="text-xl font-semibold mb-2">Fast Delivery</h3>
        <p>Quick and reliable delivery services to get your products to you faster.</p>
      </div>
      <div class="bg-orange-500 p-6 rounded-xl">
        <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
        <p>Round-the-clock customer support to assist you whenever you need.</p>
      </div>
      <div class="bg-orange-500 p-6 rounded-xl">
        <h3 class="text-xl font-semibold mb-2">4.9 Ratings</h3>
        <p>Highly rated by thousands of satisfied customers worldwide.</p>
      </div>
    </div>
  </div>
</section>
@endsection
