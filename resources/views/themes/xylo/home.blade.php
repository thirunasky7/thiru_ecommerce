@extends('themes.xylo.layouts.master')
<style>

.banner-area {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 75vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.banner-area .container {
    position: relative;
    z-index: 2;
}

.banner-content {
    padding: 2rem 0;
}

.banner-badge {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
    margin-bottom: 1.5rem;
}

.banner-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    color: #2d3748;
    margin-bottom: 1.5rem;
}

.banner-description {
    font-size: 1.3rem;
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 2rem;
    max-width: 90%;
}

.banner-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.btn-shop-now {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-shop-now:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    background: transparent;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-3px);
}

.trust-indicators {
    border-top: 1px solid #e9ecef;
    padding-top: 2rem;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #495057;
    font-size: 0.9rem;
    font-weight: 500;
}

.trust-item i {
    color: #ff6b35;
    font-size: 1.3rem;
}

.banner-image-wrapper {
    position: relative;
    text-align: center;
}

.banner-main-image {
    max-width: 100%;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.banner-main-image:hover {
    transform: translateY(-5px);
}

.floating-badge {
    position: absolute;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    animation: float 3s ease-in-out infinite;
}

.discount-badge {
    top: 30px;
    left: 30px;
    text-align: center;
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
}

.discount-badge span {
    display: block;
    font-size: 0.8rem;
    opacity: 0.9;
}

.discount-badge strong {
    font-size: 1.3rem;
}

.rating-badge {
    bottom: 30px;
    right: 30px;
    text-align: center;
}

.rating-badge .stars {
    color: #ffc107;
    margin-bottom: 0.5rem;
}

.rating-badge span {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .banner-area {
        min-height: 85vh;
        padding: 2rem 0;
    }
    
    .banner-title {
        font-size: 2.2rem;
    }
    
    .banner-description {
        font-size: 1.1rem;
        max-width: 100%;
    }
    
    .banner-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-shop-now,
    .btn-outline-secondary {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
    
    .trust-indicators .row {
        justify-content: center;
        text-align: center;
    }
    
    .floating-badge {
        position: relative;
        top: auto;
        left: auto;
        right: auto;
        bottom: auto;
        margin: 1rem auto;
        display: inline-block;
    }
    
    .banner-main-image {
        margin-bottom: 2rem;
    }
}

/* Tablet Styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .banner-area {
        min-height: 70vh;
    }
    
    .banner-title {
        font-size: 2.8rem;
    }
    
    .banner-description {
        font-size: 1.2rem;
    }
}

/* Large Desktop Styles */
@media (min-width: 1400px) {
    .banner-area {
        min-height: 75vh;
    }
    
    .banner-title {
        font-size: 4rem;
    }
    
    .banner-description {
        font-size: 1.4rem;
    }
}
</style>

@section('content')
    @php $currency = activeCurrency(); @endphp
    
 <section class="banner-area animate__animated animate__fadeIn">
    <div class="container h-100">
        @if($banners->count() > 0)
            @php $banner = $banners->first(); @endphp
            <div class="row align-items-center h-100">
                <!-- Content Column - Always first on mobile -->
                <div class="col-12 col-lg-6 order-2 order-lg-1">
                    <div class="banner-content">
                        @if($banner->translation && $banner->translation->subtitle)
                            <span class="banner-badge">{{ $banner->translation->subtitle }}</span>
                        @endif
                        
                        <h1 class="banner-title">
                            {{ $banner->translation ? $banner->translation->title : $banner->title }}
                        </h1>
                        
                        <p class="banner-description">
                            {{ $banner->translation ? ($banner->translation->description ?? 'Explore the biggest variety of sneakers, shoes, and streetwear trends.') : 'Explore the biggest variety of sneakers, shoes, and streetwear trends.' }}
                        </p>
                        
                        <div class="banner-actions">
                            <a href="{{ route('products.index') }}" class="btn-shop-now">
                                <span>Shop Now</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            
                            @if($banner->translation && $banner->translation->button_text)
                                <a href="{{ $banner->translation->button_link ?? '#' }}" class="btn-outline-secondary">
                                    {{ $banner->translation->button_text }}
                                </a>
                            @endif
                        </div>

                        <div class="trust-indicators">
                            <div class="row g-3">
                                <div class="col-12 col-sm-4">
                                    <div class="trust-item">
                                        <i class="fas fa-shipping-fast"></i>
                                        <span>Free Delivery</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="trust-item">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Secure Payment</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="trust-item">
                                        <i class="fas fa-headset"></i>
                                        <span>24/7 Support</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Column - Always second on mobile -->
                <div class="col-12 col-lg-6 order-1 order-lg-2">
                    <div class="banner-image-wrapper">
                        @php
                            $bannerImage = $banner->translation->image_url ?? $banner->image_url ?? null;
                            $bannerAlt = $banner->translation ? $banner->translation->title : $banner->title;
                            $placeholderUrl = 'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($bannerAlt);
                        @endphp
                        
                        <img src="{{ $bannerImage ? Storage::url($bannerImage) : $placeholderUrl }}" 
                             class="banner-main-image" 
                             alt="{{ $bannerAlt }}"
                             onerror="this.src='{{ $placeholderUrl }}'">
                        
                        <!-- Floating Elements -->
                        <div class="floating-badge discount-badge">
                            <span>Sale</span>
                            <strong>50% OFF</strong>
                        </div>
                        
                        <div class="floating-badge rating-badge">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span>4.5/5 Rating</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Fallback banner if no banners exist -->
            <div class="row align-items-center h-100">
                <div class="col-12 col-lg-6 order-2 order-lg-1">
                    <div class="banner-content">
                        <span class="banner-badge">Premium Quality</span>
                        
                        <h1 class="banner-title">
                            Discover Authentic Flavors & Fresh Ingredients
                        </h1>
                        
                        <p class="banner-description">
                            Explore our premium collection of spices, herbs, and fresh ingredients sourced directly from trusted farms. Experience the true taste of tradition.
                        </p>
                        
                        <div class="banner-actions">
                            <a href="{{ route('products.index') }}" class="btn-shop-now">
                                <span>Shop Now</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            
                            <a href="{{ route('categories.index') }}" class="btn-outline-secondary">
                                Browse Categories
                            </a>
                        </div>

                        <div class="trust-indicators">
                            <div class="row g-3">
                                <div class="col-12 col-sm-4">
                                    <div class="trust-item">
                                        <i class="fas fa-shipping-fast"></i>
                                        <span>Free Delivery</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="trust-item">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Secure Payment</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="trust-item">
                                        <i class="fas fa-headset"></i>
                                        <span>24/7 Support</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 order-1 order-lg-2">
                    <div class="banner-image-wrapper">
                        <img src="https://via.placeholder.com/800x600/ff6b35/ffffff?text=Premium+Spices+%26+Ingredients" 
                             class="banner-main-image" 
                             alt="Premium Spices and Ingredients">
                        
                        <div class="floating-badge discount-badge">
                            <span>Sale</span>
                            <strong>50% OFF</strong>
                        </div>
                        
                        <div class="floating-badge rating-badge">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span>4.5/5 Rating</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

    <!-- Categories Section -->
    <section class="meesho-categories py-4 animate-on-scroll">
        <div class="container">
            <div class="section-header mb-4">
                <h2 class="section-title">Explore Popular Categories</h2>
                <p class="section-subtitle">Shop from our wide range of categories</p>
            </div>
            
            <div class="row g-2 g-md-3">
                @foreach($categories as $category)
                @php
                    $categoryName = $category->translation->name ?? $category->name ?? 'Category';
                    $categoryImage = $category->translation->image_url ?? $category->image_url ?? null;
                    $categorySlug = $category->slug ?? '#';
                @endphp
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="category-card h-100">
                        <!-- route('categories.show', $categorySlug)  -->
                        <a href="#" class="category-link d-block h-100 text-decoration-none">
                            <div class="category-image-container">
                                <img src="{{ $categoryImage ? Storage::url($categoryImage) : 'https://via.placeholder.com/200x200/6c757d/ffffff?text=' . urlencode($categoryName) }}" 
                                     alt="{{ $categoryName }}"
                                     class="category-image img-fluid"
                                     onerror="this.src='https://via.placeholder.com/200x200/6c757d/ffffff?text='+encodeURIComponent('{{ $categoryName }}')">
                            </div>
                            <div class="category-info">
                                <h3 class="category-name">{{ \Illuminate\Support\Str::limit($categoryName, 20) }}</h3>
                                <span class="category-shop-now">Shop Now <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('categories.index') }}" class="view-all-btn">
                    View All Categories
                    <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="trending-products py-4 animate-on-scroll">
        <div class="container">
            <h1 class="sec-heading">Trending Products</h1>

            @if($products->count() > 0)
                <div class="row g-3">
                    @foreach ($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card h-100 d-flex flex-column">
                            <div class="product-img flex-grow-0">
                                @php
                                    $productImage = optional($product->thumbnail)->image_url ?? null;
                                    $productName = $product->translation->name ?? $product->name ?? 'Product Name Not Available';
                                @endphp
                                <img src="{{ $productImage ? Storage::url($productImage) : 'https://via.placeholder.com/400x400/ffffff/007bff?text=' . urlencode($productName) }}" 
                                    alt="{{ $productName }}"
                                    class="img-fluid w-100"
                                    onerror="this.src='https://via.placeholder.com/400x400/ffffff/007bff?text='+encodeURIComponent('{{ $productName }}')">
                                <button class="wishlist-btn" data-product-id="{{ $product->id }}">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </div>
                            <div class="product-info flex-grow-1 d-flex flex-column">
                                <div class="top-info">
                                    <div class="reviews">
                                        <i class="fa-solid fa-star"></i> ({{ $product->reviews_count ?? 0 }})
                                    </div>
                                </div>
                                <div class="bottom-info mt-auto">
                                    <div class="left flex-grow-1">
                                        <h3 class="h6 mb-1">
                                            <a href="{{ route('product.show', $product->slug) }}" class="product-title">
                                                {{ \Illuminate\Support\Str::limit($productName, 35) }}
                                            </a>
                                        </h3>
                                        <p class="price mb-0">
                                            @php
                                                $primaryVariant = $product->primaryVariant;
                                                $originalPrice = $primaryVariant->converted_price ?? 0;
                                                $discountPrice = $primaryVariant->converted_discount_price ?? 0;
                                            @endphp
                                            
                                            @if($discountPrice)
                                                <span class="original has-discount">
                                                    {{ $currency->symbol }}{{ number_format($originalPrice, 2) }}
                                                </span>
                                                <span class="discount"> 
                                                    {{ $currency->symbol }}{{ number_format($discountPrice, 2) }}
                                                </span>
                                            @else
                                                <span class="original">
                                                    {{ $currency->symbol }}{{ number_format($originalPrice, 2) }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <button class="cart-btn" onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-bag"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No trending products available at the moment.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Sale Banner -->
    <section class="sale-banner py-4 animate-on-scroll">
        <div class="container">
            <img src="{{ asset('assets/images/homesale-banner.png') }}" alt="Sale Banner" 
                 class="img-fluid"
                 onerror="this.src='https://via.placeholder.com/1200x300/dc3545/ffffff?text=Special+Sale+Up+To+50%25+Off'">
        </div>
    </section>

    <!-- Featured Products -->
    <section class="products-home py-4 animate-on-scroll">
        <div class="container">
            <h1 class="sec-heading">Featured Products</h1>
            <div class="row g-3">
                @foreach ($products->take(8) as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card h-100 d-flex flex-column">
                        <div class="product-img flex-grow-0">
                            @php
                                $productImage = optional($product->thumbnail)->image_url ?? null;
                                $productName = $product->translation->name ?? $product->name ?? 'Product Name Not Available';
                            @endphp
                            <img src="{{ $productImage ? Storage::url($productImage) : 'https://via.placeholder.com/400x400/ffffff/007bff?text=' . urlencode($productName) }}" 
                                 alt="{{ $productName }}"
                                 class="img-fluid w-100"
                                 onerror="this.src='https://via.placeholder.com/400x400/ffffff/007bff?text='+encodeURIComponent('{{ $productName }}')">
                            <button class="wishlist-btn" data-product-id="{{ $product->id }}">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                        <div class="product-info flex-grow-1 d-flex flex-column">
                            <div class="top-info">
                                <div class="reviews">
                                    <i class="fa-solid fa-star"></i> ({{ $product->reviews_count ?? 0 }})
                                </div>
                            </div>
                            <div class="bottom-info mt-auto">
                                <div class="left flex-grow-1">
                                    <h3 class="h6 mb-1">
                                        <a href="{{ route('product.show', $product->slug) }}" class="product-title">
                                            {{ \Illuminate\Support\Str::limit($productName, 35) }}
                                        </a>
                                    </h3>
                                    <p class="price mb-0">
                                        @php
                                            $primaryVariant = $product->primaryVariant;
                                            $originalPrice = $primaryVariant->converted_price ?? 0;
                                            $discountPrice = $primaryVariant->converted_discount_price ?? 0;
                                        @endphp
                                        
                                        @if($discountPrice)
                                            <span class="original has-discount">
                                                {{ $currency->symbol }}{{ number_format($originalPrice, 2) }}
                                            </span>
                                            <span class="discount"> 
                                                {{ $currency->symbol }}{{ number_format($discountPrice, 2) }}
                                            </span>
                                        @else
                                            <span class="original">
                                                {{ $currency->symbol }}{{ number_format($originalPrice, 2) }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <button class="cart-btn" onclick="addToCart({{ $product->id }})">
                                    <i class="fa fa-shopping-bag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="view-button text-center mt-4">
                <a href="#" class="read-more">VIEW ALL</a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose-us py-4 animate-on-scroll">
        <div class="container">
            <h1 class="sec-heading">Why Choose Us</h1>
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/choose-icon1.png') }}" alt="Fast Delivery"
                                 onerror="this.src='https://via.placeholder.com/80x80/ffffff/007bff?text=🚚'">
                        </div>
                        <h3>Fast Delivery</h3>
                        <p>Quick and reliable delivery services to get your products to you faster.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/choose-icon2.png') }}" alt="24/7 Support"
                                 onerror="this.src='https://via.placeholder.com/80x80/ffffff/007bff?text=💬'">
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Round-the-clock customer support to assist you whenever you need.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <img src="{{ asset('assets/images/choose-icon3.png') }}" alt="High Ratings"
                                 onerror="this.src='https://via.placeholder.com/80x80/ffffff/007bff?text=⭐'">
                        </div>
                        <h3>4.9 Ratings</h3>
                        <p>Highly rated by thousands of satisfied customers worldwide.</p>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function addToCart(productId) {
        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            toastr.success(data.message || "Product added to cart successfully!", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000
            });
            if(data.cart) {
                updateCartCount(data.cart);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            toastr.error("Failed to add product to cart");
        });
    }

    function updateCartCount(cart) {
        let totalCount = Object.values(cart).reduce((sum, item) => sum + (item.quantity || 0), 0);
        const cartCountElement = document.getElementById("cart-count");
        if(cartCountElement) {
            cartCountElement.textContent = totalCount;
        }
    }

    // Mobile menu and touch interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Add touch-friendly interactions
        document.querySelectorAll('.product-card, .category-card').forEach(card => {
            card.style.cursor = 'pointer';
        });

        // Handle wishlist buttons
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const productId = this.getAttribute('data-product-id');

                fetch('/customer/wishlist', {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = '/customer/login';
                    } else if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Something went wrong');
                    }
                })
                .then(data => {
                    if (data?.message) {
                        toastr.success(data.message, {
                            closeButton: true,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Failed to update wishlist');
                });
            });
        });

        // Add intersection observer for animations
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            document.querySelectorAll('.animate-on-scroll').forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(element);
            });
        }
    });
</script>
@endsection