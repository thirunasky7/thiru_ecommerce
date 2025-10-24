@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@php $currency = activeCurrency(); @endphp

<!-- Mobile Header Banner -->
<section class="bg-green-600 text-white py-8 md:py-16">
    <div class="container mx-auto flex flex-col lg:flex-row items-center px-4 md:px-6">
        <div class="lg:w-1/2 space-y-3 md:space-y-4 text-center lg:text-left">
            <span class="bg-white text-orange-600 px-3 py-1 rounded-full text-xs md:text-sm font-semibold inline-block">
                {{ $banner->translation ? $banner->translation->title : $banner->title }}
            </span>
            <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold leading-tight">
                {{ $banner->translation ? ($banner->translation->description ?? 'Taste Your Favorite foods and snacks') : '' }}
            </h1>
            @if($banner->translation && $banner->translation->subtitle)
            <p class="text-sm md:text-lg text-orange-100">{{ $banner->translation->subtitle }}</p>
            @endif
            <div class="space-x-2 md:space-x-3 pt-3 md:pt-4 flex flex-col sm:flex-row gap-2 justify-center lg:justify-start">
                <a href="#" class="bg-red-600 text-white-600 font-semibold px-4 py-2 md:px-5 md:py-2 rounded-full hover:bg-orange-100 text-sm md:text-base text-center">
                    Shop Now
                </a>
            </div>
        </div>

        <div class="lg:w-1/2 mt-6 lg:mt-0 text-center">
            @php
                $bannerImage = $banner->translation->image_url ?? $banner->image_url ?? null;
                $bannerAlt = $banner->translation ? $banner->translation->title : $banner->title;
                $placeholderUrl = 'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($bannerAlt);
            @endphp
            <img src="{{ $bannerImage ? Storage::url($bannerImage) : $placeholderUrl }}" 
                 alt="{{ $bannerAlt }}" 
                 class="mx-auto rounded-xl shadow-lg max-h-[300px] md:max-h-[400px] w-full object-cover"
                 onerror="this.src='{{ $placeholderUrl }}'">
        </div>
    </div>
</section>

<!-- 🔸 Shop by Category -->
<section class="container mx-auto py-8 md:py-12 px-4">
    <h2 class="text-xl md:text-2xl font-semibold mb-4 md:mb-6 text-gray-800">Shop By Category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
        @foreach($categories as $category)
            @php
                $categoryName = $category->translation->name ?? $category->name ?? 'Category';
                $categoryImage = $category->translation->image_url ?? $category->image_url ?? null;
            @endphp
            <div class="bg-white rounded-lg md:rounded-xl shadow hover:shadow-md overflow-hidden transition-all duration-300">
                <img src="{{ $categoryImage ? Storage::url($categoryImage) : 'https://via.placeholder.com/200x200/6c757d/ffffff?text=' . urlencode($categoryName) }}"
                     alt="{{ $categoryName }}" class="w-full h-28 md:h-40 object-cover">
                <div class="p-3 md:p-4 text-center">
                    <h5 class="font-medium text-gray-700 text-sm md:text-base">{{ Str::limit($categoryName, 20) }}</h5>
                    <a href="#" class="inline-block mt-1 md:mt-2 text-white  bg-red-600 px-3 py-1 md:px-4 md:py-1.5 rounded-full text-xs md:text-sm hover:bg-red-600 hover:text-white transition">
                        Shop Now
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- 🔸 Featured Products Carousel -->
<section class="container mx-auto py-8 md:py-12 px-4">
    <div class="flex justify-between items-center mb-4 md:mb-6">
        <h2 class="text-xl md:text-2xl font-semibold text-gray-800">Featured Products</h2>
        <a href="{{ url('/products')}}" class="text-red-600 hover:text-red-700 text-sm md:text-base font-medium">
            View All →
        </a>
    </div>
    
    <!-- Mobile Carousel -->
    <div class="products-carousel md:hidden">
        @foreach ($products as $product)
            @php
                $productImage = optional($product->thumbnail)->image_url ?? null;
                $productName = $product->translation->name ?? $product->name ?? 'Product';
                $primaryVariant = $product->primaryVariant;
                $originalPrice = $primaryVariant->converted_price ?? 0;
                $discountPrice = $primaryVariant->converted_discount_price ?? 0;
                $averageRating = round($product->reviews_avg_rating ?? 4.5, 1);
                $reviewCount = $product->reviews_count ?? 0;
            @endphp

            <div class="px-2 mt-2">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg overflow-hidden mobile-product-card">
                    <!-- Product Image -->
                    <div class="relative overflow-hidden">
                        <img src="{{ $productImage ? Storage::url($productImage) : 'https://via.placeholder.com/300x300?text=' . urlencode($productName) }}"
                             alt="{{ $productName }}" 
                             class="w-full mobile-product-image object-cover">
                        
                        <!-- Wishlist Button -->
                        <button class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 shadow-md hover:shadow-lg transition-all duration-200 z-10"
                                onclick="showWishlistMessage()">
                            <i class="fa-regular fa-heart text-xs"></i>
                        </button>

                        <!-- Discount Badge -->
                        @if($discountPrice && $originalPrice > $discountPrice)
                            @php
                                $discountPercent = round((($originalPrice - $discountPrice) / $originalPrice) * 100);
                            @endphp
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                -{{ $discountPercent }}%
                            </span>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="mobile-product-actions p-3">
                        <!-- Product Name -->
                        <a href="{{ url('/product/'.$product->slug)}}" 
                           class="block font-medium text-gray-800 hover:text-orange-600 transition-colors duration-200 mb-2 line-clamp-2 text-sm">
                            {{ Str::limit($productName, 50) }}
                        </a>

                        <!-- Star Ratings -->
                        <div class="flex items-center mb-2">
                            <div class="flex space-x-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($averageRating))
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @elseif ($i - 0.5 == $averageRating)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-yellow-400 text-xs"></i>
                                    @endif
                                @endfor
                            </div> <!-- $reviewCount-->
                            <span class="text-gray-500 text-xs ml-1">(50)</span>
                        </div>

                        <!-- Price and Add to Cart -->
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                @if($discountPrice && $originalPrice > $discountPrice)
                                    <span class="text-gray-400 line-through text-xs">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                                    <span class="text-orange-600 font-semibold text-base">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
                                @else
                                    <span class="text-gray-800 font-semibold text-base">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                                @endif
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <button onclick="addToCart({{ $product->id }})" 
                                    class="mobile-add-cart-btn bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center space-x-1 font-medium">
                                <i class="fas fa-shopping-cart text-xs"></i>
                                <span class="text-xs">Add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Desktop Grid -->
    <div class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            @php
                $productImage = optional($product->thumbnail)->image_url ?? null;
                $productName = $product->translation->name ?? $product->name ?? 'Product';
                $primaryVariant = $product->primaryVariant;
                $originalPrice = $primaryVariant->converted_price ?? 0;
                $discountPrice = $primaryVariant->converted_discount_price ?? 0;
                $averageRating = round($product->reviews_avg_rating ?? 4.5, 1);
                $reviewCount = $product->reviews_count ?? 0;
            @endphp

            <div class="relative bg-white rounded-xl shadow-md hover:shadow-lg overflow-hidden group transition-all duration-300">
                <!-- Product Image -->
                <div class="relative overflow-hidden">
                    <img src="{{ $productImage ? Storage::url($productImage) : '' . urlencode($productName) }}"
                         alt="{{ $productName }}" 
                         class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                    <!-- Wishlist Button -->
                    <button class="wishlist-btn absolute top-3 right-3 w-10 h-10 bg-white-600 bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 shadow-md hover:shadow-lg transition-all duration-200 z-10"
                            onclick="showWishlistMessage()">
                        <i class="fa-regular fa-heart"></i>
                    </button>

                    <!-- Discount Badge -->
                    @if($discountPrice && $originalPrice > $discountPrice)
                        @php
                            $discountPercent = round((($originalPrice - $discountPrice) / $originalPrice) * 100);
                        @endphp
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded-full">
                            -{{ $discountPercent }}%
                        </span>
                    @endif

                    <!-- Quick Add to Cart Overlay -->
                    <!-- <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($productName) }}')" 
                                class="bg-white text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-orange-500 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg text-sm">
                            Quick Add
                        </button>
                    </div> -->
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <!-- Product Name -->
                    <a href="{{ url('/product/'.$product->slug)}}" 
                       class="block font-medium text-gray-800 hover:text-orange-600 transition-colors duration-200 mb-2 line-clamp-2 h-12">
                        {{ $productName }}
                    </a>

                    <!-- Star Ratings -->
                    <div class="flex items-center mb-3">
                        <div class="flex space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                @elseif ($i - 0.5 == $averageRating)
                                    <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                @else
                                    <i class="far fa-star text-yellow-400 text-sm"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-500 text-xs ml-2">({{ $reviewCount }})</span>
                    </div>

                    <!-- Price and Add to Cart -->
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex flex-col">
                            @if($discountPrice && $originalPrice > $discountPrice)
                                <span class="text-gray-400 line-through text-sm">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                                <span class="text-red-600 font-semibold text-lg">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
                            @else
                                <span class="text-gray-800 font-semibold text-lg">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button onclick="addToCart({{ $product->id }})" 
                                class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center space-x-2 font-medium text-sm">
                            <i class="fas fa-shopping-cart text-xs"></i>
                            <span>Add</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- 🔸 Why Choose Us -->
<section class="bg-green-600 text-white py-8 md:py-12">
    <div class="container mx-auto text-center px-4 md:px-6">
        <h1 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8">Why Choose Us</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="bg-white text-black p-4 md:p-6 rounded-xl">
                <i class="fas fa-shipping-fast text-2xl md:text-3xl mb-2 md:mb-3"></i>
                <h3 class="text-lg md:text-xl font-semibold mb-2">Fast Delivery</h3>
                <p class="text-sm md:text-base">Quick and reliable delivery services to get your products to you faster.</p>
            </div>
            <div class="bg-white text-black p-4 md:p-6 rounded-xl">
                <i class="fas fa-headset text-2xl md:text-3xl mb-2 md:mb-3"></i>
                <h3 class="text-lg md:text-xl font-semibold mb-2">24/7 Support</h3>
                <p class="text-sm md:text-base">Round-the-clock customer support to assist you whenever you need.</p>
            </div>
            <div class="bg-white text-black p-4 md:p-6 rounded-xl">
                <i class="fas fa-star text-2xl md:text-3xl mb-2 md:mb-3"></i>
                <h3 class="text-lg md:text-xl font-semibold mb-2">4.9 Ratings</h3>
                <p class="text-sm md:text-base">Highly rated by thousands of satisfied customers worldwide.</p>
            </div>
        </div>
    </div>
</section>


<script>
// Toastr configuration
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "1000",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Initialize product carousel for mobile
$(document).ready(function(){
    $('.products-carousel').slick({
        dots: true,
        arrows: true,
        infinite: true,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});

// Wishlist message
function showWishlistMessage() {
    showSuccess('Wishlist feature coming soon!', 'Feature Alert');
}

// Add to Cart functionality



</script>
@endsection