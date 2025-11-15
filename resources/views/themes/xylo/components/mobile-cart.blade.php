<!-- CART BOTTOM DRAWER -->
<div id="cartDrawer"
     class="fixed bottom-0 left-0 right-0 bg-white shadow-2xl rounded-t-2xl transform translate-y-full transition-all duration-300 z-[9999]">

    <!-- Header -->
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-bold">Your Cart</h2>
        <button onclick="toggleCart()" class="text-gray-500">
            <i class="fa fa-times text-xl"></i>
        </button>
    </div>

    <!-- Cart Items -->
    <div id="cartItems" class="max-h-[55vh] overflow-y-auto p-4 space-y-4">
        <!-- Dynamic items will be rendered here -->
    </div>

    <!-- Cart Total -->
    <div class="p-4 border-t bg-gray-50">
        <div class="flex justify-between mb-2">
            <span class="text-lg font-semibold">Total</span>
            <span id="cartTotal" class="text-xl font-extrabold text-green-600">₹0</span>
        </div>

        <button class="w-full bg-green-600 text-white py-3 rounded-xl text-lg font-bold">
            Checkout Now
        </button>
    </div>
</div>

<!-- BACKDROP -->
<div id="cartBackdrop"
     onclick="toggleCart()"
     class="fixed inset-0 bg-black/40 hidden z-[9990]">
</div>

<script>
let cartDrawer = document.getElementById("cartDrawer");
let cartBackdrop = document.getElementById("cartBackdrop");

// Toggle Cart Drawer
function toggleCart() {
    if (cartDrawer.classList.contains("translate-y-full")) {
        loadCart();
        cartDrawer.classList.remove("translate-y-full");
        cartBackdrop.classList.remove("hidden");
    } else {
        cartDrawer.classList.add("translate-y-full");
        cartBackdrop.classList.add("hidden");
    }
}

// Load Cart Data from Session (AJAX)
function loadCart() {
    fetch("{{ route('cart') }}")
        .then(res => res.json())
        .then(cart => {
            renderCart(cart);
        });
}

// Render Cart UI
function renderCart(cart) {
    const container = document.getElementById("cartItems");
    const totalBox = document.getElementById("cartTotal");
    let html = "";
    let total = 0;

    Object.values(cart).forEach(item => {
        total += item.price * item.quantity;

        html += `
        <div class="flex items-center justify-between bg-white p-3 rounded-xl shadow-sm border">
            <div class="flex items-center space-x-3">
                <img src="/storage/${item.image}" class="w-14 h-14 rounded-lg object-cover">
                <div>
                    <h3 class="font-semibold">${item.name}</h3>
                    <p class="text-sm text-gray-500">₹${item.price}</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <button onclick="changeQty(${item.id}, 'minus')" 
                        class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-full">
                    -
                </button>

                <span class="font-bold">${item.quantity}</span>

                <button onclick="changeQty(${item.id}, 'plus')" 
                        class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-full">
                    +
                </button>

                <button onclick="removeItem(${item.id})" class="ml-2 text-red-500">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;
    });

    container.innerHTML = html || "<p class='text-center text-gray-500'>Your cart is empty</p>";
    totalBox.innerHTML = "₹" + total;
}

// Change Quantity
function changeQty(id, type) {
    fetch("{{ route('cart') }}")
        .then(res => res.json())
        .then(cart => {
            let qty = cart[id].quantity;

            qty = type === "plus" ? qty + 1 : Math.max(1, qty - 1);

            updateQuantity(id, qty);
        });
}

function updateQuantity(id, qty) {
    fetch("{{ route('cart.update') }}", {
        method: "POST",
        headers: { 
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ product_id: id, quantity: qty })
    })
    .then(() => loadCart());
}

// Remove Item
function removeItem(id) {
    fetch(`/cart/remove/${id}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
    .then(() => loadCart());
}
</script>
