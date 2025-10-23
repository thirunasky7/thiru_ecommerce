  <!-- FOOTER -->
  <footer class="bg-gray-800 text-white py-6 mt-10 hidden lg:block">
    <div class="container mx-auto text-center text-sm">
      © 2025 MyStore. All rights reserved.
    </div>
  </footer>

  <!-- ✅ MOBILE BOTTOM NAV -->
  <nav class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-inner flex justify-around items-center py-2 lg:hidden">
    <a href="#" class="flex flex-col items-center text-blue-600">
      <i data-feather="home" class="w-5 h-5"></i>
      <span class="text-xs mt-1">Home</span>
    </a>
    <a href="#" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
      <i data-feather="shopping-bag" class="w-5 h-5"></i>
      <span class="text-xs mt-1">Products</span>
    </a>
    <a href="#" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
      <i data-feather="package" class="w-5 h-5"></i>
      <span class="text-xs mt-1">Orders</span>
    </a>
    <a href="#" class="flex flex-col items-center text-gray-600 hover:text-blue-600">
      <i data-feather="user" class="w-5 h-5"></i>
      <span class="text-xs mt-1">Account</span>
    </a>
  </nav>

  <script>
    feather.replace();

    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('overlay');

    // Toggle menu
    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      overlay.classList.toggle('hidden');
    });

    // Close menu when clicking outside
    overlay.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
      overlay.classList.add('hidden');
    });
  </script>