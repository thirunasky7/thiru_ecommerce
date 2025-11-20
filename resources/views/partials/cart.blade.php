<!-- resources/views/partials/cart.blade.php -->
<div id="cart-sidebar" class="fixed inset-y-0 right-0 z-50 w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Cart Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Your Cart</h3>
            <button onclick="closeCart()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4" id="cart-items-container">
            <!-- Cart items will be loaded here dynamically -->
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Your cart is empty</p>
            </div>
        </div>

        <!-- Cart Footer -->
        <div class="border-t p-4 bg-gray-50">
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold" id="cart-subtotal">$0.00</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Shipping</span>
                    <span class="font-semibold" id="cart-shipping">$0.00</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Total</span>
                    <span id="cart-total">$0.00</span>
                </div>
                
                <button onclick="proceedToCheckout()" 
                        class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        id="checkout-btn"
                        disabled>
                    Proceed to Checkout
                </button>
                
                <button onclick="closeCart()" 
                        class="w-full border border-gray-300 text-gray-700 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cart Overlay -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="closeCart()"></div>

<style>
    #cart-sidebar.open {
        transform: translateX(0);
    }
    #cart-overlay.open {
        display: block;
    }
</style>

<script>
// Cart functions
function openCart() {
    document.getElementById('cart-sidebar').classList.add('open');
    document.getElementById('cart-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    loadCartItems();
}

function closeCart() {
    document.getElementById('cart-sidebar').classList.remove('open');
    document.getElementById('cart-overlay').classList.remove('open');
    document.body.style.overflow = 'auto';
}

function loadCartItems() {
    // This would typically make an AJAX call to get cart items
    // For now, we'll use the cart data from localStorage or session
    const cart = JSON.parse(localStorage.getItem('foodCart')) || [];
    updateCartDisplay(cart);
}

function updateCartDisplay(cart) {
    const container = document.getElementById('cart-items-container');
    const subtotalEl = document.getElementById('cart-subtotal');
    const totalEl = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Your cart is empty</p>
            </div>
        `;
        subtotalEl.textContent = '$0.00';
        totalEl.textContent = '$0.00';
        checkoutBtn.disabled = true;
        return;
    }
    
    let subtotal = 0;
    let html = '';
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        html += `
            <div class="flex items-center space-x-3 py-3 border-b" data-cart-item="${item.id}">
                <img src="${item.image || 'https://via.placeholder.com/60x60'}" 
                     alt="${item.name}" 
                     class="w-16 h-16 rounded-lg object-cover">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                    <p class="text-gray-600 text-sm">$${item.price} × ${item.quantity}</p>
                    ${item.meal_type && item.meal_type !== 'regular' ? 
                        `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-1">${item.meal_type}</span>` : ''}
                    ${item.deliveryDate ? 
                        `<p class="text-xs text-gray-500 mt-1">Delivery: ${item.deliveryDate}</p>` : ''}
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">$${itemTotal.toFixed(2)}</p>
                    <button onclick="removeFromCart('${item.id}')" 
                            class="text-red-600 hover:text-red-800 text-sm mt-1">
                        Remove
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    totalEl.textContent = `$${subtotal.toFixed(2)}`;
    checkoutBtn.disabled = false;
}

function removeFromCart(itemId) {
    let cart = JSON.parse(localStorage.getItem('foodCart')) || [];
    cart = cart.filter(item => item.id !== itemId);
    localStorage.setItem('foodCart', JSON.stringify(cart));
    updateCartDisplay(cart);
    updateCartCount(cart.length);
}

function proceedToCheckout() {
    window.location.href = '{{ route("checkout.index") }}';
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count, #cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
        if (count > 0) {
            element.style.display = 'flex';
        } else {
            element.style.display = 'none';
        }
    });
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('foodCart')) || [];
    updateCartCount(cart.length);
});
</script>