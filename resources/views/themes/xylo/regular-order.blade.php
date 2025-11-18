@extends('themes.xylo.partials.app')
<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.slide {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
.active-slide {
    display: block;
    opacity: 1;
}
.product-disabled {
    opacity: 0.5;
}
.availability-badge {
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 12px;
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

/* Mobile: 1 per row on very small screens, 2 per row on small screens */
.regular-products-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 12px;
    padding: 8px 4px;
}

@media (min-width: 400px) {
    .regular-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        padding: 12px 8px;
    }
}

@media (min-width: 640px) {
    .regular-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 16px;
    }
}

@media (min-width: 768px) {
    .regular-products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
}

@media (min-width: 1024px) {
    .regular-products-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 24px;
    }
}

/* Improved mobile typography */
.mobile-text-sm {
    font-size: 0.875rem;
}
.mobile-text-xs {
    font-size: 0.75rem;
}

/* Better mobile spacing */
.mobile-padding {
    padding: 12px;
}
@media (min-width: 768px) {
    .mobile-padding {
        padding: 24px;
    }
}

/* Improved button sizing for mobile */
.mobile-btn {
    padding: 8px 12px;
    font-size: 0.75rem;
}
@media (min-width: 640px) {
    .mobile-btn {
        padding: 10px 16px;
        font-size: 0.875rem;
    }
}

/* Better image sizing for mobile */
.product-image {
    height: 120px;
    object-fit: cover;
}
@media (min-width: 640px) {
    .product-image {
        height: 140px;
    }
}
@media (min-width: 768px) {
    .product-image {
        height: 160px;
    }
}

/* Improved header sizing */
.mobile-header {
    font-size: 1.5rem;
}
@media (min-width: 768px) {
    .mobile-header {
        font-size: 2rem;
    }
}

/* Better banner height for mobile */
.banner-mobile {
    height: 200px;
}
@media (min-width: 640px) {
    .banner-mobile {
        height: 250px;
    }
}
@media (min-width: 768px) {
    .banner-mobile {
        height: 300px;
    }
}
@media (min-width: 1024px) {
    .banner-mobile {
        height: 384px;
    }
}

/* Improved quantity buttons for mobile */
.quantity-btn {
    width: 28px;
    height: 28px;
    font-size: 0.75rem;
}
@media (min-width: 640px) {
    .quantity-btn {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
}

/* Better container padding */
.container-mobile {
    padding-left: 12px;
    padding-right: 12px;
}
@media (min-width: 640px) {
    .container-mobile {
        padding-left: 16px;
        padding-right: 16px;
    }
}
@media (min-width: 1024px) {
    .container-mobile {
        padding-left: 32px;
        padding-right: 32px;
    }
}

/* Improved flex layout for mobile headers */
.flex-mobile {
    flex-direction: column;
    gap: 12px;
}
@media (min-width: 640px) {
    .flex-mobile {
        flex-direction: row;
        gap: 0;
    }
}

/* Line clamp for better text handling */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom notification for mobile */
.custom-notification {
    max-width: calc(100vw - 32px);
    word-wrap: break-word;
}

/* Search and Filter Styles */
.search-filter-container {
    background: white;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.search-box {
    position: relative;
    margin-bottom: 16px;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 44px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.search-input:focus {
    outline: none;
    border-color: #10b981;
    background: white;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.filter-section {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    cursor: pointer;
    min-width: 120px;
}

.filter-select:focus {
    outline: none;
    border-color: #10b981;
}

.clear-filters {
    padding: 8px 16px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-left: auto;
}

.clear-filters:hover {
    background: #dc2626;
}

/* No results message */
.no-results {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.no-results-icon {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
}

/* Product card hidden state */
.product-card-hidden {
    display: none;
}

/* Active filter state */
.filter-active {
    background: #10b981;
    color: white;
}

/* Price range slider */
.price-range {
    width: 100%;
    margin: 8px 0;
}

.price-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 4px;
}

/* Mobile filter improvements */
@media (max-width: 640px) {
    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        justify-content: space-between;
    }
    
    .filter-select {
        flex: 1;
        min-width: auto;
    }
    
    .clear-filters {
        margin-left: 0;
        margin-top: 8px;
    }
}

/* Loading state */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #10b981;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    
    <div class="max-w-7xl mx-auto container-mobile">
        <!-- Header -->
        <div class="text-center mb-6 md:mb-8">
            <h1 class="mobile-header font-bold text-gray-900">Regular Products</h1>
            <p class="text-gray-600 mt-2 text-sm md:text-base">Available every day for immediate order</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-container">
            <!-- Search Box -->
            <div class="search-box">
                <div class="search-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
                <input type="text" id="productSearch" class="search-input" placeholder="Search products by name..." onkeyup="filterProducts()">
            </div>

            <!-- Filter Options -->
            <div class="filter-section">
                <!-- Category Filter -->
                <div class="filter-group">
                    <label class="filter-label">Category:</label>
                    <select id="categoryFilter" class="filter-select" onchange="filterProducts()">
                        <option value="">All Categories</option>
                        <option value="veg">Vegetarian</option>
                        <option value="non-veg">Non-Vegetarian</option>
                        <option value="vegan">Vegan</option>
                        <option value="beverage">Beverages</option>
                        <option value="snack">Snacks</option>
                        <option value="main-course">Main Course</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="filter-group">
                    <label class="filter-label">Price Range:</label>
                    <select id="priceFilter" class="filter-select" onchange="filterProducts()">
                        <option value="">All Prices</option>
                        <option value="0-100">Under ₹100</option>
                        <option value="100-200">₹100 - ₹200</option>
                        <option value="200-500">₹200 - ₹500</option>
                        <option value="500-1000">₹500 - ₹1000</option>
                        <option value="1000+">Above ₹1000</option>
                    </select>
                </div>

                <!-- Availability Filter -->
                <div class="filter-group">
                    <label class="filter-label">Availability:</label>
                    <select id="availabilityFilter" class="filter-select" onchange="filterProducts()">
                        <option value="">All</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="filter-group">
                    <label class="filter-label">Sort By:</label>
                    <select id="sortFilter" class="filter-select" onchange="sortProducts()">
                        <option value="name-asc">Name (A-Z)</option>
                        <option value="name-desc">Name (Z-A)</option>
                        <option value="price-asc">Price (Low to High)</option>
                        <option value="price-desc">Price (High to Low)</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <button id="clearFilters" class="clear-filters" onclick="clearAllFilters()">
                    Clear All
                </button>
            </div>

            <!-- Active Filters Display -->
            <div id="activeFilters" class="flex flex-wrap gap-2 mt-3 hidden">
                <!-- Active filters will be displayed here -->
            </div>
        </div>

        <!-- Regular Products Section -->
        @php
            $currentHour = now()->hour;
            $isRegularAvailable = $currentHour >= 6 && $currentHour < 24;
            $isRegularAvailable = true;
            $regularAvailabilityMessage = $isRegularAvailable ? 
                "Available (6AM - 10PM)" : 
                "Regular orders available from 6AM to 10PM";
        @endphp
        
        <div class="bg-white rounded-lg shadow-md mobile-padding {{ !$isRegularAvailable ? 'meal-section-disabled' : '' }}">
            <div class="flex-mobile items-center justify-between mb-4 md:mb-6">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900 text-center md:text-left">
                    🛒 Regular Products (within 1 hour)
                </h3>
                <span class="availability-badge {{ $isRegularAvailable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-center md:text-left mt-2 md:mt-0">
                    {{ $regularAvailabilityMessage }}
                </span>
            </div>

            <!-- Results Count -->
            <div id="resultsCount" class="mb-4 text-sm text-gray-600">
                Showing {{ $saleItems->count() }} products
            </div>
            
            @if($saleItems->count() > 0)
            <div class="regular-products-grid" id="productsGrid">
                @foreach($saleItems as $product)
                @php
                    $productImage = product_image($product);
                    $productName = product_name($product);
                    $displayPrice = product_price($product);
                    $description = product_description($product);
                    $isProductAvailable = is_product_available($product) && $isRegularAvailable;
                    $uniqueId = $product->id . '-regular-' . now()->format('Y-m-d');
                    
                    // Mock product data for filtering - replace with actual product attributes
                    $productCategory = 'veg'; // This should come from your product model
                    $isPopular = $loop->index < 3; // Mock popular products
                @endphp

                <div class="product-card border border-gray-200 rounded-lg p-3 md:p-2 hover:shadow-lg transition-shadow {{ !$isProductAvailable ? 'product-disabled' : '' }} flex flex-col"
                     data-name="{{ strtolower($productName) }}"
                     data-category="{{ $productCategory }}"
                     data-price="{{ $displayPrice }}"
                     data-available="{{ $isProductAvailable ? 'true' : 'false' }}"
                     data-popular="{{ $isPopular ? 'true' : 'false' }}">
                    @if($productImage)
                    <img src="{{ $productImage }}" alt="{{ $productName }}" class="w-full product-image rounded-md mb-2">
                    @else
                    <div class="w-full product-image bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">No Image</span>
                    </div>
                    @endif
                    
                    <div class="flex-grow">
                        <h4 class="font-semibold text-gray-900 mobile-text-sm md:text-base leading-tight">{{ $productName }}</h4>
                        <p class="text-gray-600 mobile-text-xs md:text-sm mt-1 line-clamp-2">{{ $description ?? '' }}</p>
                    </div>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mt-3 mb-3">
                        <span class="mobile-text-xs md:text-sm text-gray-600">Qty:</span>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="decrementQuantity('{{ $uniqueId }}')" class="quantity-btn rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">−</button>
                            <span id="quantity-{{ $uniqueId }}" class="w-6 md:w-8 text-center font-medium mobile-text-xs md:text-sm">1</span>
                            <button type="button" onclick="incrementQuantity('{{ $uniqueId }}')" class="quantity-btn rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition-colors">+</button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-sm md:text-lg font-bold text-green-600">₹{{ $displayPrice }}</span>
                        <button onclick="{{ $isProductAvailable ? 'addToCartWithQuantity(' . $product->id . ', \'' . now()->format('Y-m-d') . '\', \'regular\', \'' . $uniqueId . '\')' : 'showUnavailableMessage(\'' . $regularAvailabilityMessage . '\')' }}" class="{{ $isProductAvailable ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }} text-white mobile-btn rounded-lg transition-colors">
                            {{ $isProductAvailable ? 'Add to Cart' : 'Unavailable' }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="no-results hidden">
                <div class="no-results-icon">🔍</div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No products found</h3>
                <p class="text-gray-500">Try adjusting your search or filters to find what you're looking for.</p>
            </div>
            @else
            <div class="text-center py-6 md:py-8">
                <p class="text-gray-500 text-sm md:text-base">No regular products available at the moment.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Shared JavaScript functions
let quantityStore = {};

function incrementQuantity(uniqueId) {
    if (!quantityStore[uniqueId]) quantityStore[uniqueId] = 1;
    quantityStore[uniqueId]++;
    updateQuantityDisplay(uniqueId);
}

function decrementQuantity(uniqueId) {
    if (!quantityStore[uniqueId]) quantityStore[uniqueId] = 1;
    if (quantityStore[uniqueId] > 1) {
        quantityStore[uniqueId]--;
    }
    updateQuantityDisplay(uniqueId);
}

function updateQuantityDisplay(uniqueId) {
    const el = document.getElementById(`quantity-${uniqueId}`);
    if (el) el.textContent = quantityStore[uniqueId] || 1;
}

function getQuantity(uniqueId) {
    return quantityStore[uniqueId] || 1;
}

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
            updateCartCount(result.cart_count);
             const cartCount = result.cart_count;
            document.querySelectorAll('#cart-count, #cart-count-desktop').forEach(element => {
                element.textContent = cartCount;
            });
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

function showNotification(message, type = 'info') {
     // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-14 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showUnavailableMessage(message) {
    showNotification(message, 'error');
}

function updateCartCount(count) {
    const cartElements = document.querySelectorAll('#cart-count, #cart-count-desktop');
    cartElements.forEach(element => {
        element.textContent = count;
        element.classList.add('animate-bounce');
        setTimeout(() => {
            element.classList.remove('animate-bounce');
        }, 1000);
    });
}

// Banner carousel
let currentIndex = 0;
const slides = document.querySelectorAll('#bannerCarousel .slide');

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active-slide'));
    if (slides[index]) {
        slides[index].classList.add('active-slide');
    }
}

function moveSlide(step) {
    currentIndex = (currentIndex + step + slides.length) % slides.length;
    showSlide(currentIndex);
}

// Search and Filter Functions
function filterProducts() {
    const searchTerm = document.getElementById('productSearch').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    const availabilityFilter = document.getElementById('availabilityFilter').value;
    
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
        const productName = card.getAttribute('data-name');
        const productCategory = card.getAttribute('data-category');
        const productPrice = parseInt(card.getAttribute('data-price'));
        const isAvailable = card.getAttribute('data-available') === 'true';
        
        let matchesSearch = true;
        let matchesCategory = true;
        let matchesPrice = true;
        let matchesAvailability = true;
        
        // Search filter
        if (searchTerm && !productName.includes(searchTerm)) {
            matchesSearch = false;
        }
        
        // Category filter
        if (categoryFilter && productCategory !== categoryFilter) {
            matchesCategory = false;
        }
        
        // Price filter
        if (priceFilter) {
            if (priceFilter === '0-100' && productPrice > 100) {
                matchesPrice = false;
            } else if (priceFilter === '100-200' && (productPrice < 100 || productPrice > 200)) {
                matchesPrice = false;
            } else if (priceFilter === '200-500' && (productPrice < 200 || productPrice > 500)) {
                matchesPrice = false;
            } else if (priceFilter === '500-1000' && (productPrice < 500 || productPrice > 1000)) {
                matchesPrice = false;
            } else if (priceFilter === '1000+' && productPrice <= 1000) {
                matchesPrice = false;
            }
        }
        
        // Availability filter
        if (availabilityFilter === 'available' && !isAvailable) {
            matchesAvailability = false;
        } else if (availabilityFilter === 'unavailable' && isAvailable) {
            matchesAvailability = false;
        }
        
        // Show/hide card based on all filters
        if (matchesSearch && matchesCategory && matchesPrice && matchesAvailability) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update results count
    document.getElementById('resultsCount').textContent = `Showing ${visibleCount} products`;
    
    // Show/hide no results message
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0) {
        noResults.classList.remove('hidden');
    } else {
        noResults.classList.add('hidden');
    }
    
    // Update active filters display
    updateActiveFilters();
}

function sortProducts() {
    const sortValue = document.getElementById('sortFilter').value;
    const productsGrid = document.getElementById('productsGrid');
    const productCards = Array.from(document.querySelectorAll('.product-card'));
    
    productCards.sort((a, b) => {
        const nameA = a.getAttribute('data-name');
        const nameB = b.getAttribute('data-name');
        const priceA = parseInt(a.getAttribute('data-price'));
        const priceB = parseInt(b.getAttribute('data-price'));
        const popularA = a.getAttribute('data-popular') === 'true';
        const popularB = b.getAttribute('data-popular') === 'true';
        
        switch (sortValue) {
            case 'name-asc':
                return nameA.localeCompare(nameB);
            case 'name-desc':
                return nameB.localeCompare(nameA);
            case 'price-asc':
                return priceA - priceB;
            case 'price-desc':
                return priceB - priceA;
            case 'popular':
                return (popularB === popularA) ? 0 : popularB ? -1 : 1;
            default:
                return 0;
        }
    });
    
    // Reappend sorted cards
    productCards.forEach(card => {
        productsGrid.appendChild(card);
    });
}

function updateActiveFilters() {
    const activeFiltersContainer = document.getElementById('activeFilters');
    const searchTerm = document.getElementById('productSearch').value;
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    const availabilityFilter = document.getElementById('availabilityFilter').value;
    
    let activeFiltersHTML = '';
    
    if (searchTerm) {
        activeFiltersHTML += `<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center gap-2">
            Search: "${searchTerm}"
            <button onclick="clearSearch()" class="text-blue-600 hover:text-blue-800">×</button>
        </span>`;
    }
    
    if (categoryFilter) {
        const categoryLabel = document.querySelector(`#categoryFilter option[value="${categoryFilter}"]`).textContent;
        activeFiltersHTML += `<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center gap-2">
            Category: ${categoryLabel}
            <button onclick="clearFilter('categoryFilter')" class="text-green-600 hover:text-green-800">×</button>
        </span>`;
    }
    
    if (priceFilter) {
        const priceLabel = document.querySelector(`#priceFilter option[value="${priceFilter}"]`).textContent;
        activeFiltersHTML += `<span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm flex items-center gap-2">
            Price: ${priceLabel}
            <button onclick="clearFilter('priceFilter')" class="text-purple-600 hover:text-purple-800">×</button>
        </span>`;
    }
    
    if (availabilityFilter) {
        const availabilityLabel = document.querySelector(`#availabilityFilter option[value="${availabilityFilter}"]`).textContent;
        activeFiltersHTML += `<span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm flex items-center gap-2">
            Availability: ${availabilityLabel}
            <button onclick="clearFilter('availabilityFilter')" class="text-orange-600 hover:text-orange-800">×</button>
        </span>`;
    }
    
    if (activeFiltersHTML) {
        activeFiltersContainer.innerHTML = activeFiltersHTML;
        activeFiltersContainer.classList.remove('hidden');
    } else {
        activeFiltersContainer.classList.add('hidden');
    }
}

function clearSearch() {
    document.getElementById('productSearch').value = '';
    filterProducts();
}

function clearFilter(filterId) {
    document.getElementById(filterId).value = '';
    filterProducts();
}

function clearAllFilters() {
    document.getElementById('productSearch').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('priceFilter').value = '';
    document.getElementById('availabilityFilter').value = '';
    document.getElementById('sortFilter').value = 'name-asc';
    
    filterProducts();
    sortProducts();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Start banner carousel
    if (slides.length > 1) {
        setInterval(() => moveSlide(1), 4000);
    }
    
    // Initialize search functionality
    const searchInput = document.getElementById('productSearch');
    if (searchInput) {
        searchInput.focus();
    }
});
</script>
@endsection