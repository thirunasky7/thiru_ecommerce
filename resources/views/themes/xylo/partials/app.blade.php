<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'MyStore - Online Shopping')</title>

  <!-- ✅ Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- ✅ Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 relative min-h-screen pb-16">

  @include('themes.xylo.partials.header')

  <main>
    @yield('content')
  </main>

  @include('themes.xylo.partials.footer')

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
    if(menuBtn) {
      menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        overlay.classList.toggle('hidden');
      });
    }

    // Close menu when clicking outside
    if(overlay) {
      overlay.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        overlay.classList.add('hidden');
      });
    }
  </script>

</body>
</html>