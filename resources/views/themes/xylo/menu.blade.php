@extends('themes.xylo.partials.app')
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
</style>

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <section class="bg-white-600 text-white py-8 md:py-16">
    <div class="relative w-full " id="bannerCarousel">
        @foreach ($banners as $index => $banner)
            <div class="slide {{ $index === 0 ? 'active-slide' : '' }}">
                <img src="{{ asset('/'.$banner['image_url']) }}" alt="{{ $banner['name'] }}" class="w-full h-64 sm:h-96 object-cover">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Our Daily Menu</h1>
            <p class="text-gray-600 mt-2">Order by 10:00 PM for next day delivery</p>
        </div>

        <!-- Three Day Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    @foreach($threeDays as $index => $dayData)
                    <button 
                        @click="activeTab = {{ $index }}"
                        :class="activeTab === {{ $index }} ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        {{ $dayData['display_name'] }}
                        <br>
                        <span class="text-xs">{{ $dayData['date']->format('M j, Y') }}</span>
                    </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Menu Content -->
        <div x-data="{ activeTab: 0 }">
            @foreach($threeDays as $index => $dayData)
            <div x-show="activeTab === {{ $index }}" class="space-y-6">
                <!-- Cut-off Time Warning for Tomorrow -->
                @if($index === 1)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Order within <span class="font-bold" id="cutoff-timer">--:--:--</span> to get this delivered on {{ $dayData['display_name'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Pre-order Food Items -->
                @forelse($dayData['menus'] as $menu)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 capitalize">
                        {{ $menu->meal_type }} 
                        <span class="text-sm text-gray-500 ml-2">(Pre-order for {{ $dayData['display_name'] }})</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($menu->products as $product)
                        @php
                            $productImage = product_image($product);
                            $productName = product_name($product);
                            $displayPrice = product_price($product);
                            $hasDiscount = product_has_discount($product);
                            $isAvailable = is_product_available($product);
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            @if($productImage)
                            <img src="{{ $productImage }}" 
                                 alt="{{ $productName }}" 
                                 class="w-full h-32 object-cover rounded-md mb-3">
                            @else
                            <div class="w-full h-32 bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                            @endif
                            <h4 class="font-semibold text-gray-900">{{ $productName }}</h4>
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $product->translation->description ?? 'No description available' }}</p>
                            
                            <!-- Quantity Selector -->
                            <div class="flex items-center justify-between mt-3 mb-3">
                                <span class="text-sm text-gray-600">Quantity:</span>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="decrementQuantity({{ $product->id }})" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        −
                                    </button>
                                    <span id="quantity-{{ $product->id }}" class="w-8 text-center font-medium">1</span>
                                    <button type="button" 
                                            onclick="incrementQuantity({{ $product->id }})" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        +
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-lg font-bold text-red">₹{{ $displayPrice }}</span>
                                <button 
                                    onclick="addToCart({{ $product->id }}, '{{ $dayData['date']->format('Y-m-d') }}', '{{ $menu->meal_type }}')"
                                    class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors"
                                >
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center py-8 bg-white rounded-lg shadow">
                    <p class="text-gray-500">No pre-order menu available for {{ $dayData['display_name'] }}.</p>
                </div>
                @endforelse

                <!-- Regular Sale Items (Available every day) -->
                @if($saleItems->count() > 0 && $index === 0)
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        🛒 Regular Products (Available Every Day)
                    </h3>
                    <p class="text-gray-600 mb-4">These items are always available and don't require pre-ordering.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($saleItems as $product)
                         @php
                            $productImage = product_image($product);
                            $productName = product_name($product);
                            $displayPrice = product_price($product);
                            $description = product_description($product);
                            $hasDiscount = product_has_discount($product);
                            $isAvailable = is_product_available($product);
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            @if($productImage)
                            <img src="{{ $productImage }}" 
                                 alt="{{ $productName }}" 
                                 class="w-full h-32 object-cover rounded-md mb-3">
                            @else
                            <div class="w-full h-32 bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                            @endif
                            <h4 class="font-semibold text-gray-900">{{ $productName }}</h4>
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $description ?? 'No description available' }}</p>
                            
                            <!-- Quantity Selector -->
                            <div class="flex items-center justify-between mt-3 mb-3">
                                <span class="text-sm text-gray-600">Quantity:</span>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="decrementQuantity({{ $product->id }})" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        −
                                    </button>
                                    <span id="quantity-{{ $product->id }}" class="w-8 text-center font-medium">1</span>
                                    <button type="button" 
                                            onclick="incrementQuantity({{ $product->id }})" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        +
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-lg font-bold text-green-600">₹{{ $displayPrice }}</span>
                                <button 
                                    onclick="addToCart({{ $product->id }}, '{{ $dayData['date']->format('Y-m-d') }}', 'regular')"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Shopping Cart -->
@include('partials.cart')
<script>
// Cut-off timer for pre-orders
function updateCutoffTimer() {
    const now = new Date();
    const cutoff = new Date();
    cutoff.setHours(22, 0, 0, 0); // 10:00 PM
    
    // If it's already past 10 PM, set cutoff for next day
    if (now > cutoff) {
        cutoff.setDate(cutoff.getDate() + 1);
    }
    
    const diff = cutoff - now;
    
    if (diff <= 0) {
        document.getElementById('cutoff-timer').textContent = '00:00:00';
        return;
    }
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    document.getElementById('cutoff-timer').textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Quantity management functions
function incrementQuantity(productId) {
    const quantityElement = document.getElementById(`quantity-${productId}`);
    let quantity = parseInt(quantityElement.textContent);
    quantityElement.textContent = quantity + 1;
}

function decrementQuantity(productId) {
    const quantityElement = document.getElementById(`quantity-${productId}`);
    let quantity = parseInt(quantityElement.textContent);
    if (quantity > 1) {
        quantityElement.textContent = quantity - 1;
    }
}

function getQuantity(productId) {
    const quantityElement = document.getElementById(`quantity-${productId}`);
    return parseInt(quantityElement.textContent);
}

// Updated addToCart function
async function addToCart(productId, orderForDate, mealType) {
    const quantity = getQuantity(productId);
    try {
        const response = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
                order_for_date: orderForDate,
                meal_type: mealType,

            })
        });

        const result = await response.json();
        
        if (result.status === 'success') {
            // Show success message
            showNotification('Product added to cart successfully!', 'success');
            
            // Update cart count in header
            updateCartCount(result.cart_count);

            const cartCount = result.cart_count;
            document.querySelectorAll('#cart-count, #cart-count-desktop').forEach(element => {
                element.textContent = cartCount;
            });
            
            // Reset quantity
            document.getElementById(`quantity-${productId}`).textContent = '1';
        } else {
            showNotification('Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Error adding product to cart', 'error');
    }
}



// Cart badge animation for visual feedback
function animateCartBadge() {
    const cartBadge = document.getElementById('cart-count');
    if (cartBadge) {
        // Add bounce animation
        cartBadge.classList.add('animate-bounce');
        
        // Remove animation after it completes
        setTimeout(() => {
            cartBadge.classList.remove('animate-bounce');
        }, 1000);
    }
}


// Update cart count for all operations (remove, update quantity)
function handleCartOperation(result) {
    if (result.cart_count !== undefined) {
        updateCartCount(result.cart_count);
    }
}

// Notification function
function showNotification(message, type = 'info') {
    // Simple notification - you can replace with a toast library
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize
setInterval(updateCutoffTimer, 1000);
updateCutoffTimer();



</script>
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

