@extends('themes.xylo.layouts.master')


@section('content')
    @php $currency = activeCurrency(); @endphp
 <section class="hero-banner" style="background:#ff6d05;color:white;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="hero-badge">{{ $banner->translation ? $banner->translation->title : $banner->title }}</span>
                    <h1 class="hero-title">{{ $banner->translation ? ($banner->translation->description ?? 'Taste Your Favorite foods and snacks') : '' }}</h1>
                    <p class="hero-subtitle">
                        @if($banner->translation && $banner->translation->subtitle)
                         {{ $banner->translation->subtitle }}
                        @endif
                        <div class="hero-actions">
                        <a href="#" class="btn btn-light btn-lg me-2">Shop Now</a>
                        <a href="#" class="btn btn-outline-light btn-lg">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                     @php
                            $bannerImage = $banner->translation->image_url ?? $banner->image_url ?? null;
                            $bannerAlt = $banner->translation ? $banner->translation->title : $banner->title;
                            $placeholderUrl = 'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($bannerAlt);
                        @endphp
                        
                        <img src="{{ $bannerImage ? Storage::url($bannerImage) : $placeholderUrl }}" 
                             class="img-fluid rounded-3" 
                             alt="{{ $bannerAlt }}"
                             onerror="this.src='{{ $placeholderUrl }}'">
                        
                </div>
            </div>
        </div>
    </section>


  <!-- Categories Section -->
    <section class="container mb-5">
        <h2 class="section-title">Shop By Category</h2>
        <div class="row">
            @foreach($categories as $category)
                @php
                    $categoryName = $category->translation->name ?? $category->name ?? 'Category';
                    $categoryImage = $category->translation->image_url ?? $category->image_url ?? null;
                    $categorySlug = $category->slug ?? '#';
                @endphp
                <div class="col-md-3 col-sm-6">
                    <div class="card category-card">
                        <img alt="{{ $categoryName }}" src="{{ $categoryImage ? Storage::url($categoryImage) : 'https://via.placeholder.com/200x200/6c757d/ffffff?text=' . urlencode($categoryName) }}" class="card-img-top category-img">
                        <div class="card-body text-center">
                            <h5 class="category-name">{{ $categoryName }}</h5>
                            <a href="#" class="btn btn-outline-primary btn-sm">Buy Now</a>
                        </div>
                    </div>
                </div>
              @endforeach
        </div>
    </section>
  <section class="container mb-5">
        <h2 class="section-title">Featured Products</h2>
        <div class="row">
              @foreach ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card">
                    <div class="product-img">
                          @php
                                $productImage = optional($product->thumbnail)->image_url ?? null;
                                $productName = $product->translation->name ?? $product->name ?? 'Product Name Not Available';
                               $primaryVariant = $product->primaryVariant;
                                $originalPrice = $primaryVariant->converted_price ?? 0;
                                $discountPrice = $primaryVariant->converted_discount_price ?? 0;
                            @endphp
                        <img src="{{ $productImage ? Storage::url($productImage) : 'https://via.placeholder.com/400x400/ffffff/007bff?text=' . urlencode($productName) }}" class="card-img-top" alt="{{$productName}}">
                        <button class="wishlist-btn">
                            <i class="far fa-heart"></i>
                        </button>
                        <span class="discount-badge">-20%</span>
                    </div>
                    <div class="card-body">
                        <a href="#" class="product-title">{{ \Illuminate\Support\Str::limit($productName, 35) }}</a>
                        <div class="rating mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="ms-1 text-muted">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                 @if($discountPrice)
                                            <span class="original-price">
                                                {{ $currency->symbol }}{{ number_format($originalPrice, 2) }}
                                            </span>
                                            <span class="product-price"> 
                                                {{ $currency->symbol }}{{ number_format($discountPrice, 2) }}
                                            </span>
                                        @else
                                            <span class="original-price">
                                                {{ $currency->symbol }}{{ number_format($originalPrice, 2) }}
                                            </span>
                                        @endif
                            </div>
                            <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
</div>
</section>

    <!-- Why Choose Us -->
    <section class="why-choose-us py-4 animate-on-scroll" style="background:#ff6d05;color:white;">
        <div class="container">
            <h1 class="sec-heading">Why Choose Us</h1>
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="feature-box">
                        <h3>Fast Delivery</h3>
                        <p>Quick and reliable delivery services to get your products to you faster.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="feature-box">
                        <h3>24/7 Support</h3>
                        <p>Round-the-clock customer support to assist you whenever you need.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="feature-box">
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