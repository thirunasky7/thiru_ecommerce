<!-- resources/views/layouts/nav.blade.php -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <!-- Logo -->
      <a href="#" class="text-2xl font-bold text-blue-600">MyStore</a>

      <!-- Desktop Menu -->
      <div class="hidden md:flex space-x-6">
        <a href="#" class="hover:text-blue-600">Home</a>
        <a href="#" class="hover:text-blue-600">Shop</a>
        <a href="#" class="hover:text-blue-600">Offers</a>
        <a href="#" class="hover:text-blue-600">Categories</a>
        <a href="#" class="hover:text-blue-600">Contact</a>
      </div>

      <!-- Login Button -->
      <div class="hidden md:block">
        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          Login
        </a>
      </div>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button id="menu-toggle" class="text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Drawer Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
    <div class="px-4 py-3 space-y-2">
      <a href="#" class="block text-gray-700 hover:text-blue-600">Home</a>
      <a href="#" class="block text-gray-700 hover:text-blue-600">Shop</a>
      <a href="#" class="block text-gray-700 hover:text-blue-600">Offers</a>
      <a href="#" class="block text-gray-700 hover:text-blue-600">Categories</a>
      <a href="#" class="block text-gray-700 hover:text-blue-600">Contact</a>
      <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        Login
      </a>
    </div>
  </div>

  <script>
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    toggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>
</nav>
