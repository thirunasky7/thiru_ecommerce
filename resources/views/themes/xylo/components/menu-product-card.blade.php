@php
$productImage = optional($product->thumbnail)->image_url ?? null;
$productName = $product->translation->name ?? $product->name;
$primaryVariant = $product->primaryVariant;

$originalPrice = $primaryVariant->converted_price ?? $product->price ?? 0;
$discountPrice = $primaryVariant->converted_discount_price ?? $product->discount_price ?? 0;

$isAvailable = !($product->is_coming_soon ?? false);
$availabilityMessage = $isAvailable ? '' : 'Coming Soon';

$displayPrice = $discountPrice && $originalPrice > $discountPrice ? $discountPrice : $originalPrice;
$hasDiscount = $discountPrice && $originalPrice > $discountPrice;
$discountPercent = $hasDiscount ? round((($originalPrice - $discountPrice) / $originalPrice) * 100) : 0;
@endphp

<div class="flex gap-4 bg-white border rounded-2xl shadow-sm p-3 relative {{ !$isAvailable ? 'opacity-60' : '' }}">

    @if(!$isAvailable)
    <div class="absolute inset-0 bg-white/80 backdrop-blur flex items-center justify-center rounded-xl">
        <div class="text-center text-sm text-gray-500">
            <i class="fa fa-clock mb-1"></i>
            <p>{{ $availabilityMessage }}</p>
        </div>
    </div>
    @endif

    <!-- Image -->
    <div class="relative w-20 h-20 flex-shrink-0">
        @if($productImage)
            <img src="{{ asset('storage/'.$productImage) }}"
                 class="w-full h-full rounded-xl object-cover">
        @else
            <div class="w-full h-full bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                <i class="fa fa-image"></i>
            </div>
        @endif

        @if($hasDiscount)
            <span class="absolute -top-1 -left-1 bg-red-600 text-white text-[10px] px-1.5 py-0.5 rounded-full">
                -{{ $discountPercent }}%
            </span>
        @endif
    </div>

    <!-- Info -->
    <div class="flex-1">
        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $productName }}</h4>

        <p class="text-gray-500 text-[11px] line-clamp-2 mb-2">
            {{ trim(preg_replace('/\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($product->translation->description ?? '')))) }}
        </p>

        <div class="flex justify-between items-center">
            <div>
                @if($hasDiscount)
                    <span class="line-through text-xs text-gray-400">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span><br>
                    <span class="text-green-600 font-bold text-base">{{ $currency->symbol }}{{ number_format($discountPrice, 2) }}</span>
                @else
                    <span class="text-gray-900 font-bold text-base">{{ $currency->symbol }}{{ number_format($originalPrice, 2) }}</span>
                @endif
            </div>

            <button
                onclick="{{ $isAvailable ? 'addToCart('.$product->id.')' : 'showUnavailableMessage()' }}"
                class="bg-orange-600 text-white px-4 py-1.5 rounded-xl text-sm font-semibold 
                       {{ $isAvailable ? 'bg-orange-500 text-white active:scale-95' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                <i class="fa fa-plus text-xs"></i> Add
            </button>
        </div>
    </div>
</div>
