<div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t z-50 md:hidden">
    <div class="flex justify-between px-6 py-3 text-gray-600">

        <a href="{{ url('/') }}" class="flex flex-col items-center">
            <i class="las la-home text-2xl"></i>
            <span class="text-xs mt-1">Home</span>
        </a>

        <a href="{{ route('categories.index') }}" class="flex flex-col items-center">
            <i class="las la-th-large text-2xl"></i>
            <span class="text-xs mt-1">Categories</span>
        </a>

        <a href="{{ route('cart') }}" class="flex flex-col items-center">
            <i class="las la-shopping-cart text-2xl"></i>
            <span class="text-xs mt-1">Cart</span>
        </a>

        <a href="#" class="flex flex-col items-center">
            <i class="las la-user text-2xl"></i>
            <span class="text-xs mt-1">Account</span>
        </a>

    </div>
</div>
