<!-- HEADER -->
<header class="bg-white shadow-md fixed w-full top-0 z-50">
  <div class="container mx-auto px-4 py-3 flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <button id="menu-btn" class="lg:hidden">
        <i data-feather="menu" class="w-6 h-6"></i>
      </button>
      <a href="#" class="text-2xl font-bold text-red-600">MyStore</a>
    </div>
    <div class="hidden lg:flex space-x-6 font-medium">
      <a href="{{ url('/home')}}" class="hover:text-blue-600">Home</a>
      <a href="{{ url('/products')}}" class="hover:text-blue-600">Products</a>
      <a href="{{ url('/about-us')}}" class="hover:text-blue-600">About Us</a>
      <a href="{{ url('/services')}}}" class="hover:text-blue-600">Services</a>
    </div>
    <div class="flex items-center space-x-4">
      <i data-feather="search" class="w-5 h-5 cursor-pointer"></i>
      <i data-feather="heart" class="w-5 h-5 cursor-pointer"></i>
      <a href="{{ route('cart.view') }}" class="relative inline-block text-gray-800 me-3">
        <!-- Cart Icon -->
        <i data-feather="shopping-cart" class="w-6 h-6 cursor-pointer"></i>
        
        <!-- Cart Count Badge -->
        <span id="cart-count"
                class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-semibold rounded-full px-1.5 py-0.5">
            {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
        </span>
        </a>
    </div>
  </div>
  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden bg-white border-t border-gray-200 absolute w-full left-0 top-full shadow-lg">
    <nav class="flex flex-col p-4 space-y-3 font-medium">
      <a href="{{ url('/home')}}" class="hover:text-blue-600">Home</a>
      <a href="{{ url('/products')}}" class="hover:text-blue-600">Products</a>
      <a href="{{ url('/about-us')}}" class="hover:text-blue-600">About Us</a>
      <a href="{{ url('/services')}}}" class="hover:text-blue-600">Services</a>
    </nav>
  </div>
</header>

<!-- OVERLAY for closing menu -->
<div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-25 z-40"></div>