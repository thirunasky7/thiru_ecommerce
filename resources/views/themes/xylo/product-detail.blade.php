@extends('themes.xylo.partials.app')

@section('title', 'Thaiyur Shop, Kelambakam - Online Shopping')

@php
    $productImage = optional($product->thumbnail)->image_url ?? null;
    $imageUrl = $productImage ? asset('public/storage/'.$productImage) : asset('public/images/default-product.jpg');
    $currency = activeCurrency();
    $averageRating = round($product->averageRating(), 1);
    $price = $product->primaryVariant->converted_discount_price ?? $product->primaryVariant->converted_price ?? 0;
    $availability = $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
@endphp

<title>{{ $product->translation->name }}</title>
<meta name="description" content="{{ strip_tags($product->translation->description) }}">
<meta name="keywords" content="{{ $product->translation->tags }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:title" content="{{ $product->translation->name }}">
<meta property="og:description" content="{{ strip_tags($product->translation->short_description) }}">
<meta property="og:image" content="{{ $imageUrl }}">
<meta name="twitter:card" content="summary_large_image">

{{-- ✅ Product Structured Data (JSON-LD for Google) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->translation->name }}",
  "image": [
    @foreach ($product->images as $img)
      "{{ asset('public/storage/'.$img->image_url) }}"@if(!$loop->last),@endif
    @endforeach
  ],
  "description": "{{ strip_tags($product->translation->short_description) }}",
  "sku": "{{ $product->sku ?? 'SKU-'.$product->id }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $product->brand->name ?? 'Thaiyur Shop' }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "{{ $currency->code ?? 'INR' }}",
    "price": "{{ number_format($price, 2, '.', '') }}",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "{{ $availability }}"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $averageRating }}",
    "reviewCount": "{{ $product->reviews_count }}"
  },
  "review": [
    @foreach($product->reviews as $review)
    {
      "@type": "Review",
      "author": {
        "@type": "Person",
        "name": "{{ $review->customer->name ?? 'Anonymous' }}"
      },
      "datePublished": "{{ $review->created_at->toDateString() }}",
      "reviewBody": "{{ strip_tags($review->comment) }}",
      "name": "{{ $review->title ?? 'Customer Review' }}",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "{{ $review->rating }}",
        "bestRating": "5",
        "worstRating": "1"
      }
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>


@section('content')
@php $currency = activeCurrency(); @endphp


<div class="main-detail py-8 md:py-12" style="margin-top:80px">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <!-- Product Images -->
            <div class="w-full lg:w-6/12 relative">
                <div class="product-slider">
                    @foreach ($product->images as $image)
                        <div>
                            <img src="{{  asset('/public/storage/'.$image['image_url']) }}" 
                                 alt="{{ $image['name'] }}" 
                                 class="w-full h-auto rounded-lg shadow-sm" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Info -->
            <div class="w-full lg:w-6/12">
               
                <!-- Product Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $product->translation->name }}</h1>
                 <!-- Stock Status -->
                @if ($inStock)
                    <div id="product-stock" class="mb-3 mt-4 inline-block px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                        IN STOCK
                    </div>
                @else
                    <div id="product-stock" class="mb-3 mt-4 inline-block px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                        OUT OF STOCK
                    </div>
                @endif

                 <!-- Ratings -->
                @php
                    $averageRating = round($product->averageRating(), 1);
                @endphp
                <div class="flex items-center mb-4">
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            @elseif ($i - 0.5 == $averageRating)
                                <i class="fa-solid fa-star-half-alt text-yellow-400 text-sm"></i>
                            @else
                                <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm ml-2">({{ $product->averageRating() }} customer reviews)</span>
                </div>

               
          @php
            $primaryVariant = $product->primaryVariant;
            $originalPrice = $primaryVariant->converted_price ?? 0;
            $discountPrice = $primaryVariant->converted_discount_price ?? 0;
         @endphp
                <!-- Price -->
                <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-4">
                        @if($discountPrice && $originalPrice > $discountPrice)
                            <span class="text-gray-400 line-through text-xs">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                            <span class="text-red-600 font-semibold text-sm">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
                        @else
                            <span class="text-gray-800 font-semibold text-sm">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                        @endif
                  
                </h2>
                

                <!-- Description -->
                <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->translation->short_description }}</p>

              

                <!-- Quantity Selector and Cart Button -->
                <div class="cart-actions mt-8 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="quantity flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                        <button 
                            type="button"
                            onclick="changeQty(-1)" 
                            class="w-12 h-12 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors text-gray-600 hover:text-gray-800"
                        >
                            <span class="text-lg font-semibold">-</span>
                        </button>
                        <input 
                            type="number" 
                            id="qty" 
                            value="1" 
                            min="1"
                            class="w-16 h-12 text-center border-x border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white"
                        >
                        <button 
                            type="button"
                            onclick="changeQty(1)" 
                            class="w-12 h-12 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors text-gray-600 hover:text-gray-800"
                        >
                            <span class="text-lg font-semibold">+</span>
                        </button>
                    </div>
                    <button 
                        type="button"
                        class="add-to-cart mobile-add-cart-btn bg-red-600 text-white px-3 py-4 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center space-x-1 font-medium"
                        onclick="addToCartType({{ $product->id }})"
                        id="add-to-cart-btn"
                    >
                        <i class="fas fa-shopping-cart text-xs"></i>
                                <span class="text-xs">Add to Cart</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Product Reviews Section -->
        <div class="mt-16 border-t pt-12">
            @if(isset($product->translation->description))
             <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">Description</h2>
                <div class="">
                <p class="text-gray-600 mb-6 leading-relaxed">
                {!! strip_tags($product->translation->description) !!}</p>
                </div>
                @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">Customer Reviews</h2>
                <button 
                    id="write-review-btn"
                    class="text-xs px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center space-x-1 font-medium focus:ring-offset-2"
                >
                    Write a Review
                </button>
            </div>
            
            <!-- Review Statistics -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/3 text-center mb-6 md:mb-0">
                        <div class="text-5xl font-bold text-gray-900 mb-2">{{ $averageRating }}</div>
                        <div class="flex justify-center space-x-1 mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                @elseif ($i - 0.5 == $averageRating)
                                    <i class="fa-solid fa-star-half-alt text-yellow-400"></i>
                                @else
                                    <i class="fa-regular fa-star text-gray-300"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-gray-600 text-sm">Based on {{ $product->reviews_count }} reviews</p>
                    </div>
                    
                    <div class="md:w-2/3 w-full">
                        @php
                            $ratingDistribution = [];
                            for ($i = 5; $i >= 1; $i--) {
                                $count = $product->reviews->where('rating', $i)->count();
                                $percentage = $product->reviews_count > 0 ? ($count / $product->reviews_count) * 100 : 0;
                                $ratingDistribution[$i] = [
                                    'count' => $count,
                                    'percentage' => $percentage
                                ];
                            }
                        @endphp
                        
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="flex items-center mb-2">
                                <span class="text-sm text-gray-600 w-10">{{ $i }} <i class="fa-solid fa-star text-yellow-400 text-xs"></i></span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden mx-2">
                                    <div 
                                        class="h-full bg-yellow-400 rounded-full" 
                                        style="width: {{ $ratingDistribution[$i]['percentage'] }}%"
                                    ></div>
                                </div>
                                <span class="text-sm text-gray-600 w-10 text-right">{{ $ratingDistribution[$i]['count'] }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            
            <!-- Reviews List -->
            <div id="reviews-list" class="space-y-6">
                @forelse($product->reviews as $review)
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $review->customer->name ?? '' }}</h3>
                                <div class="flex items-center space-x-1 mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                        @else
                                            <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                                        @endif
                                    @endfor
                                    <span class="text-gray-500 text-sm ml-2">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        @if($review->title)
                            <h4 class="font-medium text-gray-900 mb-2">{{ $review->title }}</h4>
                        @endif
                        <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fa-regular fa-comment text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Load More Reviews Button -->
            @if($product->reviews_count > 5)
                <div class="text-center mt-8">
                    <button class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Load More Reviews
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Write a Review</h3>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="review-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <!-- Customer Validation -->
            <div id="customer-validation-section">
                <p class="text-gray-600 mb-4">To submit a review, please verify your customer information.</p>
                
                <div class="mb-4">
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input 
                        type="text" 
                        id="customer_name" 
                        name="customer_name" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter your full name as registered"
                        required
                    >
                    <p id="name-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>
                
                <div class="mb-4">
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input 
                        type="tel" 
                        id="customer_phone" 
                        name="customer_phone" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter your registered phone number"
                        required
                    >
                    <p id="phone-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>
                
                <button 
                    type="button" 
                    id="validate-customer-btn"
                    class="text-xs w-full py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 mb-4"
                >
                    Verify Customer
                </button>
            </div>
            
            <!-- Review Form (Initially Hidden) -->
            <div id="review-form-section" class="hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                    <div class="flex space-x-1" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-regular fa-star text-2xl text-yellow-400 cursor-pointer rating-star" data-rating="{{ $i }}"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="selected-rating" required>
                    <p id="rating-error" class="text-red-500 text-sm mt-1 hidden">Please select a rating</p>
                </div>
                
                <div class="mb-4">
                    <label for="review-title" class="block text-sm font-medium text-gray-700 mb-1">Review Title (Optional)</label>
                    <input 
                        type="text" 
                        id="review-title" 
                        name="title" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Summarize your experience"
                        maxlength="255"
                    >
                </div>
                
                <div class="mb-4">
                    <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-1">Your Review</label>
                    <textarea 
                        id="review-comment" 
                        name="comment" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Share details of your experience with this product"
                        required
                        minlength="10"
                        maxlength="1000"
                    ></textarea>
                    <p id="comment-error" class="text-red-500 text-sm mt-1 hidden">Review must be at least 10 characters long</p>
                </div>
                
                <button 
                    type="submit" 
                    class="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const variantMap = @json($variantMap);
    
    document.addEventListener('DOMContentLoaded', function() {
        // Review Modal Functionality
        const reviewModal = document.getElementById('review-modal');
        const writeReviewBtn = document.getElementById('write-review-btn');
        const closeModalBtn = document.getElementById('close-modal');
        const validateCustomerBtn = document.getElementById('validate-customer-btn');
        const customerValidationSection = document.getElementById('customer-validation-section');
        const reviewFormSection = document.getElementById('review-form-section');
        const reviewForm = document.getElementById('review-form');
        const ratingStars = document.querySelectorAll('.rating-star');
        const selectedRatingInput = document.getElementById('selected-rating');
        
        // Open modal
        writeReviewBtn.addEventListener('click', () => {
            reviewModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });
        
        // Close modal
        closeModalBtn.addEventListener('click', () => {
            reviewModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetReviewForm();
        });
        
        // Close modal when clicking outside
        reviewModal.addEventListener('click', (e) => {
            if (e.target === reviewModal) {
                reviewModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                resetReviewForm();
            }
        });
        
        // Rating stars interaction
        ratingStars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                selectedRatingInput.value = rating;
                document.getElementById('rating-error').classList.add('hidden');
                
                // Update stars display
                ratingStars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                    } else {
                        s.classList.remove('fa-solid');
                        s.classList.add('fa-regular');
                    }
                });
            });
            
            // Hover effect
            star.addEventListener('mouseenter', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                ratingStars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                    }
                });
            });
            
            star.addEventListener('mouseleave', () => {
                const currentRating = parseInt(selectedRatingInput.value) || 0;
                ratingStars.forEach((s, index) => {
                    if (index >= currentRating) {
                        s.classList.remove('fa-solid');
                        s.classList.add('fa-regular');
                    }
                });
            });
        });
        
        // Validate customer
        validateCustomerBtn.addEventListener('click', () => {
            const nameInput = document.getElementById('customer_name');
            const phoneInput = document.getElementById('customer_phone');
            const nameError = document.getElementById('name-error');
            const phoneError = document.getElementById('phone-error');
            
            const name = nameInput.value.trim();
            const phone = phoneInput.value.trim();
            
            // Reset errors
            nameError.classList.add('hidden');
            phoneError.classList.add('hidden');
            
            let isValid = true;
            
            if (!name) {
                nameError.textContent = 'Please enter your name';
                nameError.classList.remove('hidden');
                isValid = false;
            }
            
            if (!phone) {
                phoneError.textContent = 'Please enter your phone number';
                phoneError.classList.remove('hidden');
                isValid = false;
            } else if (!isValidPhone(phone)) {
                phoneError.textContent = 'Please enter a valid phone number';
                phoneError.classList.remove('hidden');
                isValid = false;
            }
            
            if (!isValid) return;
            
            // Show loading state
            validateCustomerBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
            validateCustomerBtn.disabled = true;
            
            // AJAX request to validate customer
            fetch('{{ route("validate-customer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    product_id: {{ $product->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Customer validated, show review form
                    customerValidationSection.classList.add('hidden');
                    reviewFormSection.classList.remove('hidden');
                    
                    // Store customer info for form submission
                    reviewForm.dataset.customerId = data.customer_id;
                    reviewForm.dataset.customerName = data.customer_name;
                } else {
                    phoneError.textContent = data.message || 'Customer not found. Please check your information.';
                    phoneError.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                phoneError.textContent = 'An error occurred. Please try again.';
                phoneError.classList.remove('hidden');
            })
            .finally(() => {
                validateCustomerBtn.innerHTML = 'Verify Customer';
                validateCustomerBtn.disabled = false;
            });
        });
        
        // Submit review form
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate rating
            if (!selectedRatingInput.value) {
                document.getElementById('rating-error').classList.remove('hidden');
                return;
            }
            
            // Validate comment length
            const comment = document.getElementById('review-comment').value.trim();
            if (comment.length < 10) {
                document.getElementById('comment-error').classList.remove('hidden');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('customer_id', this.dataset.customerId);
            formData.append('user_name', this.dataset.customerName);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
            submitBtn.disabled = true;
            
            // AJAX request to submit review
            fetch('{{ route("submit-review") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Review submitted successfully!');
                    reviewModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    resetReviewForm();
                    
                    // Reload page to show new review
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(data.message || 'Failed to submit review');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred. Please try again.');
            })
            .finally(() => {
                submitBtn.innerHTML = 'Submit Review';
                submitBtn.disabled = false;
            });
        });
        
        // Phone validation helper
        function isValidPhone(phone) {
            // Basic phone validation - adjust regex as needed for your country
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            return phoneRegex.test(phone.replace(/\s/g, ''));
        }
        
        // Reset review form
        function resetReviewForm() {
            reviewForm.reset();
            selectedRatingInput.value = '';
            ratingStars.forEach(star => {
                star.classList.remove('fa-solid');
                star.classList.add('fa-regular');
            });
            
            customerValidationSection.classList.remove('hidden');
            reviewFormSection.classList.add('hidden');
            document.getElementById('name-error').classList.add('hidden');
            document.getElementById('phone-error').classList.add('hidden');
            document.getElementById('rating-error').classList.add('hidden');
            document.getElementById('comment-error').classList.add('hidden');
            
            delete reviewForm.dataset.customerId;
            delete reviewForm.dataset.customerName;
        }
        
        // Initialize slick slider (if not already initialized)
        if (typeof $ !== 'undefined' && $.fn.slick) {
            $('.product-slider').slick({
                arrows: true,
                dots: false,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: '<button type="button" class="slick-prev absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200"><i class="fas fa-chevron-left text-gray-700 text-sm"></i></button>',
                nextArrow: '<button type="button" class="slick-next absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200"><i class="fas fa-chevron-right text-gray-700 text-sm"></i></button>',
            });
        }
    });

    function changeQty(amount) {
        let qtyInput = document.getElementById("qty");
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + amount;

        if (newQty < 1) newQty = 1;
        qtyInput.value = newQty;
    }

    function addToCartType(productId) {
        const quantity = parseInt(document.getElementById("qty").value);
        addToCart(productId,quantity);
    }

    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>

<style>
    /* Color circle styles */
    .color-circle.red { background-color: #dc2626; }
    .color-circle.blue { background-color: #2563eb; }
    .color-circle.green { background-color: #16a34a; }
    .color-circle.black { background-color: #000000; }
    .color-circle.white { background-color: #ffffff; border-color: #d1d5db !important; }
    .color-circle.yellow { background-color: #eab308; }
    .color-circle.purple { background-color: #9333ea; }
    .color-circle.pink { background-color: #db2777; }
    .color-circle.gray { background-color: #6b7280; }
    .color-circle.orange { background-color: #ea580c; }
    .color-circle.brown { background-color: #92400e; }
    .color-circle.navy { background-color: #1e3a8a; }

    /* Custom slick slider styles */
    .slick-prev, .slick-next {
        z-index: 20;
    }
    
    .slick-prev:hover, .slick-next:hover {
        background-color: white !important;
        transform: scale(1.1);
    }
    
    /* Hide number input arrows */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Checked state styles */
    input[type="radio"]:checked + label {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    /* Smooth transitions */
    .quantity button, .add-to-cart, label {
        transition: all 0.2s ease-in-out;
    }

    /* Scale animation */
    .scale-125 {
        transform: scale(1.25);
        transition: transform 0.3s ease-in-out;
    }
    
    /* Review modal animations */
    #review-modal {
        transition: opacity 0.3s ease;
    }
    
    #review-modal > div {
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    #review-modal:not(.hidden) > div {
        transform: scale(1);
    }
    
    /* Rating stars hover effect */
    .rating-star:hover {
        transform: scale(1.1);
    }
</style>

@endsection