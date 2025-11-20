  <!-- ✅ MOBILE BOTTOM NAV -->

@php
    $currentRoute = Route::currentRouteName();
    $currentPath = request()->path();
@endphp
  <nav class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-inner flex justify-around items-center py-2 lg:hidden">
    <a href="{{ url('/home')}}" class="flex flex-col items-center {{ $currentPath === 'home' || $currentPath === '/' ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }}">
    <i data-feather="home" class="w-5 h-5"></i>
    <span class="text-xs mt-1">Home</span>
    </a>
    <a href="{{ url('/cart')}}" class="flex flex-col items-center {{ str_starts_with($currentPath, 'products') ? 'text-red-600' : 'text-gray-600 hover:text-red-600' }}">
        <i data-feather="shopping-bag" class="w-5 h-5"></i>
        <span class="text-xs mt-1">Cart</span>
    </a>

    <!-- <a href="{{ url('/my-orders')}}" class="flex flex-col items-center text-gray-600 hover:text-red-600">
      <i data-feather="package" class="w-5 h-5"></i>
      <span class="text-xs mt-1">Orders</span>
    </a>
    <a href="{{ url('/accounts')}}" class="flex flex-col items-center text-gray-600 hover:text-red-600">
      <i data-feather="user" class="w-5 h-5"></i>
      <span class="text-xs mt-1">Profile</span>
    </a> -->
  </nav>
<!-- FOOTER -->
<footer class="bg-gray-800 text-white py-6 mt-10 hidden lg:block">
  <div class="container mx-auto text-center text-sm">
    © {{ date('Y') }} MyStore. All rights reserved.
  </div>
</footer>

     <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 
 <script>
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "100",
            "hideDuration": "1000",
            "timeOut": "1000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        // Example usage
        function showSuccess(message) {
            toastr.success(message);
        }
        
        function showError(message) {
            toastr.error(message);
        }
        
        function showWarning(message) {
            toastr.warning(message);
        }
        
        function showInfo(message) {
            toastr.info(message);
        }
    </script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update cart counts in real-time
    function updateCartCount() {
        const cartCount = {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }};
        document.querySelectorAll('#cart-count, #cart-count-desktop').forEach(element => {
            element.textContent = cartCount;
        });
    }

    // Search functionality with debounce
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = e.target.value.trim();
                if (query.length > 2) {
                    fetchSearchSuggestions(query);
                } else {
                    hideSearchSuggestions();
                }
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                hideSearchSuggestions();
            }
        });
    }

    function fetchSearchSuggestions(query) {
        // Add your search API call here
        console.log('Searching for:', query);
        // showSearchSuggestions(results);
    }

    function showSearchSuggestions(results) {
        searchSuggestions.classList.remove('d-none');
        // Populate suggestions
    }

    function hideSearchSuggestions() {
        searchSuggestions.classList.add('d-none');
    }

    // Mobile menu enhancements
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        mobileMenu.addEventListener('show.bs.offcanvas', function() {
            document.body.style.overflow = 'hidden';
        });

        mobileMenu.addEventListener('hidden.bs.offcanvas', function() {
            document.body.style.overflow = '';
        });
    }

    // Touch enhancements for mobile
    if ('ontouchstart' in window) {
        document.querySelectorAll('.nav-link, .dropdown-toggle').forEach(element => {
            element.style.cursor = 'pointer';
        });
    }
});
// Update cart count in header
function updateCartCount(cart) {
    let totalCount = 0;
    if (cart && typeof cart === 'object') {
        totalCount = Object.values(cart).reduce((sum, item) => sum + (item.quantity || 0), 0);
    }
    
    const cartCountElement = document.getElementById("cart-count");
    if (cartCountElement) {
        cartCountElement.textContent = totalCount;
        cartCountElement.classList.add('scale-125');
        setTimeout(() => {
            cartCountElement.classList.remove('scale-125');
        }, 300);
    }
}



function updateCartCount(cart) {
  let totalCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    $('#cart-count').text(totalCount);
  document.getElementById("cart-count").textContent = totalCount;
}


</script>

   