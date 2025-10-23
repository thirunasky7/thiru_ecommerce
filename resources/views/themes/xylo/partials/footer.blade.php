<!-- FOOTER -->
<footer class="bg-gray-800 text-white py-6 mt-10 hidden lg:block">
  <div class="container mx-auto text-center text-sm">
    © {{ date('Y') }} MyStore. All rights reserved.
  </div>
</footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
     <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

function addToCart(productId) {

fetch("{{ route('cart.add') }}", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({
        product_id: productId,
        quantity: 1
    })
})
.then(response => response.json())
.then(data => {
    toastr.success("{{ session('success') }}", data.message, {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 5000
    });
    updateCartCount(data.cart);
})
.catch(error => console.error("Error:", error));
}
function updateCartCount(cart) {
  let totalCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
  document.getElementById("cart-count").textContent = totalCount;
}

</script>

   