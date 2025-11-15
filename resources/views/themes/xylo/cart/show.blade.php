@extends('themes.xylo.partials.app')

@section('title', 'My Cart - Thaiyur Shop')

@section('content')
@php $currency = activeCurrency(); @endphp
<div class="min-h-screen bg-gray-50 pb-20">
    <!-- Sticky Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button onclick="window.history.back()" class="p-2 text-gray-600 hover:text-orange-500">
                        <i class="fa fa-arrow-left text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">My Cart</h1>
                        <p class="text-xs text-gray-500" id="cart-summary">0 items</p>
                    </div>
                </div>
                <button onclick="clearCart()" class="p-2 text-gray-600 hover:text-red-500 transition-colors">
                    <i class="fa fa-trash text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="p-4">
        <!-- Empty State -->
        <div id="empty-cart" class="hidden text-center py-12">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-shopping-cart text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 mb-6">Add some delicious items to get started!</p>
            <a href="{{ url('/') }}" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200">
                <i class="fa fa-utensils mr-2"></i>Browse Menu
            </a>
        </div>

        <!-- Cart Items -->
        <div id="cart-content">
            <!-- Items grouped by delivery date -->
            <div id="cart-items-container">
                <!-- Cart items will be loaded here dynamically -->
            </div>

            <!-- Order Summary -->
            <div id="order-summary" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mt-6 hidden">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="subtotal-amount" class="font-semibold">₹0.00</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Delivery Charge</span>
                        <span id="delivery-charge" class="font-semibold">₹0.00</span>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span id="total-amount" class="text-xl font-bold text-orange-600">₹0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button onclick="openCheckoutModal()" 
                        class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-200 mt-6 active:scale-95">
                    <i class="fa fa-lock mr-2"></i>Proceed to Checkout
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div id="checkout-modal" class="fixed inset-0 z-50 transform translate-y-full transition-transform duration-300">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeCheckoutModal()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-4 py-4 rounded-t-3xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Checkout</h2>
                <button onclick="closeCheckoutModal()" class="p-2 text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="p-4">
            <form id="checkout-form" onsubmit="processCheckout(event)">
                <!-- Customer Details -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="customer_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Enter your full name">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                            <input type="tel" name="customer_phone" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Enter your mobile number">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address *</label>
                            <textarea name="delivery_address" required rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Enter your complete delivery address"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Instructions (Optional)</label>
                            <textarea name="delivery_instructions" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Any special delivery instructions?"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash" checked class="text-orange-500 focus:ring-orange-500">
                            <i class="fa fa-money-bill-wave text-green-500 text-lg"></i>
                            <span class="font-medium">Cash on Delivery</span>
                        </label>
                        
                        <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="online" class="text-orange-500 focus:ring-orange-500">
                            <i class="fa fa-credit-card text-blue-500 text-lg"></i>
                            <span class="font-medium">Online Payment</span>
                        </label>
                    </div>
                </div>

                <!-- Order Review -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Review</h3>
                    <div id="checkout-items" class="space-y-3">
                        <!-- Checkout items will be loaded here -->
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-200 active:scale-95">
                    <i class="fa fa-paper-plane mr-2"></i>Place Order
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 bg-gray-800 text-white px-6 py-3 rounded-full shadow-lg transition-all duration-300 opacity-0 -translate-y-2">
    <div class="flex items-center space-x-3">
        <i class="fa fa-check-circle text-green-400"></i>
        <span class="font-semibold" id="toast-message"></span>
    </div>
</div>
@endsection

@push('styles')
<style>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

[x-cloak] {
    display: none !important;
}

/* Smooth transitions */
* {
    -webkit-tap-highlight-color: transparent;
}
</style>
@endpush

@push('scripts')
<script>
// Cart Management
let cart = JSON.parse(localStorage.getItem('foodCart')) || [];

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCartPage();
});

// Load cart page content
function loadCartPage() {
    if (cart.length === 0) {
        showEmptyCart();
    } else {
        showCartContent();
        renderCartItems();
        updateOrderSummary();
    }
}

// Show empty cart state
function showEmptyCart() {
    document.getElementById('empty-cart').classList.remove('hidden');
    document.getElementById('cart-content').classList.add('hidden');
    document.getElementById('cart-summary').textContent = '0 items';
}

// Show cart content
function showCartContent() {
    document.getElementById('empty-cart').classList.add('hidden');
    document.getElementById('cart-content').classList.remove('hidden');
}

// Render cart items
function renderCartItems() {
    const container = document.getElementById('cart-items-container');
    let html = '';

    // Group items by delivery date
    const groupedItems = cart.reduce((groups, item) => {
        const date = item.deliveryDate;
        if (!groups[date]) groups[date] = [];
        groups[date].push(item);
        return groups;
    }, {});

    Object.keys(groupedItems).forEach(date => {
        html += `
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-gray-900 text-lg">${formatDate(date)}</h3>
                    <span class="text-sm text-gray-500">${groupedItems[date].length} ${groupedItems[date].length === 1 ? 'item' : 'items'}</span>
                </div>
                <div class="space-y-3">
        `;

        groupedItems[date].forEach((item, index) => {
            const itemIndex = cart.findIndex(cartItem => 
                cartItem.id === item.id && 
                cartItem.deliveryDate === item.deliveryDate && 
                cartItem.mealType === item.mealType
            );
            
            const itemTotal = item.price * item.quantity;

            html += `
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <!-- Item Image -->
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa fa-utensils text-gray-400"></i>
                        </div>
                        
                        <!-- Item Details -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm truncate">${item.name}</h4>
                            <p class="text-gray-500 text-xs">${item.mealType}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-green-600 font-bold">${item.price} × ${item.quantity} = ₹${itemTotal.toFixed(2)}</span>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity(${itemIndex}, ${item.quantity - 1})" 
                                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs ${item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300'}">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <span class="font-semibold text-sm w-8 text-center">${item.quantity}</span>
                                    <button onclick="updateQuantity(${itemIndex}, ${item.quantity + 1})" 
                                            class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs hover:bg-gray-300">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Remove Button -->
                        <button onclick="removeItem(${itemIndex})" class="text-red-500 hover:text-red-700 transition-colors">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    
    // Update cart summary
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cart-summary').textContent = `${totalItems} ${totalItems === 1 ? 'item' : 'items'}`;
}

// Update order summary
function updateOrderSummary() {
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const deliveryCharge = 0; // You can add delivery logic here
    const total = subtotal + deliveryCharge;

    document.getElementById('subtotal-amount').textContent = `₹${subtotal.toFixed(2)}`;
    document.getElementById('delivery-charge').textContent = `₹${deliveryCharge.toFixed(2)}`;
    document.getElementById('total-amount').textContent = `₹${total.toFixed(2)}`;
    
    document.getElementById('order-summary').classList.remove('hidden');
}

// Update item quantity
function updateQuantity(index, newQuantity) {
    if (newQuantity < 1) {
        removeItem(index);
        return;
    }

    cart[index].quantity = newQuantity;
    localStorage.setItem('foodCart', JSON.stringify(cart));
    renderCartItems();
    updateOrderSummary();
    showToast('Cart updated');
}

// Remove item from cart
function removeItem(index) {
    cart.splice(index, 1);
    localStorage.setItem('foodCart', JSON.stringify(cart));
    
    if (cart.length === 0) {
        showEmptyCart();
    } else {
        renderCartItems();
        updateOrderSummary();
    }
    
    showToast('Item removed from cart', 'warning');
}

// Clear entire cart
function clearCart() {
    if (cart.length === 0) {
        showToast('Cart is already empty', 'info');
        return;
    }

    if (confirm('Are you sure you want to clear your cart?')) {
        cart = [];
        localStorage.setItem('foodCart', JSON.stringify(cart));
        showEmptyCart();
        showToast('Cart cleared', 'info');
    }
}

// Checkout Modal Functions
function openCheckoutModal() {
    if (cart.length === 0) {
        showToast('Cart is empty', 'warning');
        return;
    }

    // Render checkout items
    renderCheckoutItems();
    document.getElementById('checkout-modal').classList.remove('translate-y-full');
}

function closeCheckoutModal() {
    document.getElementById('checkout-modal').classList.add('translate-y-full');
}

function renderCheckoutItems() {
    const container = document.getElementById('checkout-items');
    let html = '';

    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        html += `
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <div class="flex-1">
                    <p class="font-medium text-gray-900">${item.name}</p>
                    <p class="text-sm text-gray-500">${formatDate(item.deliveryDate)} • ${item.mealType} • Qty: ${item.quantity}</p>
                </div>
                <span class="font-semibold text-gray-900">₹${itemTotal.toFixed(2)}</span>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Process checkout
function processCheckout(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const orderData = {
        customer_name: formData.get('customer_name'),
        customer_phone: formData.get('customer_phone'),
        delivery_address: formData.get('delivery_address'),
        delivery_instructions: formData.get('delivery_instructions'),
        payment_method: formData.get('payment_method'),
        items: cart,
        total_amount: cart.reduce((total, item) => total + (item.price * item.quantity), 0)
    };

    // Here you would typically send this data to your backend
    console.log('Order Data:', orderData);
    
    // Simulate order processing
    showToast('Processing your order...', 'info');
    
    setTimeout(() => {
        // Clear cart after successful order
        cart = [];
        localStorage.setItem('foodCart', JSON.stringify(cart));
        
        closeCheckoutModal();
        showEmptyCart();
        showToast('Order placed successfully! We will contact you soon.', 'success');
    }, 2000);
}

// Helper function to format dates
function formatDate(dateString) {
    const date = new Date(dateString);
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    if (date.toDateString() === today.toDateString()) return 'Today';
    if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow';
    
    return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    let icon = 'fa-check-circle';
    let bgColor = '#1f2937';
    
    switch (type) {
        case 'warning':
            icon = 'fa-exclamation-triangle';
            bgColor = '#f59e0b';
            break;
        case 'error':
            icon = 'fa-times-circle';
            bgColor = '#ef4444';
            break;
        case 'info':
            icon = 'fa-info-circle';
            bgColor = '#3b82f6';
            break;
    }
    
    toastMessage.innerHTML = `<i class="fa ${icon} mr-2"></i>${message}`;
    toast.style.background = bgColor;
    toast.classList.remove('opacity-0', '-translate-y-2');
    toast.classList.add('opacity-100', 'translate-y-0');
    
    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', '-translate-y-2');
    }, 3000);
}
</script>
@endpush