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
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .mobile-product-card {
            min-height: auto;
        }
        .mobile-product-image {
            height: 180px;
        }
        .mobile-product-actions {
            padding: 0.75rem;
        }
        .mobile-add-cart-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }
    }
    
    /* Slick slider customizations */
    .slick-prev, .slick-next {
        z-index: 10;
    }
    .slick-prev { left: 10px; }
    .slick-next { right: 10px; }
    .slick-dots {
        bottom: -40px;
    }
</style>
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