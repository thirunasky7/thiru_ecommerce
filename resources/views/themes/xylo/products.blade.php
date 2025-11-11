@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f5f2;
    }
    
    .coffee-title {
        font-family: 'Playfair Display', serif;
        color: #3e2723;
    }
    
    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .add-to-cart-btn {
        background: linear-gradient(to right, #8B4513, #A0522D);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    
    .add-to-cart-btn:hover {
        background: linear-gradient(to right, #A0522D, #8B4513);
        transform: scale(1.05);
    }
    
    .filter-sidebar {
        transition: all 0.3s ease;
    }
    
    .mobile-filter-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }
    
    .mobile-filter-panel {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 50;
    }
    
    .mobile-filter-panel.open {
        transform: translateX(0);
    }
    
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        cursor: pointer;
    }
    
    .checkbox-container input {
        display: none;
    }
    
    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid #A0522D;
        border-radius: 4px;
        margin-right: 10px;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .checkbox-container input:checked + .checkmark {
        background-color: #A0522D;
    }
    
    .checkbox-container input:checked + .checkmark:after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .active-filter-tag {
        background-color: #A0522D;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
    }
    
    .active-filter-tag button {
        margin-left: 5px;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
    }
    
    .no-products {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .filter-sidebar {
            display: none;
        }
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

<body class="bg-gray-50 mt-10">
<section class="container mx-auto py-8 md:py-12 px-4">

   <div class="lg:hidden bg-white shadow-sm py-11 px-4 sticky top-5 z-30">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold coffee-title"> Products</h1>
            <button id="mobileFilterBtn" class="bg-amber-800 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-filter mr-2"></i> Filters
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container mx-auto py-14 px-4 md:px-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Desktop Filter Sidebar -->
            <div class="filter-sidebar lg:w-1/4 bg-white rounded-lg shadow p-6 h-fit lg:sticky lg:top-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold coffee-title">Filters</h2>
                    <button id="clearFilters" class="text-amber-800 text-sm">Clear All</button>
                </div>
                
                <!-- Category Filter -->
                <div class="mb-8">
                    <h3 class="font-semibold text-lg mb-4 coffee-title">Categories</h3>
                    <div id="categoryFilters">
                        @foreach($categories as $category)
                            @php
                                $categoryName = $category->translation->name ?? $category->name ?? 'Category';
                                $categoryId = $category->id;
                                $productCount = $category->products->where('status', 1)->count() ?? 0;
                            @endphp
                            <div class="checkbox-container">
                                <input type="checkbox" id="category-{{ $categoryId }}" value="{{ $categoryId }}" data-name="{{ $categoryName }}">
                                <span class="checkmark"></span>
                                <label for="category-{{ $categoryId }}" class="flex-1 cursor-pointer">{{ $categoryName }}</label>
                                <span class="text-gray-500 text-sm">({{ $productCount }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
              
                <!-- Rating Filter -->
                <div class="mb-8">
                    <h3 class="font-semibold text-lg mb-4 coffee-title">Customer Rating</h3>
                    <div class="space-y-2" id="ratingFilters">
                        <div class="checkbox-container">
                            <input type="radio" name="rating" id="rating-4" value="4">
                            <span class="checkmark rounded-full"></span>
                            <label for="rating-4" class="flex-1 cursor-pointer flex items-center">
                                4 Stars & Up
                                <div class="ml-2 flex text-amber-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </label>
                        </div>
                        <div class="checkbox-container">
                            <input type="radio" name="rating" id="rating-3" value="3">
                            <span class="checkmark rounded-full"></span>
                            <label for="rating-3" class="flex-1 cursor-pointer flex items-center">
                                3 Stars & Up
                                <div class="ml-2 flex text-amber-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </label>
                        </div>
                        <div class="checkbox-container">
                            <input type="radio" name="rating" id="rating-2" value="2">
                            <span class="checkmark rounded-full"></span>
                            <label for="rating-2" class="flex-1 cursor-pointer flex items-center">
                                2 Stars & Up
                                <div class="ml-2 flex text-amber-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Section -->
            <div class="lg:w-3/4">
                <!-- Page Header (Desktop) -->
                <div class="hidden lg:flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold coffee-title">Our Products</h1>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-3">Sort by:</span>
                        <select id="sortSelect" class="border rounded-lg px-4 py-2">
                            <option value="newest">Newest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                    </div>
                </div>
                
                <!-- Active Filters -->
                <div id="activeFilters" class="active-filters">
                    <!-- Active filter tags will appear here -->
                </div>
                
                <!-- Products Grid -->
                <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        @php
                            $productName = $product->translation->name ?? $product->name ?? 'Product';
                            $productDescription = $product->translation->description ?? $product->description ?? '';
                            $productPrice = $product->primaryVariant->price ?? $product->price ?? 0;
                            $productImage = $product->thumbnail->image_url ?? 'https://via.placeholder.com/300x300/6c757d/ffffff?text=Product';
                            $categoryName = $product->category->translation->name ?? $product->category->name ?? 'Uncategorized';
                            $categoryId = $product->category->id ?? 0;
                            $rating = $product->reviews->avg('rating') ?? 0;
                            $reviewCount = $product->reviews_count ?? 0;
                            // Check food menu availability
            $isFoodMenu = $product->is_food_menu === 'yes';
            $isAvailable = true;
            $availabilityMessage = '';
            
            if ($isFoodMenu) {
                $currentDate = now()->format('Y-m-d');
                $currentTime = now()->format('H:i:s');
                
                $availableFromDate = $product->available_from_date;
                $availableToDate = $product->available_to_date;
                $availableFromTime = $product->available_from_time;
                $availableToTime = $product->available_to_time;
                
                // Check date availability
                $isDateAvailable = true;
                if ($availableFromDate && $availableToDate) {
                    $isDateAvailable = $currentDate >= $availableFromDate && $currentDate <= $availableToDate;
                }
                
                // Check time availability
                $isTimeAvailable = true;
                if ($availableFromTime && $availableToTime) {
                    $isTimeAvailable = $currentTime >= $availableFromTime && $currentTime <= $availableToTime;
                }
                
                $isAvailable = $isDateAvailable && $isTimeAvailable;
                
                if (!$isAvailable) {
                    $availabilityMessage = "Not available for now";
                }
            }
                        @endphp
                        <div class="product-card" data-category="{{ $categoryId }}" data-price="{{ $productPrice }}" data-rating="{{ $rating }}" data-date="{{ $product->created_at }}">
                            <div class="relative overflow-hidden">
                                     @if(!$isAvailable)
                        <div class="unavailable-overlay">
                            {{ $availabilityMessage }}
                        </div>
                    @endif
                                <a href="{{ $isAvailable ? url('/product/' . $product['slug']) : 'javascript:void(0)' }}">
                                    <img src="{{ $productImage ? asset('/public/storage/'.$productImage) : 'https://via.placeholder.com/300x300?text=' . urlencode($productName) }}" alt="{{ $productName }}" class="w-full h-48 object-cover">
                                 </a>
                                 <div class="absolute top-4 right-4 bg-amber-800 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $categoryName }}
                                </div>
                            </div>
                            <div class="p-5">
                               <a href="{{ $isAvailable ? url('/product/' . $product['slug']) : 'javascript:void(0)' }}" >
                                 <h3 class="text-lg font-semibold coffee-title mb-2">{{ $productName }}</h3>
                                </a>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-amber-500 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($rating))
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-600 text-sm">({{ $reviewCount }})</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-amber-800">{{ $currency->symbol }}{{ number_format($productPrice, 2) }}</span>
                                    <button onclick="{{ $isAvailable ? 'addToCart(' . $product->id . ')' : 'showUnavailableMessage()' }}" 
                                    class="mobile-add-cart-btn bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center space-x-1 font-medium {{ !$isAvailable ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        <i class="fas fa-shopping-cart text-xs"></i>
                                        <span class="text-xs">Add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- No Products Message -->
                <div id="noProducts" class="no-products hidden">
                    <i class="fas fa-search fa-3x mb-4 text-gray-400"></i>
                    <h3 class="text-xl font-semibold mb-2">No products found</h3>
                    <p class="text-gray-600">Try adjusting your filters to find what you're looking for.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Filter Overlay and Panel -->
    <div id="mobileFilterOverlay" class="mobile-filter-overlay fixed inset-0 hidden"></div>
    <div id="mobileFilterPanel" class="mobile-filter-panel fixed top-0 left-0 h-full w-4/5 max-w-sm bg-white overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold coffee-title">Filters</h2>
                <button id="closeMobileFilter" class="text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Mobile Category Filter -->
            <div class="mb-8">
                <h3 class="font-semibold text-lg mb-4 coffee-title">Categories</h3>
                <div id="mobileCategoryFilters">
                    @foreach($categories as $category)
                        @php
                            $categoryName = $category->translation->name ?? $category->name ?? 'Category';
                            $categoryId = $category->id;
                            $productCount = $category->products->where('status', 1)->count() ?? 0;
                        @endphp
                        <div class="checkbox-container">
                            <input type="checkbox" id="mobile-category-{{ $categoryId }}" value="{{ $categoryId }}" data-name="{{ $categoryName }}">
                            <span class="checkmark"></span>
                            <label for="mobile-category-{{ $categoryId }}" class="flex-1 cursor-pointer">{{ $categoryName }}</label>
                            <span class="text-gray-500 text-sm">({{ $productCount }})</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Mobile Rating Filter -->
            <div class="mb-8">
                <h3 class="font-semibold text-lg mb-4 coffee-title">Customer Rating</h3>
                <div class="space-y-2" id="mobileRatingFilters">
                    <div class="checkbox-container">
                        <input type="radio" name="mobile-rating" id="mobile-rating-4" value="4">
                        <span class="checkmark rounded-full"></span>
                        <label for="mobile-rating-4" class="flex-1 cursor-pointer flex items-center">
                            4 Stars & Up
                            <div class="ml-2 flex text-amber-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </label>
                    </div>
                    <div class="checkbox-container">
                        <input type="radio" name="mobile-rating" id="mobile-rating-3" value="3">
                        <span class="checkmark rounded-full"></span>
                        <label for="mobile-rating-3" class="flex-1 cursor-pointer flex items-center">
                            3 Stars & Up
                            <div class="ml-2 flex text-amber-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </label>
                    </div>
                    <div class="checkbox-container">
                        <input type="radio" name="mobile-rating" id="mobile-rating-2" value="2">
                        <span class="checkmark rounded-full"></span>
                        <label for="mobile-rating-2" class="flex-1 cursor-pointer flex items-center">
                            2 Stars & Up
                            <div class="ml-2 flex text-amber-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <button id="applyMobileFilters" class="w-full bg-amber-800 text-white py-3 rounded-lg font-medium mt-4">
                Apply Filters
            </button>
        </div>
    </div>
</section>
    <script>
        // State
        let selectedCategories = [];
        let selectedRating = 0;
        let sortBy = 'newest';

        // DOM Elements
        const productsGridEl = document.getElementById('productsGrid');
        const activeFiltersEl = document.getElementById('activeFilters');
        const noProductsEl = document.getElementById('noProducts');
        const mobileFilterBtn = document.getElementById('mobileFilterBtn');
        const mobileFilterOverlay = document.getElementById('mobileFilterOverlay');
        const mobileFilterPanel = document.getElementById('mobileFilterPanel');
        const closeMobileFilter = document.getElementById('closeMobileFilter');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const sortSelect = document.getElementById('sortSelect');
        const applyMobileFiltersBtn = document.getElementById('applyMobileFilters');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            updateActiveFilters();
        });

        // Filter and sort products
        function filterAndSortProducts() {
            const productCards = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            productCards.forEach(card => {
                const categoryId = card.getAttribute('data-category');
                const rating = parseFloat(card.getAttribute('data-rating'));
                
                // Category filter
                const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(categoryId);
                
                // Rating filter
                const ratingMatch = selectedRating === 0 || rating >= selectedRating;
                
                if (categoryMatch && ratingMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Sort products
            sortProducts();

            // Show/hide no products message
            if (visibleCount === 0) {
                noProductsEl.classList.remove('hidden');
            } else {
                noProductsEl.classList.add('hidden');
            }
        }

        // Sort products
        function sortProducts() {
            const productsGrid = document.getElementById('productsGrid');
            const productCards = Array.from(productsGrid.querySelectorAll('.product-card'));
            
            productCards.sort((a, b) => {
                switch(sortBy) {
                    case 'price_low':
                        return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                    case 'price_high':
                        return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                    case 'rating':
                        return parseFloat(b.getAttribute('data-rating')) - parseFloat(a.getAttribute('data-rating'));
                    case 'newest':
                    default:
                        return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                }
            });
            
            // Re-append sorted products
            productCards.forEach(card => productsGrid.appendChild(card));
        }

        // Update active filters display
        function updateActiveFilters() {
            activeFiltersEl.innerHTML = '';
            
            // Category filters
            selectedCategories.forEach(categoryId => {
                const categoryCheckbox = document.querySelector(`input[value="${categoryId}"]`);
                const categoryName = categoryCheckbox ? categoryCheckbox.getAttribute('data-name') : 'Category';
                
                const filterTag = document.createElement('div');
                filterTag.className = 'active-filter-tag';
                filterTag.innerHTML = `
                    ${categoryName}
                    <button type="button" data-category="${categoryId}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                activeFiltersEl.appendChild(filterTag);
            });
            
            // Rating filter
            if (selectedRating > 0) {
                const ratingTag = document.createElement('div');
                ratingTag.className = 'active-filter-tag';
                ratingTag.innerHTML = `
                    ${selectedRating}+ Stars
                    <button type="button" data-filter="rating">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                activeFiltersEl.appendChild(ratingTag);
            }
            
            // Add event listeners to remove buttons
            activeFiltersEl.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.dataset.category) {
                        removeCategoryFilter(this.dataset.category);
                    } else if (this.dataset.filter === 'rating') {
                        resetRatingFilter();
                    }
                });
            });
        }

        // Remove category filter
        function removeCategoryFilter(categoryId) {
            selectedCategories = selectedCategories.filter(id => id !== categoryId);
            
            // Update checkboxes
            document.querySelectorAll(`input[value="${categoryId}"]`).forEach(checkbox => {
                checkbox.checked = false;
            });
            
            filterAndSortProducts();
            updateActiveFilters();
        }

        // Reset rating filter
        function resetRatingFilter() {
            selectedRating = 0;
            
            document.querySelectorAll('input[name="rating"]').forEach(radio => {
                radio.checked = false;
            });
            
            document.querySelectorAll('input[name="mobile-rating"]').forEach(radio => {
                radio.checked = false;
            });
            
            filterAndSortProducts();
            updateActiveFilters();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Category filter change - apply immediately
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedCategories.push(this.value);
                    } else {
                        selectedCategories = selectedCategories.filter(id => id !== this.value);
                    }
                    filterAndSortProducts();
                    updateActiveFilters();
                });
            });
            
            // Rating filter change - apply immediately
            document.querySelectorAll('input[name="rating"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        selectedRating = parseInt(this.value);
                        filterAndSortProducts();
                        updateActiveFilters();
                    }
                });
            });
            
            // Mobile rating filter change
            document.querySelectorAll('input[name="mobile-rating"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        selectedRating = parseInt(this.value);
                        // Also update desktop radio buttons
                        document.querySelector(`input[name="rating"][value="${this.value}"]`).checked = true;
                    }
                });
            });
            
            // Sort select change
            sortSelect.addEventListener('change', function() {
                sortBy = this.value;
                sortProducts();
            });
            
            // Clear all filters
            clearFiltersBtn.addEventListener('click', function() {
                selectedCategories = [];
                selectedRating = 0;
                
                // Reset all checkboxes and radios
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.checked = false;
                });
                
                filterAndSortProducts();
                updateActiveFilters();
            });
            
            // Mobile filter panel
            mobileFilterBtn.addEventListener('click', openMobileFilterPanel);
            closeMobileFilter.addEventListener('click', closeMobileFilterPanel);
            mobileFilterOverlay.addEventListener('click', closeMobileFilterPanel);
            
            // Apply mobile filters button
            applyMobileFiltersBtn.addEventListener('click', function() {
                filterAndSortProducts();
                updateActiveFilters();
                closeMobileFilterPanel();
            });
        }

        // Open mobile filter panel
        function openMobileFilterPanel() {
            mobileFilterOverlay.classList.remove('hidden');
            mobileFilterPanel.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        // Close mobile filter panel
        function closeMobileFilterPanel() {
            mobileFilterOverlay.classList.add('hidden');
            mobileFilterPanel.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection