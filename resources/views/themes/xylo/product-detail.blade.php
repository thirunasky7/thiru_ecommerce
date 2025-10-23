@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@php $currency = activeCurrency(); @endphp

<section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn productinnerbanner">
    <div class="container mx-auto px-4 h-full">
        <div class="flex flex-wrap">       
            <div class="w-full md:w-4/12">
                <div class="breadcrumbs flex items-center space-x-2 text-sm text-gray-600 flex-wrap">
                    <a href="#" class="hover:text-gray-900 transition-colors">Home Page</a> 
                    <i class="fa fa-angle-right text-xs"></i> 
                    <a href="#" class="hover:text-gray-900 transition-colors">Headphone</a> 
                    <i class="fa fa-angle-right text-xs"></i> 
                    <span class="text-gray-900">Espresso decaffeinato</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="main-detail py-8 md:py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <!-- Product Images -->
            <div class="w-full lg:w-6/12 relative">
                <div class="product-slider">
                    @foreach ($product->images as $image)
                        <div>
                            <img src="{{ Storage::url($image['image_url']) }}" 
                                 alt="{{ $image['name'] }}" 
                                 class="w-full h-auto rounded-lg shadow-sm" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Info -->
            <div class="w-full lg:w-6/12">
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
                    $averageRating = round($product->reviews_avg_rating, 1);
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
                    <span class="text-gray-500 text-sm ml-2">({{ $product->reviews_count }} customer reviews)</span>
                </div>

                <!-- Product Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $product->translation->name }}</h1>
                
                <!-- Price -->
                <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-4">
                    <span id="currency-symbol">{{ $currency->symbol }}</span>
                    <span id="variant-price">{{ $product->primaryVariant->converted_price ?? 'N/A' }}</span>
                </h2>

                <!-- Description -->
                <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->translation->short_description }}</p>

                <!-- Product Attributes -->
                <div id="product-attributes" class="product-options space-y-6">
                    @php
                        $groupedAttributes = $product->attributeValues->groupBy(fn($item) => $item->attribute->id);
                    @endphp

                    @foreach ($groupedAttributes as $attributeId => $values)
                        <div class="attribute-options">
                            <h3 class="font-semibold text-gray-900 mb-3">{{ $values->first()->attribute->name }}</h3>
                            <div class="{{ strtolower($values->first()->attribute->name) }}-wrapper flex flex-wrap gap-3">
                                @foreach ($values as $index => $value)
                                    @php
                                        $inputId = strtolower($values->first()->attribute->name) . '-' . $index;
                                        $isColor = strtolower($values->first()->attribute->name) === 'color';
                                        $isSize = strtolower($values->first()->attribute->name) === 'size';
                                    @endphp
                                    
                                    <div class="relative">
                                        <input 
                                            type="radio" 
                                            name="attribute_{{ $attributeId }}" 
                                            id="{{ $inputId }}"
                                            value="{{ $value->id }}"
                                            {{ $index === 0 ? 'checked' : '' }}
                                            class="absolute opacity-0 -z-10"
                                            data-attribute-id="{{ $attributeId }}"
                                        >
                                        <label 
                                            for="{{ $inputId }}" 
                                            class="cursor-pointer transition-all duration-200 ease-in-out
                                                @if($isColor)
                                                    color-circle w-10 h-10 rounded-full border-2 border-gray-300 hover:border-gray-400 flex items-center justify-center
                                                    {{ strtolower($value->translated_value) }}
                                                @elseif($isSize)
                                                    size-box px-4 py-3 border border-gray-300 rounded-md hover:border-gray-400 hover:bg-gray-50
                                                    text-sm font-medium min-w-12 text-center
                                                @else
                                                    px-4 py-3 border border-gray-300 rounded-md hover:border-gray-400 hover:bg-gray-50
                                                    text-sm font-medium min-w-12 text-center
                                                @endif
                                                peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200"
                                        >
                                            @if($isSize)
                                                {{ $value->translated_value }}
                                            @elseif($isColor)
                                                <!-- Color circle content -->
                                            @else
                                                {{ $value->translated_value }}
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

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
                        class="add-to-cart w-full sm:w-auto px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
                        onclick="addToCart({{ $product->id }}, '{{ $product->product_type }}')"
                        id="add-to-cart-btn"
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        const variantMap = @json($variantMap);
        
        $(document).ready(function() {
            // Initialize slick slider
            $('.product-slider').slick({
                arrows: true,
                dots: false,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: '<button type="button" class="slick-prev absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200"><i class="fas fa-chevron-left text-gray-700 text-sm"></i></button>',
                nextArrow: '<button type="button" class="slick-next absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200"><i class="fas fa-chevron-right text-gray-700 text-sm"></i></button>',
            });

            // Attribute selection functionality
            function getSelectedAttributeValueIds() {
                let selected = [];
                $('#product-attributes input[type="radio"]:checked').each(function () {
                    selected.push(parseInt($(this).val()));
                });
                return selected.sort((a, b) => a - b);
            }

            function findMatchingVariantId(selectedAttrIds) {
                for (const variant of variantMap) {
                    const variantAttrIds = variant.attributes.slice().sort((a, b) => a - b);
                    if (JSON.stringify(variantAttrIds) === JSON.stringify(selectedAttrIds)) {
                        return variant.id;
                    }
                }
                return null;
            }

            // Handle attribute changes
            $('input[type="radio"]').on('change', function () {
                const selectedAttrIds = getSelectedAttributeValueIds();
                const variantId = findMatchingVariantId(selectedAttrIds);

                if (!variantId) {
                    toastr.error('Selected variant not available.');
                    // Reset to first available option
                    $(this).prop('checked', false);
                    $(this).siblings('input[type="radio"]').first().prop('checked', true).trigger('change');
                    return;
                }

                // Show loading state
                const addToCartBtn = $('#add-to-cart-btn');
                const originalText = addToCartBtn.html();
                addToCartBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
                addToCartBtn.prop('disabled', true);

                $.ajax({
                    url: '/get-variant-price',
                    type: 'GET',
                    data: {
                        variant_id: variantId,
                        product_id: {{ $product->id }}
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#variant-price').text(response.price);
                            $('#product-stock').text(response.stock);
                            $('#currency-symbol').text(response.currency_symbol);

                            if (response.is_out_of_stock) {
                                $('#product-stock').removeClass('bg-green-100 text-green-800').addClass('bg-red-100 text-red-800');
                                $('#add-to-cart-btn').prop('disabled', true).text('Out of Stock');
                            } else {
                                $('#product-stock').removeClass('bg-red-100 text-red-800').addClass('bg-green-100 text-green-800');
                                $('#add-to-cart-btn').prop('disabled', false).html(originalText);
                            }
                        } else {
                            console.log('Unable to fetch variant price.');
                            toastr.error('Unable to fetch variant information.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        toastr.error('Something went wrong. Please try again.');
                    },
                    complete: function() {
                        // Re-enable button in case of error
                        addToCartBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Trigger change on load to set default variant
            $('input[type="radio"]:checked').trigger('change');
        });

        function changeQty(amount) {
            let qtyInput = document.getElementById("qty");
            let currentQty = parseInt(qtyInput.value);
            let newQty = currentQty + amount;

            if (newQty < 1) newQty = 1;
            qtyInput.value = newQty;
        }

        function addToCart(productId, product_type) {
    const quantity = parseInt(document.getElementById("qty").value);
    const attributeInputs = document.querySelectorAll('#product-attributes input[type="radio"]:checked');

    let selectedAttributes = [];
    attributeInputs.forEach(input => {
        selectedAttributes.push(parseInt(input.value));
    });

    // Validate attributes are selected
    if (selectedAttributes.length === 0) {
        toastr.error('Please select product options.');
        return;
    }

    // Show loading state
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const originalText = addToCartBtn.innerHTML;
    addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
    addToCartBtn.disabled = true;

    fetch("{{ route('cart.add') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            attribute_value_ids: selectedAttributes,
            product_type: product_type
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            updateCartCount(data.cart_count || data.cart);
            
            // Refresh cart sidebar/dropdown if exists
           
            
            // Update mini cart if exists
            updateMiniCart(data.cart_items);
        } else {
            toastr.error(data.message || 'Failed to add item to cart.');
        }
    })
    .catch(error => {
        console.error("Error:", error);
        toastr.error('An error occurred. Please try again.');
    })
    .finally(() => {
        // Reset button state
        addToCartBtn.innerHTML = originalText;
        addToCartBtn.disabled = false;
    });
}

// Improved cart count update function
function updateCartCount(cartData) {
    let totalCount = 0;
    
    if (typeof cartData === 'number') {
        // If it's already a number (cart_count)
        totalCount = cartData;
    } else if (cartData && typeof cartData === 'object') {
        // Handle different cart data structures
        if (Array.isArray(cartData)) {
            // If it's an array of cart items
            totalCount = cartData.reduce((sum, item) => sum + (item.quantity || 0), 0);
        } else if (cartData.items && Array.isArray(cartData.items)) {
            // If it's a cart object with items array
            totalCount = cartData.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
        } else {
            // If it's a plain object with items
            totalCount = Object.values(cartData).reduce((sum, item) => sum + (item.quantity || 0), 0);
        }
    }
    
    // Update all cart count elements
    const cartCountElements = document.querySelectorAll(".cart-count, #cart-count, [data-cart-count]");
    
    cartCountElements.forEach(element => {
        element.textContent = totalCount;
        
        // Add animation effect
        element.classList.add('scale-125', 'text-blue-600');
        setTimeout(() => {
            element.classList.remove('scale-125', 'text-blue-600');
        }, 300);
    });
    
    console.log('Cart updated. Total items:', totalCount);
}


// Update mini cart items
function updateMiniCart(cartItems) {
    const miniCart = document.getElementById('mini-cart-items');
    if (miniCart && cartItems) {
        // Update mini cart items here based on your HTML structure
        console.log('Mini cart items updated:', cartItems);
    }
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
    </style>

@endsection

