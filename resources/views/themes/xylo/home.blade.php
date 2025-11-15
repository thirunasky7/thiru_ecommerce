@extends('themes.xylo.partials.app')

@section('title', 'Thaiyur Shop - Online Shopping')
<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;     /* Firefox */
}
.slide {
            display: none;
            opacity: 0;
          
        }
        .active-slide {
            display: block;
            opacity: 1;
        }
        
        /* Food menu availability styles */
        .product-unavailable {
            position: relative;
            
            opacity: 1.5;
            pointer-events: none;
        }
        
        .unavailable-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 10;
            text-align: center;
            font-size: 14px;
        }
        
        .product-card-container {
            position: relative;
        }
</style>
@section('content')
@php $currency = activeCurrency(); @endphp

<!-- Mobile Header Banner -->
<section class="bg-white-600 text-white py-8 md:py-16">
    <div class="relative w-full " id="bannerCarousel">
        @foreach ($banners as $index => $banner)
            <div class="slide {{ $index === 0 ? 'active-slide' : '' }}">
                <img src="{{ asset('public'.$banner['image_url']) }}" alt="{{ $banner['name'] }}" class="w-full h-64 sm:h-96 object-cover">
                <div class="absolute inset-0  flex flex-col justify-center items-center text-center text-white">
                    <!-- <h2 class="text-3xl sm:text-5xl font-bold mb-3">{{ $banner['name']??'' }}</h2> -->
                </div>
            </div>
        @endforeach

        <!-- Navigation Arrows -->
        <button onclick="moveSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-3 rounded-full hover:bg-black transition">‹</button>
        <button onclick="moveSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-3 rounded-full hover:bg-black transition">›</button>
    </div>
</section>

<!-- 🔸 Shop by Category -->
<!-- <section class="container mx-auto py-8 md:py-12 px-4">
    <h2 class="text-xl md:text-2xl font-semibold mb-4 md:mb-6 text-gray-800">Shop By Category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 md:gap-6">
        @foreach($categories as $category)
            @php
                $categoryName = $category->translation->name ?? $category->name ?? 'Category';
                $categoryImage = $category->translation->image_url ?? $category->image_url ?? null;
            @endphp
            <div class="bg-white rounded-lg md:rounded-xl shadow hover:shadow-md overflow-hidden transition-all duration-300">
                <img src="{{ $categoryImage ?  asset('/public/storage/'.$categoryImage) : 'https://via.placeholder.com/200x200/6c757d/ffffff?text=' . urlencode($categoryName) }}"
                     alt="{{ $categoryName }}" class="w-full h-28 md:h-40 object-cover">
                <div class="p-3 md:p-4 text-center">
                    <h5 class="font-medium text-gray-700 text-sm md:text-base">{{ Str::limit($categoryName, 20) }}</h5>
                    <a href="{{ url('/category/products/' .$category['slug']) }}" class="inline-block mt-1 md:mt-2 text-white  bg-red-600 px-3 py-1 md:px-4 md:py-1.5 rounded-full text-xs md:text-sm hover:bg-red-600 hover:text-white transition">
                        Shop Now
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section> -->

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
                
                // Check food menu availability
              $isFoodMenu = $product->is_food_menu === 'yes';
            $isAvailable = true;
            $availabilityMessage = '';
                $isAvailable = is_food_menu_available($product);
        $availabilityMessage = get_food_menu_availability_message($product);

            @endphp

            <div class="px-2 mt-2 product-card-container">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg overflow-hidden mobile-product-card {{ !$isAvailable ? 'product-unavailable' : '' }}">
                    @if(!$isAvailable)
                        <div class="unavailable-overlay">
                            {{ $availabilityMessage }}
                        </div>
                    @endif
                   
                    <!-- Product Image -->
                    <div class="relative overflow-hidden">
                        <img src="{{ $productImage ? asset('/public/storage/'.$productImage) : 'https://via.placeholder.com/300x300?text=' . urlencode($productName) }}"
                             alt="{{ $productName }}" 
                             class="w-full mobile-product-image object-cover">
                        
                        <!-- Wishlist Button -->
                        <button class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 shadow-md hover:shadow-lg transition-all duration-200 z-10"
                                onclick="showWishlistMessage()">
                            <i class="fa-regular fa-heart text-xs"></i>
                        </button>

                        <!-- Discount Badge -->
                        @if($discountPrice && $originalPrice > $discountPrice && $isAvailable)
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
                        <a href="{{ $isAvailable ? url('/product/'.$product->slug) : 'javascript:void(0)' }}" 
                           class="block font-medium text-gray-800 hover:text-orange-600 transition-colors duration-200 mb-2 line-clamp-2 text-sm {{ !$isAvailable ? 'pointer-events-none' : '' }}">
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
                                @if($discountPrice && $originalPrice > $discountPrice && $isAvailable)
                                    <span class="text-gray-400 line-through text-xs">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                                    <span class="text-orange-600 font-semibold text-base">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
                                @else
                                    <span class="text-gray-800 font-semibold text-base">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                                @endif
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <button onclick="{{ $isAvailable ? 'addToCart(' . $product->id . ')' : 'showUnavailableMessage()' }}" 
                                    class="mobile-add-cart-btn bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center space-x-1 font-medium {{ !$isAvailable ? 'opacity-50 cursor-not-allowed' : '' }}">
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
  <div class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
    @foreach ($products as $product)
        @php
            $productImage = optional($product->thumbnail)->image_url ?? null;
            $productName = $product->translation->name ?? $product->name ?? 'Product';
            $primaryVariant = $product->primaryVariant;
            $originalPrice = $primaryVariant->converted_price ?? 0;
            $discountPrice = $primaryVariant->converted_discount_price ?? 0;
            $averageRating = round($product->reviews_avg_rating ?? 4.5, 1);
            $reviewCount = $product->reviews_count ?? 0;
            
            // Check food menu availability
            $isFoodMenu = $product->is_food_menu === 'yes';
           
            $isAvailable = is_food_menu_available($product);
        $availabilityMessage = get_food_menu_availability_message($product);
            $isComingSoon = $product['is_coming_soon'] ?? false;
            if ($isComingSoon) {
                $isAvailable = false;
                $availabilityMessage = "Coming Soon";
            }

        @endphp

        <div class="relative product-card-container">
            <div class="relative bg-white rounded-lg shadow-sm hover:shadow-md overflow-hidden group transition-all duration-300 {{ !$isAvailable ? 'product-unavailable' : '' }}">
                    @if(!$isAvailable)
                        <div class="unavailable-overlay">
                            {{ $availabilityMessage }}
                        </div>
                    @endif
                
                <!-- Product Image (Reduced height) -->
                <div class="relative overflow-hidden">
                      <a href="{{ $isAvailable ? url('/product/' . $product['slug']) : 'javascript:void(0)' }}" 
                                   class="{{ !$isAvailable ? 'pointer-events-none cursor-default' : '' }}">
                             <img src="{{ $productImage ?  asset('/public/storage/'.$productImage) : '' . urlencode($productName) }}"
                         alt="{{ $productName }}" 
                         class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    <!-- Wishlist Button -->
                    <!-- <button class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center text-gray-500 hover:text-red-500 shadow-sm hover:shadow transition-all duration-200 z-10"
                            onclick="showWishlistMessage()">
                        <i class="fa-regular fa-heart text-sm"></i>
                    </button> -->

                    <!-- Discount Badge -->
                    @if($discountPrice && $originalPrice > $discountPrice && $isAvailable)
                        @php
                            $discountPercent = round((($originalPrice - $discountPrice) / $originalPrice) * 100);
                        @endphp
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded-full">
                            -{{ $discountPercent }}%
                        </span>
                    @endif
                </div>

                <!-- Product Info (Reduced padding & spacing) -->
                <div class="p-3">
                    <a href="{{ $isAvailable ? url('/product/'.$product->slug) : 'javascript:void(0)' }}" 
                       class="block font-medium text-gray-800 hover:text-orange-600 transition-colors duration-200 mb-1 line-clamp-2 text-sm h-10 {{ !$isAvailable ? 'pointer-events-none' : '' }}">
                        {{ $productName }}
                    </a>

                    <!-- Ratings (smaller) -->
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
                        </div>
                        <span class="text-gray-500 text-[11px] ml-1">({{ $reviewCount }})</span>
                    </div>

                    <!-- Price + Add Button -->
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col leading-tight">
                            @if($discountPrice && $originalPrice > $discountPrice && $isAvailable)
                                <span class="text-gray-400 line-through text-xs">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                                <span class="text-red-600 font-semibold text-sm">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
                            @else
                                <span class="text-gray-800 font-semibold text-sm">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                            @endif
                        </div>

                        <button onclick="{{ $isAvailable ? 'addToCart(' . $product->id . ')' : 'showUnavailableMessage()' }}" 
                                class="bg-red-500 text-white px-2.5 py-1.5 rounded-md hover:bg-red-600 transition-colors duration-200 flex items-center space-x-1 text-xs font-medium {{ !$isAvailable ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <i class="fas fa-shopping-cart text-[10px]"></i>
                            <span>Add</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

</section>

<div class="container mx-auto px-4 py-8 space-y-10">

    @foreach ($categoryProducts as $category)
        @php
            $category_products = collect($category['products'])->take(20);
        @endphp

        <section>
            <!-- 🏷️ Category Title -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $category['category_name'] }}
                </h2>
                <a href="{{ url('/products') }}" 
                   class="text-sm text-red-500 hover:text-red-600 font-medium">
                   View All →
                </a>
            </div>

            <!-- 🛒 Horizontal Scroll Product Row -->
            <div class="flex space-x-4 overflow-x-auto scrollbar-hide pb-2">
                @foreach ($category_products as $product)
                    @php
                        // Use the availability data from API response
                        $isFoodMenu = $product['is_food_menu'] ?? false;
                        $isAvailable = $product['is_available'] ?? true;
                        $availabilityMessage = $product['availability_message'] ?? '';
                        $aproduct = \App\Models\Product::find($product['id']);
                        $isAvailable = is_food_menu_available($aproduct);
        $availabilityMessage = get_food_menu_availability_message($aproduct);
                        $isComingSoon = $product['is_coming_soon'] ?? false;
                        if ($isComingSoon) {
                            $isAvailable = false;
                            $availabilityMessage = "Coming Soon";
                        }
                    @endphp

                    <div class="min-w-[160px] sm:min-w-[180px] lg:min-w-[200px] product-card-container">
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex-shrink-0 {{ !$isAvailable ? 'product-unavailable' : '' }}">
                            @if(!$isAvailable)
                                <div class="unavailable-overlay">
                                    {{ $availabilityMessage }}
                                </div>
                            @endif
                            
                            <!-- Product Image -->
                            <div class="relative">
                                  <a href="{{ $isAvailable ? url('/product/' . $product['slug']) : 'javascript:void(0)' }}" 
                                   class="{{ !$isAvailable ? 'pointer-events-none cursor-default' : '' }}">
                                <img src="{{ asset('/public/'.$product['image']) ?? asset('public/images/no-image.png') }}"
                                     alt="{{ $product['name'] }}"
                                     class="w-full h-36 object-cover rounded-t-lg {{ !$isAvailable ? 'filter blur-[1px]' : '' }}">
                                </a>
                                @if($product['discount_price'] && $product['original_price'] > $product['discount_price'] && $isAvailable)
                                    @php
                                        $discountPercent = round((($product['original_price'] - $product['discount_price']) / $product['original_price']) * 100);
                                    @endphp
                                    <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full font-semibold">
                                        -{{ $discountPercent }}%
                                    </span>
                                @endif

                                <!-- Coming Soon Badge -->
                                @if($isComingSoon && $isAvailable === false)
                                    <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full font-semibold">
                                        Coming Soon
                                    </span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-2.5 {{ !$isAvailable ? 'opacity-70' : '' }}">
                                <a href="{{ $isAvailable ? url('/product/' . $product['slug']) : 'javascript:void(0)' }}" 
                                   class="block text-[13px] font-medium text-gray-800 hover:text-orange-600 line-clamp-2 h-10 mb-1 {{ !$isAvailable ? 'pointer-events-none cursor-default' : '' }}">
                                    {{ $product['name'] }}
                                </a>

                                <!-- Ratings -->
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product['average_rating']))
                                            <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                                        @elseif ($i - 0.5 == $product['average_rating'])
                                            <i class="fas fa-star-half-alt text-yellow-400 text-[10px]"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400 text-[10px]"></i>
                                        @endif
                                    @endfor
                                    <span class="text-gray-500 text-[10px] ml-1">({{ $product['review_count'] }})</span>
                                </div>

                                <!-- Price + Add Button -->
                                <div class="flex justify-between items-center">
                                    <div class="{{ !$isAvailable ? 'opacity-60' : '' }}">
                                        @if($product['discount_price'] && $product['original_price'] > $product['discount_price'] && $isAvailable)
                                            <span class="block text-gray-400 line-through text-[11px]">{{ $currency->symbol ?? '₹' }}{{ number_format($product['original_price'], 2) }}</span>
                                            <span class="text-red-600 font-semibold text-[13px]">{{ $currency->symbol ?? '₹' }}{{ number_format($product['discount_price'], 2) }}</span>
                                        @else
                                            <span class="text-gray-800 font-semibold text-[13px]">{{ $currency->symbol ?? '₹' }}{{ number_format($product['original_price'], 2) }}</span>
                                        @endif
                                    </div>

                                    <button onclick="{{ $isAvailable ? 'addToCart(' . $product['id'] . ')' : 'showUnavailableMessage()' }}" 
                                            class="bg-red-500 text-white px-2 py-1 text-[11px] rounded hover:bg-red-600 transition {{ !$isAvailable ? 'opacity-50 cursor-not-allowed bg-gray-400 hover:bg-gray-400' : '' }}">
                                        <i class="fas fa-shopping-cart text-[9px]"></i>
                                        <span class="text-xs">Add</span>
                                    </button>
                                </div>

                                <!-- Availability Details (for debugging - can be removed in production) -->
                                @if($isFoodMenu && !$isAvailable)
                                    <div class="mt-2 text-[10px] text-gray-500 border-t pt-1">
                                        @if($product['available_from_date'] && $product['available_to_date'])
                                            <div>Order Available: {{ \Carbon\Carbon::parse($product['available_from_date'])->format('M d') }} - {{ \Carbon\Carbon::parse($product['available_to_date'])->format('M d') }}</div>
                                        @endif
                                        @if($product['available_from_time'] && $product['available_to_time'])
                                            <div>Time: {{ \Carbon\Carbon::parse($product['available_from_time'])->format('g:i A') }} - {{ \Carbon\Carbon::parse($product['available_to_time'])->format('g:i A') }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach

</div>

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

// Unavailable product message
function showUnavailableMessage() {
    toastr.warning('This product is not available at the moment.', 'Product Unavailable');
}

// Add to Cart functionality
function addToCart(productId) {
    // Your existing addToCart function implementation
    console.log('Adding product to cart:', productId);
}

</script>

    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('#bannerCarousel .slide');

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active-slide'));
            slides[index].classList.add('active-slide');
        }

        function moveSlide(step) {
            currentIndex = (currentIndex + step + slides.length) % slides.length;
            showSlide(currentIndex);
        }

        // Auto slide every 4 seconds
        setInterval(() => moveSlide(1), 4000);
    </script>
@endsection