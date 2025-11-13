
  @foreach ($products as $product)
            @php
                $productImage = optional($product->thumbnail)->image_url ?? null;
                $productName = $product->translation->name ?? $product->name ?? 'Product';
                $primaryVariant = $product->primaryVariant;
                $originalPrice = $primaryVariant->converted_price ?? 0;
                $discountPrice = $primaryVariant->converted_discount_price ?? 0;
                $averageRating = round($product->reviews_avg_rating ?? 4.5, 1);
                $reviewCount = $product->reviews_count ?? 0;
                
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