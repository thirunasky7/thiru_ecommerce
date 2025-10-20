@extends('themes.xylo.layouts.master')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection
@section('content')
@php $currency = activeCurrency(); @endphp
<section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn productinnerbanner">
    <div class="container h-100">
        <div class="row">       
            <div class="col-md-4">
                <div class="breadcrumbs">
                    <a href="#">Home Page</a> <i class="fa fa-angle-right"></i> <a href="#">Headphone</a> <i
                        class="fa fa-angle-right"></i> Espresso decaffeinato
                </div>
            </div>
        </div>
    </div>
</section>
<div class="main-detail pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 position-relative">
                @php /*
                <div class="slider-for">
                    @if (!empty($product->images) && count($product->images))
                        @foreach ($product->images as $image)
                            <div>
                                <img src="{{ Storage::url($image['image_url']) }}" alt="{{ $image['name'] }}">
                            </div>
                        @endforeach
                    @else
                        <p>No images found.</p>
                    @endif
                </div>

                <div class="slider-nav imgnav">
                    <div><img src="assets/images/prodict-detailthumb.png" alt=""></div>
                    <div><img src="assets/images/prodict-detailthumb.png" alt=""></div>
                    <div><img src="assets/images/prodict-detailthumb.png" alt=""></div>
                </div>
                */
                @endphp
                <div class="product-slider">
                    @foreach ($product->images as $image)
                        <div>
                            <img src="{{ Storage::url($image['image_url']) }}" alt="{{ $image['name'] }}" style="width: 100%; height: auto;" />
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="col-md-6 pro-textarea">
                @if ($inStock)
                    <div id="product-stock" class="mb-2 mt-3 btnss">IN STOCK</div>
                @else
                    <div id="product-stock" class="mb-2 mt-3 btnss text-danger">OUT OF STOCK</div>
                @endif
                @php
                    $averageRating = round($product->reviews_avg_rating, 1);
                @endphp
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($averageRating))
                            <i class="fa-solid fa-star text-warning"></i>
                        @elseif ($i - 0.5 == $averageRating)
                            <i class="fa-solid fa-star-half-alt text-warning"></i>
                        @else
                            <i class="fa-regular fa-star text-muted"></i>
                        @endif
                    @endfor
                    <span class="spanstar"> ({{ $product->reviews_count }} customer reviews)</span>
                </div>
                <h1 class="sec-heading">{{ $product->translation->name }}</h1>
                <h2><span id="currency-symbol">{{ $currency->symbol }}</span><span  id="variant-price" >{{ $product->primaryVariant->converted_price ?? 'N/A' }}</span></h2>
                <p>{{ $product->translation->short_description }}</p>




                <div id="product-attributes" class="product-options">
                    @php
                        $groupedAttributes = $product->attributeValues->groupBy(fn($item) => $item->attribute->id);
                    @endphp

                    @foreach ($groupedAttributes as $attributeId => $values)
                        <div class="attribute-options mt-3">
                            <h3>{{ $values->first()->attribute->name }}</h3>
                            <div class="{{ strtolower($values->first()->attribute->name) }}-wrapper">
                                @foreach ($values as $index => $value)
                                    @php
                                        $inputId = strtolower($values->first()->attribute->name) . '-' . $index;
                                    @endphp
                                    <input 
                                        type="radio" 
                                        name="attribute_{{ $attributeId }}" 
                                        id="{{ $inputId }}"
                                        value="{{ $value->id }}"
                                        {{ $index === 0 ? 'checked' : '' }}
                                    >
                                    <label 
                                        for="{{ $inputId }}" 
                                        class="{{ strtolower($values->first()->attribute->name) === 'color' ? 'color-circle ' . strtolower($value->translated_value) : 'size-box' }}"
                                    >
                                    @if(strtolower($values->first()->attribute->name) === 'size')
                                        {{ $value->translated_value }}
                                    @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>




                <!-- Quantity Selector and Cart Button -->
                <div class="cart-actions mt-3 d-flex">
                    <div class="quantity me-4">
                        <button onclick="changeQty(-1)">-</button>
                        <input type="text" id="qty" value="1">
                        <button onclick="changeQty(1)">+</button>
                    </div>
                    <button class="add-to-cart read-more" onclick="addToCart({{ $product->id }}, '{{ $product->product_type }}')">Add to Cart</button>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>  
    <script>
        $(document).ready(function() {
            $('.product-slider').slick({
                arrows: true, // Enable left/right arrows
                dots: false,  // Disable dots (bullets)
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: '<button type="button" class="slick-prev">←</button>',
                nextArrow: '<button type="button" class="slick-next">→</button>',
            });
        });
    </script>

 
    <script>
        const variantMap = @json($variantMap);
    </script>
    <script>    

    $(document).ready(function () {
        const productId = {{ $product->id }};

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

        $('input[type="radio"]').on('change', function () {
            const selectedAttrIds = getSelectedAttributeValueIds();
            const variantId = findMatchingVariantId(selectedAttrIds);

            if (!variantId) {
                alert('Selected variant not available.');
                return;
            }

            $.ajax({
                url: '/get-variant-price',
                type: 'GET',
                data: {
                    variant_id: variantId,
                    product_id: productId
                },
                success: function (response) {
                    if (response.success) {
                        $('#variant-price').text(response.price);
                        $('#product-stock').text(response.stock);
                        $('#currency-symbol').text(response.currency_symbol);

                        if (response.is_out_of_stock) {
                            $('#product-stock').addClass('text-danger');
                        } else {
                            $('#product-stock').removeClass('text-danger');
                        }
                    } else {
                        console.log('Unable to fetch variant price.');
                    }
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        });

        // Trigger change on load to set default variant
        $('input[type="radio"]:checked').trigger('change');
    });

    </script>

    <script>
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

            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    attribute_value_ids: selectedAttributes,
                    product_type: product_type
                })
            })
            .then(response => response.json())
            .then(data => {
                toastr.success(data.message);
                updateCartCount(data.cart);
            })
            .catch(error => console.error("Error:", error));
        }


        function getSelectedVariantId(attributes) {
            // Custom logic to determine the variant ID based on selected attributes (size, color)
            // This is a simplified version. In practice, you'd likely query the backend to determine the exact variant ID
            // based on these attributes.
            return null; // For now, assuming no variant is selected directly
        }

        function updateCartCount(cart) {
            let totalCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById("cart-count").textContent = totalCount;
        }
    </script>
@endsection