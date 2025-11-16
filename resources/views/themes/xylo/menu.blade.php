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
.meal-section-disabled {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}
.meal-section-disabled::after {
    content: "Order Closed";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: bold;
    z-index: 10;
}
.product-disabled {
    opacity: 0.5;
}
.availability-badge {
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 12px;
    margin-left: 8px;
}
.disabled-button {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}
.quantity-button-disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <section class="bg-white-600 text-white py-8 md:py-16">
        <div class="relative w-full " id="bannerCarousel">
            @foreach ($banners as $index => $banner)
                <div class="slide {{ $index === 0 ? 'active-slide' : '' }}">
                    <img src="{{ $banner['image_url']}}" alt="{{ $banner['name'] }}" class="w-full h-64 sm:h-96 object-cover">
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
                    @php
                        $isToday = $index === 0;
                        $isTomorrow = $index === 1;
                        $isDayAfter = $index === 2;
                    @endphp
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
            @php
                $isToday = $index === 0;
                $isTomorrow = $index === 1;
                $isDayAfter = $index === 2;
                
                // Get current time for availability checks
                $currentHour = now()->hour;
                $currentTime = now();
            @endphp
            
            <div x-show="activeTab === {{ $index }}" class="space-y-6">
                <!-- Cut-off Time Warning for Tomorrow -->
                @if($isTomorrow)
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
                @php
                    // Check if this meal section should be available
                    $isMealAvailable = true;
                    $availabilityMessage = 'Available';
                    $cutoffTime = null;
                    
                    if ($isToday) {
                        switch($menu->meal_type) {
                            case 'breakfast':
                                $isMealAvailable = false;
                                $availabilityMessage = "Today's breakfast order closed";
                                break;
                            case 'lunch':
                                $isMealAvailable = $currentHour < 10; // Available until 10AM
                                $availabilityMessage = $isMealAvailable ? "Available until 10 AM" : "Today's lunch order closed";
                                $cutoffTime = 10;
                                break;
                            case 'snacks':
                                $isMealAvailable = $currentHour < 18; // Available until 6PM
                                $availabilityMessage = $isMealAvailable ? "Available until 6 PM" : "Today's snacks order closed";
                                $cutoffTime = 18;
                                break;
                            case 'dinner':
                                $isMealAvailable = $currentHour < 17; // Available until 5PM
                                $availabilityMessage = $isMealAvailable ? "Available until 5 PM" : "Today's dinner order closed";
                                $cutoffTime = 17;
                                break;
                        }
                    } elseif ($isTomorrow || $isDayAfter) {
                        // Tomorrow and day after are always available
                        $isMealAvailable = true;
                        $availabilityMessage = "Available for pre-order";
                    }
                    
                    $availabilityClass = $isMealAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                @endphp
                
                <div class="bg-white rounded-lg shadow-md p-6 {{ !$isMealAvailable ? 'meal-section-disabled' : '' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 capitalize">
                            {{ $menu->meal_type }} 
                            <span class="text-sm text-gray-500 ml-2">(Pre-order for {{ $dayData['display_name'] }})</span>
                        </h3>
                        <span class="availability-badge {{ $availabilityClass }}">
                            {{ $availabilityMessage }}
                        </span>
                    </div>
                    
                    <!-- Countdown timer for today's meals -->
                    @if($isToday && $isMealAvailable && $cutoffTime)
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center text-sm text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Order within <span class="font-bold ml-1" id="cutoff-{{ $menu->meal_type }}">--:--:--</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($menu->products as $product)
                        @php
                            $productImage = product_image($product);
                            $productName = product_name($product);
                            $displayPrice = product_price($product);
                            $hasDiscount = product_has_discount($product);
                            $isProductAvailable = is_product_available($product) && $isMealAvailable;
                            $description = product_description($product);
                            // Create unique identifier for this product in this specific context
                            $uniqueId = $product->id . '-' . $dayData['date']->format('Y-m-d') . '-' . $menu->meal_type;
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow {{ !$isProductAvailable ? 'product-disabled' : '' }}">
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
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $description ?? '' }}</p>
                            
                            <!-- Quantity Selector - ALWAYS ALLOW QUANTITY CHANGES -->
                            <div class="flex items-center justify-between mt-3 mb-3">
                                <span class="text-sm text-gray-600">Quantity:</span>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="decrementQuantity('{{ $uniqueId }}')" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        −
                                    </button>
                                    <span id="quantity-{{ $uniqueId }}" class="w-8 text-center font-medium">1</span>
                                    <button type="button" 
                                            onclick="incrementQuantity('{{ $uniqueId }}')" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        +
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-lg font-bold text-red">₹{{ $displayPrice }}</span>
                                <button 
                                    onclick="{{ $isProductAvailable ? 'addToCartWithQuantity(' . $product->id . ', \'' . $dayData['date']->format('Y-m-d') . '\', \'' . $menu->meal_type . '\', \'' . $uniqueId . '\')' : 'showUnavailableMessage(\'' . $availabilityMessage . '\')' }}"
                                    class="{{ $isProductAvailable ? 'bg-red-700 hover:bg-red-800' : 'bg-gray-400 cursor-not-allowed' }} text-white px-4 py-2 rounded-lg transition-colors"
                                >
                                    {{ $isProductAvailable ? 'Add to Cart' : 'Unavailable' }}
                                </button>
                            </div>
                            
                            @if(!$isProductAvailable && !$isMealAvailable)
                            <div class="mt-2 text-xs text-red-600 text-center">
                                {{ $availabilityMessage }}
                            </div>
                            @endif
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
                @if($saleItems->count() > 0 && $isToday)
                @php
                    // Regular items available from 6AM to 10PM
                    $isRegularAvailable = $currentHour >= 6 && $currentHour < 22;
                    $regularAvailabilityMessage = $isRegularAvailable ? 
                        "Available (6AM - 10PM)" : 
                        "Regular orders available from 6AM to 10PM";
                @endphp
                
                <div class="bg-white rounded-lg shadow-md p-6 mt-6 {{ !$isRegularAvailable ? 'meal-section-disabled' : '' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">
                            🛒 Regular Products (Available Every Day)
                        </h3>
                        <span class="availability-badge {{ $isRegularAvailable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $regularAvailabilityMessage }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4">These items are always available and don't require pre-ordering.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($saleItems as $product)
                        @php
                            $productImage = product_image($product);
                            $productName = product_name($product);
                            $displayPrice = product_price($product);
                            $description = product_description($product);
                            $hasDiscount = product_has_discount($product);
                            $isProductAvailable = is_product_available($product) && $isRegularAvailable;
                            // Create unique identifier for regular products
                            $uniqueId = $product->id . '-regular-' . $dayData['date']->format('Y-m-d');
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow {{ !$isProductAvailable ? 'product-disabled' : '' }}">
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
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $description ?? '' }}</p>
                            
                            <!-- Quantity Selector - ALWAYS ALLOW QUANTITY CHANGES -->
                            <div class="flex items-center justify-between mt-3 mb-3">
                                <span class="text-sm text-gray-600">Quantity:</span>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="decrementQuantity('{{ $uniqueId }}')" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        −
                                    </button>
                                    <span id="quantity-{{ $uniqueId }}" class="w-8 text-center font-medium">1</span>
                                    <button type="button" 
                                            onclick="incrementQuantity('{{ $uniqueId }}')" 
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        +
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-lg font-bold text-green-600">₹{{ $displayPrice }}</span>
                                <button 
                                    onclick="{{ $isProductAvailable ? 'addToCartWithQuantity(' . $product->id . ', \'' . $dayData['date']->format('Y-m-d') . '\', \'regular\', \'' . $uniqueId . '\')' : 'showUnavailableMessage(\'' . $regularAvailabilityMessage . '\')' }}"
                                    class="{{ $isProductAvailable ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }} text-white px-4 py-2 rounded-lg transition-colors"
                                >
                                    {{ $isProductAvailable ? 'Add to Cart' : 'Unavailable' }}
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
<script>
let quantityStore = {};

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

// Individual meal cutoff timers for today
function updateMealCutoffTimers() {
    const now = new Date();
    const currentHour = now.getHours();
    
    // Only update for today's meals
    const mealCutoffs = {
        'lunch': 10,  // Until 10AM
        'snacks': 18, // Until 6PM  
        'dinner': 17  // Until 5PM
    };
    
    Object.keys(mealCutoffs).forEach(mealType => {
        const cutoffHour = mealCutoffs[mealType];
        const cutoffElement = document.getElementById(`cutoff-${mealType}`);
        
        if (cutoffElement && currentHour < cutoffHour) {
            const cutoffTime = new Date();
            cutoffTime.setHours(cutoffHour, 0, 0, 0);
            
            const diff = cutoffTime - now;
            
            if (diff > 0) {
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                cutoffElement.textContent = 
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            } else {
                cutoffElement.textContent = '00:00:00';
                // Reload page to update availability status
                setTimeout(() => location.reload(), 1000);
            }
        }
    });
}

// NEW: Show unavailable message
function showUnavailableMessage(message) {
    showNotification(message, 'error');
}

// Quantity management functions with unique IDs
function incrementQuantity(uniqueId) {
    if (!quantityStore[uniqueId]) quantityStore[uniqueId] = 1;
    quantityStore[uniqueId] = quantityStore[uniqueId] + 1;
    updateQuantityDisplay(uniqueId);
}

function decrementQuantity(uniqueId) {
    if (!quantityStore[uniqueId]) quantityStore[uniqueId] = 1;
    if (quantityStore[uniqueId] > 1) {
        quantityStore[uniqueId] = quantityStore[uniqueId] - 1;
    }
    updateQuantityDisplay(uniqueId);
}

function getQuantity(uniqueId) {
    return quantityStore[uniqueId] ?? 1;
}

// Update UI only
function updateQuantityDisplay(uniqueId) {
    const el = document.getElementById(`quantity-${uniqueId}`);
    if (el) {
        el.textContent = quantityStore[uniqueId];
    }
}

// NEW: Add to cart with quantity from specific product instance
async function addToCartWithQuantity(productId, orderForDate, mealType, uniqueId) {
    const quantity = getQuantity(uniqueId);
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
            showNotification('Product added to cart successfully!', 'success');
            
            // Update cart count in header
            updateCartCount(result.cart_count);

            const cartCount = result.cart_count;
            document.querySelectorAll('#cart-count, #cart-count-desktop').forEach(element => {
                element.textContent = cartCount;
            });
            
            // Reset quantity for this specific product instance
            quantityStore[uniqueId] = 1;
            updateQuantityDisplay(uniqueId);
        } else {
            showNotification(result.message || 'Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Error adding product to cart', 'error');
    }
}

// Keep original function for backward compatibility
async function addToCart(productId, orderForDate, mealType) {
    // Fallback to quantity 1 if called directly
    await addToCartWithQuantity(productId, orderForDate, mealType, productId.toString());
}

// Cart badge animation for visual feedback
function animateCartBadge() {
    const cartBadge = document.getElementById('cart-count');
    if (cartBadge) {
        cartBadge.classList.add('animate-bounce');
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
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
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
setInterval(updateMealCutoffTimers, 1000);
updateCutoffTimer();
updateMealCutoffTimers();

// Banner carousel
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